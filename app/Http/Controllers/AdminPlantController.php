<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Plant;
use App\Models\PlantPopulationHistory;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Writer\SvgWriter;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AdminPlantController extends Controller
{
    public function login(): View
    {
        return view('admin.login');
    }

    public function authenticate(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'username' => ['nullable', 'string'],
            'password' => ['required', 'string'],
        ]);

        $user = null;
        if ($credentials['username'] !== null && $credentials['username'] !== '') {
            $user = \App\Models\User::where('name', $credentials['username'])->first();
        }

        $authenticated = $user !== null
            ? Hash::check($credentials['password'], $user->password)
            : ($credentials['username'] === null || $credentials['username'] === '')
                && hash_equals((string) config('admin.password'), $credentials['password']);

        if (! $authenticated) {
            return back()->withErrors(['password' => 'Password admin tidak sesuai.'])->onlyInput('password');
        }

        $request->session()->regenerate();
        $request->session()->put('admin_authenticated', true);
        $request->session()->put('admin_user_id', $user?->id ?? config('admin.user_id'));

        return redirect()->intended(route('admin.plants.index'));
    }

    public function logout(Request $request): RedirectResponse
    {
        $request->session()->forget('admin_authenticated');
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        \Illuminate\Support\Facades\Cookie::queue(
            \Illuminate\Support\Facades\Cookie::forget(\App\Http\Middleware\CrossPortAuthMiddleware::COOKIE_NAME, '/', null)
        );

        return redirect()->route('admin.login');
    }

    public function index(Request $request): View
    {
        $query = Plant::with('category');

        $query->when($request->filled('search'), fn ($q) => $q->search($request->string('search')->toString()));
        $categoryIds = collect($request->input('category', []))
            ->filter(fn ($categoryId) => ctype_digit((string) $categoryId))
            ->map(fn ($categoryId) => (int) $categoryId)
            ->values()
            ->all();

        $query->when($categoryIds, fn ($q) => $q->whereIn('category_id', $categoryIds));
        $query->when(in_array($request->input('status'), ['active', 'inactive'], true), function ($q) use ($request) {
            $q->where('is_active', $request->input('status') === 'active');
        });

        $statisticsQuery = clone $query;

        $query->when($request->filled('location'), function ($q) use ($request) {
            $location = $request->input('location');
            if ($location === 'greenhouse') {
                $q->whereIn('location_type', ['greenhouse', 'both']);
            } elseif ($location === 'outdoor') {
                $q->whereIn('location_type', ['outdoor', 'collection', 'both']);
            }
        });

        $categorySummary = (clone $query)
            ->reorder()
            ->select('category_id', DB::raw('COUNT(*) as plant_count'), DB::raw('COALESCE(SUM(population), 0) as population_total'))
            ->groupBy('category_id')
            ->with('category')
            ->get()
            ->sortBy(fn ($item) => $item->category?->name)
            ->values();

        $locationSummary = (clone $statisticsQuery)
            ->reorder()
            ->select('location_type', DB::raw('COUNT(*) as plant_count'))
            ->groupBy('location_type')
            ->pluck('plant_count', 'location_type');

        $greenhousePopulationSum = (clone $statisticsQuery)->sum('greenhouse_population');
        $outdoorPopulationSum = (clone $statisticsQuery)->sum('outdoor_population');

        $sortColumns = [
            'name' => 'plants.name',
            'category' => 'categories.name',
            'location' => 'plants.location_type',
            'population' => 'plants.population',
            'status' => 'plants.is_active',
        ];
        $sort = $request->input('sort', 'name');
        $direction = $request->input('direction') === 'desc' ? 'desc' : 'asc';

        if ($sort === 'category') {
            $query->leftJoin('categories', 'categories.id', '=', 'plants.category_id')->select('plants.*');
        }

        // --- PENGURUTAN KHUSUS LOKASI ---
        if ($sort === 'location') {
            $locationOrder = $direction === 'asc'
                ? "CASE plants.location_type WHEN 'greenhouse' THEN 1 WHEN 'outdoor' THEN 2 WHEN 'collection' THEN 2 WHEN 'both' THEN 3 ELSE 4 END"
                : "CASE plants.location_type WHEN 'both' THEN 1 WHEN 'outdoor' THEN 2 WHEN 'collection' THEN 2 WHEN 'greenhouse' THEN 3 ELSE 4 END";
            $query->orderByRaw($locationOrder);
        } 
        // --- PENGURUTAN KHUSUS STATUS ---
        elseif ($sort === 'status') {
            if ($direction === 'asc') {
                $query->orderByRaw("CASE WHEN plants.is_active = 1 THEN 1 ELSE 2 END");
            } else {
                $query->orderByRaw("CASE WHEN plants.is_active = 1 THEN 2 ELSE 1 END");
            }
        } 
        else {
            $query->orderBy($sortColumns[$sort] ?? $sortColumns['name'], $direction);
        }

        $query->orderBy('plants.id');

        // --- UPDATE BAGIAN SUMMARY ---
        $summary = [
            'total' => (clone $statisticsQuery)->count(),
            'population' => $greenhousePopulationSum + $outdoorPopulationSum,
            'greenhouse' => $locationSummary->get('greenhouse', 0) + $locationSummary->get('both', 0),
            'outdoor' => $locationSummary->get('outdoor', 0) + $locationSummary->get('collection', 0) + $locationSummary->get('both', 0),
            'greenhouse_population' => $greenhousePopulationSum,
            'outdoor_population' => $outdoorPopulationSum,
        ];

        $plants = $query
            ->paginate(10)
            ->withQueryString();

        return view('admin.plants.index', [
            'plants' => $plants,
            'summary' => $summary,
            'categorySummary' => $categorySummary,
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function export(): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        return response()->streamDownload(function (): void {
            $handle = fopen('php://output', 'w');
            if ($handle === false) {
                throw new \RuntimeException('File ekspor tidak dapat dibuat.');
            }

            fwrite($handle, "\xEF\xBB\xBF");
            fputcsv($handle, [
                'Nama tanaman',
                'Kode aksesi',
                'Kategori',
                'Nama latin',
                'Kingdom',
                'Famili',
                'Ordo',
                'Genus',
                'Botanist',
                'Lokasi',
                'Populasi',
                'Populasi greenhouse',
                'Populasi outdoor',
                'Kawasan asal',
                'Sebaran botani',
                'Status',
                'URL QR',
            ]);

            Plant::with('category')->orderBy('name')->each(function (Plant $plant) use ($handle): void {
                fputcsv($handle, [
                    $plant->name,
                    $plant->accession_code,
                    $plant->category?->name,
                    $plant->latin_name,
                    $plant->kingdom,
                    $plant->family,
                    $plant->ordo,
                    $plant->genus,
                    $plant->botanist,
                    $plant->location_type,
                    $plant->population,
                    $plant->greenhouse_population,
                    $plant->outdoor_population,
                    $plant->origin,
                    $plant->origin_distribution,
                    $plant->is_active ? 'Aktif' : 'Nonaktif',
                    $plant->public_qr_url,
                ]);
            });

            fclose($handle);
        }, 'data-tanaman-' . now()->format('Y-m-d') . '.csv', [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    public function create(): View
    {
        return view('admin.plants.create', [
            'plant' => new Plant(),
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $plant = new Plant();
        $this->savePlant($plant, $request);

        return redirect()->route('admin.plants.index')->with('success', 'Tanaman berhasil ditambahkan.');
    }

    public function edit(Plant $plant): View
    {
        return view('admin.plants.edit', [
            'plant' => $plant,
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function qr(Plant $plant): \Illuminate\Http\Response
    {
        return $this->qrResponse($plant);
    }

    public function qrDownload(Plant $plant): \Illuminate\Http\Response
    {
        return $this->qrResponse($plant, true);
    }

    public function qrPrint(Plant $plant): View
    {
        return view('admin.plants.qr-print', [
            'plant' => $plant,
            'qrUrl' => route('admin.plants.qr', $plant),
        ]);
    }

    public function history(Plant $plant): View
    {
        $plant->load('category');
        $histories = $plant->populationHistories()
            ->with('user')
            ->paginate(15)
            ->withQueryString();

        return view('admin.plants.history', compact('plant', 'histories'));
    }

    public function updatePopulation(Request $request, Plant $plant): RedirectResponse
    {
        $changeType = $request->input('change_type');
        $locationType = $request->input('location_type');
        $reasons = [
            'add' => ['purchase', 'propagation', 'initial_stock', 'other'],
            'subtract' => ['damaged', 'sale_distribution', 'other'],
        ];

        $validated = $request->validate([
            'change_type' => ['required', Rule::in(array_keys($reasons))],
            'location_type' => ['required', Rule::in(['greenhouse', 'outdoor'])],
            'change_amount' => ['required', 'integer', 'min:1'],
            'reason' => ['required', Rule::in($reasons[$changeType] ?? [])],
            'other_reason' => ['required_if:reason,other', 'nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:5000'],
            'documentation_photo' => [
                Rule::requiredIf($changeType === 'subtract'),
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:10240',
            ],
        ], [
            'documentation_photo.required' => 'Foto dokumentasi wajib diunggah untuk perubahan kurang.',
        ]);

        $photo = null;
        DB::transaction(function () use ($request, $plant, $validated, $changeType, $locationType, &$photo): void {
            $lockedPlant = Plant::whereKey($plant->getKey())->lockForUpdate()->firstOrFail();
            $greenhouseBefore = (int) ($lockedPlant->greenhouse_population ?? 0);
            $outdoorBefore = (int) ($lockedPlant->outdoor_population ?? 0);
            $before = $locationType === 'greenhouse' ? $greenhouseBefore : $outdoorBefore;
            $amount = $changeType === 'subtract'
                ? -abs((int) $validated['change_amount'])
                : abs((int) $validated['change_amount']);
            $after = $before + $amount;

            if ($after < 0) {
                throw ValidationException::withMessages([
                    'change_amount' => 'Jumlah pengurangan tidak boleh melebihi populasi saat ini.',
                ]);
            }

            if ($request->hasFile('documentation_photo')) {
                // DIPERBAIKI KE FILEBASE
                $photo = basename($request->file('documentation_photo')->store('plant-population-history', 'filebase'));
            }

            $greenhouseAfter = $locationType === 'greenhouse' ? $after : $greenhouseBefore;
            $outdoorAfter = $locationType === 'outdoor' ? $after : $outdoorBefore;
            $lockedPlant->update([
                'greenhouse_population' => $greenhouseAfter,
                'outdoor_population' => $outdoorAfter,
                'population' => $greenhouseAfter + $outdoorAfter,
            ]);

            PlantPopulationHistory::create([
                'plant_id' => $lockedPlant->id,
                'user_id' => auth()->id() ?: session('admin_user_id'),
                'recorder_name' => auth()->user()?->name ?? config('admin.user_name', 'Admin'),
                'change_type' => $changeType,
                'location_type' => $locationType,
                'change_amount' => $amount,
                'population_before' => $before,
                'population_after' => $after,
                'greenhouse_population_before' => $greenhouseBefore,
                'greenhouse_population_after' => $greenhouseAfter,
                'outdoor_population_before' => $outdoorBefore,
                'outdoor_population_after' => $outdoorAfter,
                'reason' => $validated['reason'],
                'other_reason' => $validated['other_reason'] ?? null,
                'notes' => $validated['notes'] ?? null,
                'documentation_photo' => $photo,
                'changed_at' => now(),
            ]);
        });

        return redirect()->route('admin.plants.history', $plant)->with('success', 'Jumlah populasi berhasil diperbarui.');
    }

    public function update(Request $request, Plant $plant): RedirectResponse
    {
        $this->savePlant($plant, $request);

        return redirect()->route('admin.plants.index')->with('success', 'Tanaman berhasil diperbarui.');
    }

    public function destroy(Plant $plant): RedirectResponse
    {
        if ($plant->image) {
            Storage::disk('filebase')->delete('plants/' . $plant->image);
        }
        foreach ($plant->image_gallery ?? [] as $image) {
            Storage::disk('filebase')->delete('plants/' . $image);
        }

        $plant->delete();

        return back()->with('success', 'Tanaman berhasil dihapus.');
    }

    private function savePlant(Plant $plant, Request $request): void
    {
        $locationTypes = array_values(array_unique(array_filter(
            (array) $request->input('location_type', []),
            fn ($location) => in_array($location, ['greenhouse', 'outdoor'], true)
        )));
        $request->merge(['location_type' => $locationTypes]);

        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'location_type' => ['required', 'array', 'min:1'],
            'location_type.*' => ['required', Rule::in(['greenhouse', 'outdoor'])],
            'population' => ['nullable', 'integer', 'min:0'],
            'greenhouse_population' => ['nullable', 'integer', 'min:0'],
            'outdoor_population' => ['nullable', 'integer', 'min:0'],
            'name' => ['required', 'string', 'max:255'],
            'accession_code' => ['nullable', 'string', 'max:255'],
            'kingdom' => ['required', 'string', 'max:255'],
            'latin_name' => ['required', 'string', 'max:255'],
            'family' => ['required', 'string', 'max:255'],
            'ordo' => ['required', 'string', 'max:255'],
            'genus' => ['required', 'string', 'max:255'],
            'botanist' => ['required', 'string', 'max:255'],
            'description_short' => ['required', 'string'],
            'description_full' => ['required', 'string'],
            'benefit_items' => ['required', 'array', 'min:1'],
            'benefit_items.*.title' => ['required', 'string', 'max:255'],
            'benefit_items.*.description' => ['nullable', 'string'],
            'origin' => ['required', 'string', 'max:255'],
            'origin_distribution' => ['required', 'string'],
            'image' => [$plant->exists ? 'nullable' : 'required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:10240'],
            'remove_image' => ['nullable', 'boolean'],
            'images' => ['nullable', 'array', 'max:12'],
            'images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:10240'],
            'remove_gallery_images' => ['nullable', 'array'],
            'remove_gallery_images.*' => ['string'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['benefits'] = json_encode(
            collect($validated['benefit_items'])
                ->map(fn (array $item): array => [
                    'title' => trim($item['title']),
                    'description' => trim($item['description'] ?? ''),
                ])
                ->values()
                ->all(),
            JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR
        );
        unset($validated['benefit_items']);
        if ($plant->exists) {
            $validated['greenhouse_population'] = $plant->greenhouse_population;
            $validated['outdoor_population'] = $plant->outdoor_population;
        }
        $validated['location_type'] = count($validated['location_type']) === 2
            ? 'both'
            : $validated['location_type'][0];
        if (! $plant->exists) {
            $validated['greenhouse_population'] = (int) ($validated['greenhouse_population'] ?? 0);
            $validated['outdoor_population'] = (int) ($validated['outdoor_population'] ?? 0);
        }
        $validated['population'] = (int) ($validated['greenhouse_population'] ?? 0)
            + (int) ($validated['outdoor_population'] ?? 0);
        $slugRule = Rule::unique('plants', 'slug')->ignore($plant->id);
        validator(['slug' => $validated['slug']], ['slug' => [$slugRule]])->validate();
        $validated['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('image')) {
            if ($plant->image) {
                Storage::disk('filebase')->delete('plants/' . $plant->image);
            }
            $validated['image'] = $request->file('image')->store('plants', 'filebase');
            $validated['image'] = basename($validated['image']);
        } elseif ($plant->exists && $request->boolean('remove_image')) {
            if ($plant->image) {
                Storage::disk('filebase')->delete('plants/' . $plant->image);
            }
            $validated['image'] = null;
        }

        if ($request->hasFile('images')) {
            foreach ($plant->image_gallery ?? [] as $image) {
                Storage::disk('filebase')->delete('plants/' . $image);
            }

            $validated['image_gallery'] = collect($request->file('images'))
                ->map(fn ($image) => basename($image->store('plants', 'filebase')))
                ->all();
        } elseif ($plant->exists && $request->filled('remove_gallery_images')) {
            $currentGallery = collect($plant->image_gallery ?? [])->filter()->values();
            $removedGallery = collect($request->input('remove_gallery_images', []))
                ->intersect($currentGallery);

            foreach ($removedGallery as $image) {
                Storage::disk('filebase')->delete('plants/' . $image);
            }

            $validated['image_gallery'] = $currentGallery
                ->diff($removedGallery)
                ->values()
                ->all();
        }

        $plant->fill($validated)->save();
    }

    private function qrResponse(Plant $plant, bool $download = false): \Illuminate\Http\Response
    {
        $usePng = extension_loaded('gd');
        $logoPath = public_path('images/tsth2-logo.png');

        if ($usePng && ! is_file($logoPath)) {
            throw new \RuntimeException('Logo QR Code tidak dapat dibaca.');
        }

        $result = (new Builder(
            writer: $usePng ? new PngWriter() : new SvgWriter(),
            data: route('plant.qr', ['token' => $plant->qr_token]),
            encoding: new Encoding('UTF-8'),
            errorCorrectionLevel: ErrorCorrectionLevel::High,
            size: 320,
            margin: 12,
            roundBlockSizeMode: RoundBlockSizeMode::Margin,
            foregroundColor: new Color(0, 63, 50),
            backgroundColor: new Color(255, 255, 255),
            logoPath: $usePng ? $logoPath : '',
            logoResizeToWidth: $usePng ? 64 : null,
            logoPunchoutBackground: $usePng,
        ))
            ->build();

        $extension = $usePng ? 'png' : 'svg';
        $headers = ['Content-Type' => $usePng ? 'image/png' : 'image/svg+xml'];
        if ($download) {
            $headers['Content-Disposition'] = 'attachment; filename="qr-' . $plant->slug . '.' . $extension . '"';
        } else {
            $headers['Cache-Control'] = 'public, max-age=86400';
        }

        return response($result->getString(), 200, $headers);
    }
}
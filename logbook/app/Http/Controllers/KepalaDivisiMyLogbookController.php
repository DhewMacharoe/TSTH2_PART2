<?php

namespace App\Http\Controllers;

use App\Models\Logbook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class KepalaDivisiMyLogbookController extends Controller
{
    /**
     * Menampilkan daftar logbook pribadi milik Kepala Divisi yang sedang login.
     */
    public function index()
    {
        $user = auth()->user();

        $logbooks = Logbook::with(['reviewer'])
            ->where('user_id', $user->id)
            ->latest('date')
            ->latest('id')
            ->paginate(10);

        $totalLogbooks = Logbook::where('user_id', $user->id)->count();
        $pendingCount = Logbook::where('user_id', $user->id)->where('status', 'pending')->count();
        $approvedCount = Logbook::where('user_id', $user->id)->where('status', 'approved')->count();
        $revisionCount = Logbook::where('user_id', $user->id)->where('status', 'revision')->count();

        return view('kepala-divisi.my-logbooks.index', compact(
            'logbooks',
            'totalLogbooks',
            'pendingCount',
            'approvedCount',
            'revisionCount'
        ));
    }

    /**
     * Menampilkan form tambah logbook pribadi Kepala Divisi.
     */
    public function create()
    {
        $user = auth()->user();
        $department = $user->department;
        $division = $user->divisionRelation;

        return view('kepala-divisi.my-logbooks.create', compact('user', 'department', 'division'));
    }

    /**
     * Menyimpan logbook pribadi baru Kepala Divisi.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'activity' => 'required|string',
            'obstacle' => 'nullable|string',
            'documentation_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'documentation_images' => 'nullable|array',
            'documentation_images.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'documentation_link' => 'nullable|string|max:2048',
            'documentation_links' => 'nullable|array',
            'documentation_links.*' => 'nullable|string|max:2048',
            'next_plan' => 'nullable|string',
        ]);

        // Upload dokumentasi foto (bisa lebih dari 1 file gambar)
        $uploadedImages = [];
        if ($request->hasFile('documentation_images')) {
            $files = $request->file('documentation_images');
            if (! is_array($files)) {
                $files = [$files];
            }
            foreach ($files as $file) {
                if ($file && $file->isValid()) {
                    $uploadedImages[] = $file->store('logbook-documentation', 'public');
                }
            }
        } elseif ($request->hasFile('documentation_image')) {
            $file = $request->file('documentation_image');
            if ($file && $file->isValid()) {
                $uploadedImages[] = $file->store('logbook-documentation', 'public');
            }
        }

        // Normalisasi dan validasi link dokumentasi
        $normalizedLinks = [];
        $rawLinks = $request->input('documentation_links');
        if (! is_array($rawLinks) && $request->filled('documentation_link')) {
            $rawLinks = [$request->input('documentation_link')];
        }

        if (is_array($rawLinks)) {
            foreach ($rawLinks as $rawLink) {
                $norm = $this->normalizeDocumentationLink($rawLink);
                if ($norm) {
                    if (! filter_var($norm, FILTER_VALIDATE_URL)) {
                        return back()
                            ->withErrors(['documentation_links' => "Format tautan '{$rawLink}' tidak valid."])
                            ->withInput();
                    }
                    $normalizedLinks[] = $norm;
                }
            }
        }

        // Simpan logbook pribadi Kepala Divisi (tipe independent, tanpa progres tugas)
        Logbook::create([
            'user_id' => auth()->id(),
            'task_id' => null,
            'logbook_type' => 'independent',
            'date' => $validated['date'],
            'activity' => $validated['activity'],
            'obstacle' => $validated['obstacle'] ?? null,
            'documentation_image' => $uploadedImages[0] ?? null,
            'documentation_images' => count($uploadedImages) > 0 ? $uploadedImages : null,
            'documentation_link' => $normalizedLinks[0] ?? null,
            'documentation_links' => count($normalizedLinks) > 0 ? $normalizedLinks : null,
            'next_plan' => $validated['next_plan'] ?? null,
            'status' => 'pending',
            'progress' => null,
        ]);

        return redirect()
            ->route('kepala_divisi.my_logbooks.index')
            ->with('success', 'Logbook pribadi berhasil disimpan dan diajukan untuk direview oleh Kepala Departemen.');
    }

    /**
     * Menampilkan detail logbook pribadi Kepala Divisi.
     */
    public function show($logbook)
    {
        $logbook = $this->resolveLogbook($logbook);
        $this->checkOwnership($logbook);

        $logbook->load(['user.department', 'user.divisionRelation', 'reviewer', 'comments.user']);

        return view('kepala-divisi.my-logbooks.show', compact('logbook'));
    }

    /**
     * Menampilkan form edit logbook pribadi Kepala Divisi.
     */
    public function edit($logbook)
    {
        $logbook = $this->resolveLogbook($logbook);
        $this->checkOwnership($logbook);

        if (in_array($logbook->status, ['approved', 'reviewed'], true)) {
            return redirect()
                ->route('kepala_divisi.my_logbooks.index')
                ->with('error', 'Logbook yang sudah disetujui tidak dapat diubah.');
        }

        $user = auth()->user();
        $department = $user->department;
        $division = $user->divisionRelation;

        return view('kepala-divisi.my-logbooks.edit', compact('logbook', 'user', 'department', 'division'));
    }

    /**
     * Memperbarui logbook pribadi Kepala Divisi.
     */
    public function update(Request $request, $logbook)
    {
        $logbook = $this->resolveLogbook($logbook);
        $this->checkOwnership($logbook);

        if (in_array($logbook->status, ['approved', 'reviewed'], true)) {
            return redirect()
                ->route('kepala_divisi.my_logbooks.index')
                ->with('error', 'Logbook yang sudah disetujui tidak dapat diubah.');
        }

        $validated = $request->validate([
            'date' => 'required|date',
            'activity' => 'required|string',
            'obstacle' => 'nullable|string',
            'documentation_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'documentation_images' => 'nullable|array',
            'documentation_images.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'documentation_link' => 'nullable|string|max:2048',
            'documentation_links' => 'nullable|array',
            'documentation_links.*' => 'nullable|string|max:2048',
            'next_plan' => 'nullable|string',
        ]);

        $currentImages = $logbook->documentation_images;
        $allImages = $currentImages;

        // Jika user memilih untuk menghapus foto
        if ($request->boolean('remove_documentation_image')) {
            foreach ($currentImages as $oldImg) {
                if ($oldImg) {
                    Storage::disk('public')->delete($oldImg);
                }
            }
            $allImages = [];
        }

        // Upload foto baru jika ada
        if ($request->hasFile('documentation_images') || $request->hasFile('documentation_image')) {
            foreach ($currentImages as $oldImg) {
                if ($oldImg) {
                    Storage::disk('public')->delete($oldImg);
                }
            }

            $newUploadedImages = [];
            if ($request->hasFile('documentation_images')) {
                $files = $request->file('documentation_images');
                if (! is_array($files)) {
                    $files = [$files];
                }
                foreach ($files as $file) {
                    if ($file && $file->isValid()) {
                        $newUploadedImages[] = $file->store('logbook-documentation', 'public');
                    }
                }
            } elseif ($request->hasFile('documentation_image')) {
                $file = $request->file('documentation_image');
                if ($file && $file->isValid()) {
                    $newUploadedImages[] = $file->store('logbook-documentation', 'public');
                }
            }

            $allImages = $newUploadedImages;
        }

        // Normalisasi dan validasi link dokumentasi
        $normalizedLinks = [];
        $rawLinks = $request->input('documentation_links');
        if (! is_array($rawLinks) && $request->filled('documentation_link')) {
            $rawLinks = [$request->input('documentation_link')];
        }

        if (is_array($rawLinks)) {
            foreach ($rawLinks as $rawLink) {
                $norm = $this->normalizeDocumentationLink($rawLink);
                if ($norm) {
                    if (! filter_var($norm, FILTER_VALIDATE_URL)) {
                        return back()
                            ->withErrors(['documentation_links' => "Format tautan '{$rawLink}' tidak valid."])
                            ->withInput();
                    }
                    $normalizedLinks[] = $norm;
                }
            }
        }

        $logbook->update([
            'date' => $validated['date'],
            'activity' => $validated['activity'],
            'obstacle' => $validated['obstacle'] ?? null,
            'documentation_image' => $allImages[0] ?? null,
            'documentation_images' => count($allImages) > 0 ? $allImages : null,
            'documentation_link' => $normalizedLinks[0] ?? null,
            'documentation_links' => count($normalizedLinks) > 0 ? $normalizedLinks : null,
            'next_plan' => $validated['next_plan'] ?? null,
            'status' => 'pending',
            'reviewed_by' => null,
            'reviewed_at' => null,
            'review_note' => null,
        ]);

        return redirect()
            ->route('kepala_divisi.my_logbooks.index')
            ->with('success', 'Logbook pribadi berhasil diperbarui dan diajukan ulang.');
    }

    /**
     * Menghapus logbook pribadi Kepala Divisi.
     */
    public function destroy($logbook)
    {
        $logbook = $this->resolveLogbook($logbook);
        $this->checkOwnership($logbook);

        if (in_array($logbook->status, ['approved', 'reviewed'], true)) {
            return redirect()
                ->route('kepala_divisi.my_logbooks.index')
                ->with('error', 'Logbook yang sudah disetujui tidak dapat dihapus.');
        }

        // Hapus file foto terkait jika ada
        foreach ($logbook->documentation_images as $img) {
            if ($img) {
                Storage::disk('public')->delete($img);
            }
        }

        $logbook->delete();

        return redirect()
            ->route('kepala_divisi.my_logbooks.index')
            ->with('success', 'Logbook pribadi berhasil dihapus.');
    }

    /**
     * Memastikan logbook adalah milik Kepala Divisi yang sedang login.
     */
    private function checkOwnership(Logbook $logbook): void
    {
        if (! $logbook->exists || (int) $logbook->user_id !== (int) auth()->id()) {
            abort(403, 'Anda tidak memiliki akses ke logbook ini.');
        }
    }

    /**
     * Mengambil instance model Logbook baik dari binding maupun dari ID.
     */
    protected function resolveLogbook($logbook): Logbook
    {
        if ($logbook instanceof Logbook && $logbook->exists) {
            return $logbook;
        }

        $id = $logbook instanceof Logbook ? $logbook->id : $logbook;

        return Logbook::findOrFail($id);
    }

    /**
     * Normalisasi format link dokumentasi.
     */
    private function normalizeDocumentationLink(?string $link): ?string
    {
        if (blank($link)) {
            return null;
        }

        $link = trim($link);

        if (! preg_match('~^(?:f|ht)tps?://~i', $link)) {
            $link = 'https://' . $link;
        }

        return $link;
    }
}


<?php

namespace App\Http\Controllers;

use App\Models\Logbook;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LogbookController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | DAFTAR LOGBOOK
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $logbooks = Logbook::where(
            'user_id',
            auth()->id()
        )
        ->latest('date')
        ->latest('id')
        ->paginate(10);

        return view(
            'employee.logbooks.index',
            compact('logbooks')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | FORM TAMBAH LOGBOOK
    |--------------------------------------------------------------------------
    */

    public function create(Request $request)
    {
        if (in_array(auth()->user()->role, ['web_admin', 'admin', 'pic_gudang', 'pic_lab', 'admin_katalog'])) {
            return redirect()->route('dashboard')->with('error', 'Akun manajerial operasional (PIC / Web Admin) tidak mengisi logbook.');
        }

    $tasks = Task::with([
    'target',
    'subTarget',
    'assignedBy',
    ])
    ->where('assigned_to', auth()->id())
    ->orderByDesc('created_at')
    ->get();

    $task = null;

    if ($request->filled('task_id')) {
        $task = $tasks->firstWhere(
            'id',
            (int) $request->task_id
        );

        if (!$task) {
            abort(404, 'Pekerjaan tidak ditemukan.');
        }
    }

    return view(
        'employee.logbooks.create',
        compact('task', 'tasks')
    );

    }

    /*
    |--------------------------------------------------------------------------
    | SIMPAN LOGBOOK
    |--------------------------------------------------------------------------
    */


    public function store(Request $request)
    {
        if (in_array(auth()->user()->role, ['web_admin', 'admin', 'pic_gudang', 'pic_lab', 'admin_katalog'])) {
            return redirect()->route('dashboard')->with('error', 'Akun manajerial operasional (PIC / Web Admin) tidak mengisi logbook.');
        }

        $isAssigned = $request->input('logbook_type', 'assigned') === 'assigned';

        $validated = $request->validate([
            'logbook_type' => 'required|in:assigned,independent',
            'task_id' => 'required_if:logbook_type,assigned|nullable|integer|exists:tasks,id',
            'date' => 'required|date',
            'activity' => 'required|string',
            'progress' => $isAssigned ? 'required|integer|min:0|max:100' : 'nullable',
            'obstacle' => 'nullable|string',
            'documentation_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'documentation_images' => 'nullable|array',
            'documentation_images.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'documentation_link' => 'nullable|string|max:2048',
            'documentation_links' => 'nullable|array',
            'documentation_links.*' => 'nullable|string|max:2048',
            'next_plan' => 'nullable|string',
        ]);

        $task = null;

        // Validasi tugas hanya jika logbook berasal dari pekerjaan ditugaskan.
        if ($validated['logbook_type'] === 'assigned') {
            $task = Task::where('id', $validated['task_id'])
                ->where('assigned_to', auth()->id())
                ->firstOrFail();
        }

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

        // Normalisasi dan validasi link dokumentasi (bisa lebih dari 1 link)
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

        // Siapkan data logbook.
        $data = [
            'user_id' => auth()->id(),
            'task_id' => $task?->id,
            'logbook_type' => $validated['logbook_type'],
            'date' => $validated['date'],
            'activity' => $validated['activity'],
            'obstacle' => $validated['obstacle'] ?? null,
            'documentation_image' => $uploadedImages[0] ?? null,
            'documentation_images' => count($uploadedImages) > 0 ? $uploadedImages : null,
            'documentation_link' => $normalizedLinks[0] ?? null,
            'documentation_links' => count($normalizedLinks) > 0 ? $normalizedLinks : null,
            'next_plan' => $validated['next_plan'] ?? null,
            'status' => 'pending',
            'progress' => $validated['logbook_type'] === 'assigned' ? (int) $request->input('progress') : null,
        ];

        // Simpan logbook.
        Logbook::create($data);

        // Perbarui progres hanya jika logbook terhubung dengan tugas.
        if ($task) {
            $task->update([
                'reported_progress' => $validated['progress'],
                'status' => $validated['progress'] > 0
                    ? 'in_progress'
                    : 'assigned',
            ]);
        }

        return redirect()
            ->route('employee.logbooks.index')
            ->with('success', 'Logbook berhasil ditambahkan.');
    }

/*
    |--------------------------------------------------------------------------
    | DETAIL LOGBOOK
    |--------------------------------------------------------------------------
    */

        public function show(Logbook $logbook)
        {
            $this->checkOwnership($logbook);

            $logbook->load([
                'task.target',
                'task.assignedBy',
                'reviewer',
            ]);

            return view(
                'employee.logbooks.show',
                compact('logbook')
            );
        }

    /*
    |--------------------------------------------------------------------------
    | FORM EDIT LOGBOOK
    |--------------------------------------------------------------------------
    */

        public function edit(Logbook $logbook)
    {
        $this->checkOwnership($logbook);

        /*
        | Logbook yang sudah direview
        | tidak dapat diedit
        */

        if (in_array($logbook->status, ['approved', 'reviewed'], true)) {

            return redirect()
                ->route(
                    'employee.logbooks.index'
                )
                ->with(
                    'error',
                    'Logbook yang sudah direview tidak dapat diedit.'
                );
        }

        /*
        | Load pekerjaan dan target
        | agar informasi pekerjaan dapat
        | ditampilkan pada halaman edit.
        */

        $logbook->load([
            'task.target'
        ]);

        return view(
            'employee.logbooks.edit',
            compact('logbook')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE LOGBOOK
    |--------------------------------------------------------------------------
    */

        public function update(Request $request, Logbook $logbook)
        {
            $this->checkOwnership($logbook);

            // Logbook yang sudah disetujui tidak boleh diedit.
            if (in_array($logbook->status, ['approved', 'reviewed'], true)) {
                return redirect()
                    ->route('employee.logbooks.index')
                    ->with(
                        'error',
                        'Logbook yang sudah disetujui tidak dapat diubah.'
                    );
            }

            $isAssigned = ($logbook->logbook_type ?? 'assigned') === 'assigned';

            // Validasi data form.
            $validated = $request->validate([
                'date' => 'required|date',
                'activity' => 'required|string',
                'progress' => $isAssigned ? 'required|integer|min:0|max:100' : 'nullable',
                'obstacle' => 'nullable|string',
                'documentation_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
                'documentation_images' => 'nullable|array',
                'documentation_images.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
                'documentation_link' => 'nullable|string|max:2048',
                'documentation_links' => 'nullable|array',
                'documentation_links.*' => 'nullable|string|max:2048',
                'next_plan' => 'nullable|string',
            ]);

            // Ambil task. Nilainya boleh null untuk logbook mandiri.
            $task = $logbook->task;

            // Kelola foto dokumentasi
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

            // Upload foto dokumentasi baru jika ada (bisa lebih dari 1 file gambar)
            if ($request->hasFile('documentation_images') || $request->hasFile('documentation_image')) {
                // Hapus foto lama jika diganti foto baru
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

            // Normalisasi dan validasi link dokumentasi (bisa lebih dari 1 link)
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

            // Siapkan data yang akan diperbarui.
            $data = [
                'date' => $validated['date'],
                'activity' => $validated['activity'],
                'progress' => $isAssigned ? (int) $request->input('progress') : null,
                'obstacle' => $validated['obstacle'] ?? null,
                'documentation_image' => $allImages[0] ?? null,
                'documentation_images' => count($allImages) > 0 ? $allImages : null,
                'documentation_link' => $normalizedLinks[0] ?? null,
                'documentation_links' => count($normalizedLinks) > 0 ? $normalizedLinks : null,
                'next_plan' => $validated['next_plan'] ?? null,

                // Pengajuan ulang kembali menunggu review.
                'status' => 'pending',
                'reviewed_by' => null,
                'reviewed_at' => null,
                'review_note' => null,
            ];

            // Perbarui logbook tanpa mengubah task_id atau logbook_type.
            $logbook->update($data);

            // Hanya hitung ulang progres jika logbook terhubung ke task.
            if ($task) {
                $latestReported = $task->logbooks()
                    ->orderByDesc('date')
                    ->orderByDesc('id')
                    ->first();

                $latestApproved = $task->logbooks()
                    ->where('status', 'approved')
                    ->orderByDesc('date')
                    ->orderByDesc('id')
                    ->first();

                $task->update([
                    'reported_progress' => $latestReported?->progress ?? 0,
                    'verified_progress' => $latestApproved?->progress ?? 0,
                ]);
            }

            return redirect()
                ->route('employee.logbooks.show', $logbook)
                ->with(
                    'success',
                    'Logbook berhasil diperbarui dan diajukan kembali untuk review.'
                );
        }

    /*
    |--------------------------------------------------------------------------
    | HAPUS LOGBOOK
    |--------------------------------------------------------------------------
    */

        public function destroy(Logbook $logbook)
    {
        $this->checkOwnership($logbook);

        if (in_array($logbook->status, ['approved', 'reviewed'], true)) {
            return redirect()
                ->route('employee.logbooks.index')
                ->with(
                    'error',
                    'Logbook yang sudah disetujui tidak dapat dihapus.'
                );
        }

        // Simpan task sebelum logbook dihapus.
        // Logbook mandiri boleh memiliki task bernilai null.
        $task = $logbook->task;

        // Hapus seluruh file dokumentasi foto jika ada.
        foreach ($logbook->documentation_images as $imagePath) {
            if ($imagePath) {
                Storage::disk('public')->delete($imagePath);
            }
        }

        // Hapus logbook.
        $logbook->delete();

        // Perbarui progres hanya jika logbook terkait dengan pekerjaan.
        if ($task) {
            $latestReported = $task->logbooks()
                ->orderByDesc('date')
                ->orderByDesc('id')
                ->first();

            $latestApproved = $task->logbooks()
                ->where('status', 'approved')
                ->orderByDesc('date')
                ->orderByDesc('id')
                ->first();

            $task->update([
                'reported_progress' => $latestReported?->progress ?? 0,
                'verified_progress' => $latestApproved?->progress ?? 0,
            ]);
        }

        return redirect()
            ->route('employee.logbooks.index')
            ->with(
                'success',
                $task
                    ? 'Logbook berhasil dihapus dan progres pekerjaan diperbarui.'
                    : 'Logbook mandiri berhasil dihapus.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | CEK KEPEMILIKAN LOGBOOK
    |--------------------------------------------------------------------------
    */

    private function checkOwnership(Logbook $logbook)
    {
        if ($logbook->user_id !== auth()->id()) {

            abort(403);

        }
    }

    /**
     * Memproses dan menormalisasi array link dokumentasi.
     * Mengembalikan array URL valid dan unik.
     */
    private function processDocumentationLinks(Request $request): array
    {
        $rawLinks = [];

        if ($request->has('documentation_links')) {
            $input = $request->input('documentation_links');
            if (is_array($input)) {
                $rawLinks = $input;
            } elseif (is_string($input)) {
                $rawLinks = [$input];
            }
        } elseif ($request->filled('documentation_link')) {
            $rawLinks = [$request->input('documentation_link')];
        }

        $validLinks = [];
        foreach ($rawLinks as $link) {
            $normalized = $this->normalizeDocumentationLink($link);
            if ($normalized && filter_var($normalized, FILTER_VALIDATE_URL)) {
                $validLinks[] = $normalized;
            }
        }

        return array_values(array_unique($validLinks));
    }

    /**
     * Normalisasi format link dokumentasi agar selalu memiliki protokol URL yang valid.
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
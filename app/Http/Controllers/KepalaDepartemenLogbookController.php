<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Division;
use App\Models\Logbook;
use App\Models\User;
use Illuminate\Http\Request;

class KepalaDepartemenLogbookController extends Controller
{
    /**
     * Menampilkan daftar logbook Kepala Divisi yang berada di bawah naungan
     * departemen Kepala Departemen yang sedang login.
     */
    public function index()
    {
        $kadep = auth()->user();
        $departmentId = $kadep->department_id;

        // Ambil data departemen Kepala Departemen
        $department = Department::with('divisions')->find($departmentId);

        // Divisi-divisi yang ada di bawah departemen ini
        $divisions = $department ? $department->divisions : collect();
        $divisionIds = $divisions->pluck('id')->toArray();

        // Query logbook Kepala Divisi dalam departemen ini
        $baseQuery = Logbook::with([
            'user.department',
            'user.divisionRelation',
            'reviewer',
        ])
        ->whereHas('user', function ($q) use ($departmentId, $divisionIds) {
            $q->where('role', 'kepala_divisi')
              ->where(function ($sub) use ($departmentId, $divisionIds) {
                  $sub->where('department_id', $departmentId);
                  if (!empty($divisionIds)) {
                      $sub->orWhereIn('division_id', $divisionIds);
                  }
              });
        });

        // Summary metrics (hanya total logbook, menunggu review, dan perlu revisi)
        $totalLogbooks = (clone $baseQuery)->count();
        $pendingCount = (clone $baseQuery)->where('status', 'pending')->count();
        $revisionCount = (clone $baseQuery)->where('status', 'revision')->count();

        $logbooks = (clone $baseQuery)
            ->latest('date')
            ->latest('id')
            ->paginate(10);

        return view('kepala-departemen.logbooks.index', compact(
            'logbooks',
            'department',
            'totalLogbooks',
            'pendingCount',
            'revisionCount'
        ));
    }

    /**
     * Menampilkan detail logbook Kepala Divisi untuk direview.
     */
    public function show(Logbook $logbook)
    {
        $kadep = auth()->user();
        $this->authorizeLogbook($logbook, $kadep);

        $logbook->load([
            'user.department',
            'user.divisionRelation',
            'reviewer',
            'comments.user',
        ]);

        return view('kepala-departemen.logbooks.show', compact('logbook'));
    }

    /**
     * Menyetujui atau meminta revisi atas logbook Kepala Divisi.
     */
    public function review(Request $request, Logbook $logbook)
    {
        $kadep = auth()->user();
        $this->authorizeLogbook($logbook, $kadep);

        $validated = $request->validate([
            'action' => ['required', 'in:approve,revision'],
            'review_note' => ['nullable', 'string'],
        ]);

        if ($validated['action'] === 'revision' && empty(trim($validated['review_note'] ?? ''))) {
            return back()
                ->withErrors(['review_note' => 'Catatan revisi wajib diisi jika meminta revisi logbook.'])
                ->withInput();
        }

        if ($validated['action'] === 'approve') {
            $logbook->update([
                'status' => 'approved',
                'reviewed_by' => $kadep->id,
                'reviewed_at' => now(),
                'review_note' => $validated['review_note'] ?? null,
            ]);

            return redirect()
                ->route('kepala_departemen.logbooks.show', $logbook)
                ->with('success', 'Logbook Kepala Divisi berhasil disetujui.');
        }

        // Action: revision
        $logbook->update([
            'status' => 'revision',
            'reviewed_by' => $kadep->id,
            'reviewed_at' => now(),
            'review_note' => $validated['review_note'],
        ]);

        return redirect()
            ->route('kepala_departemen.logbooks.show', $logbook)
            ->with('success', 'Logbook Kepala Divisi telah dikembalikan dengan catatan revisi.');
    }

    /**
     * Memastikan logbook Kepala Divisi berada dalam departemen Kepala Departemen yang sedang login.
     */
    private function authorizeLogbook(Logbook $logbook, User $kadep): void
    {
        $logbook->loadMissing('user.divisionRelation', 'user.department');
        $user = $logbook->user;

        $departmentId = $kadep->department_id;
        $divisionDepartmentId = $user?->divisionRelation?->department_id;

        $isKadivInDept = $user
            && $user->role === 'kepala_divisi'
            && ($user->department_id == $departmentId || $divisionDepartmentId == $departmentId);

        abort_unless(
            $isKadivInDept,
            403,
            'Anda tidak memiliki akses untuk mereview logbook Kepala Divisi di luar departemen Anda.'
        );
    }
}


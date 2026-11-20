<?php

namespace App\Http\Controllers;

use App\Models\Logbook;
use App\Models\User;
use Illuminate\Http\Request;

class SupervisorLogbookController extends Controller
{
    /**
     * Daftar karyawan beserta statistik logbook mereka.
     */
    public function index(Request $request)
    {
        $query = User::where('role', 'employee')
            ->withCount([
                'logbooks as total_logbooks_count',
                'logbooks as pending_logbooks_count'  => fn ($q) => $q->where('status', 'pending'),
                'logbooks as approved_logbooks_count' => fn ($q) => $q->where('status', 'approved'),
                'logbooks as rejected_logbooks_count' => fn ($q) => $q->where('status', 'rejected'),
            ]);

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('division')) {
            $query->where('division', $request->division);
        }

        $employees = $query->orderByDesc('pending_logbooks_count')->orderBy('name')->paginate(10)->withQueryString();

        // Ambil daftar divisi untuk filter
        $divisions = User::where('role', 'employee')->whereNotNull('division')->pluck('division')->unique()->sort()->values();

        return view('supervisor.logbooks.index', compact('employees', 'divisions'));
    }

    /**
     * Detail semua logbook dari satu karyawan.
     */
    public function employeeDetail(User $user)
    {
        abort_unless($user->role === 'employee', 404);

        $logbooks = $user->logbooks()
            ->latest('date')
            ->latest('id')
            ->get();

        $total    = $logbooks->count();
        $approved = $logbooks->where('status', 'approved')->count();
        $rejected = $logbooks->where('status', 'rejected')->count();
        $pending  = $logbooks->where('status', 'pending')->count();
        
        $latestApproved = $logbooks->where('status', 'approved')->whereNotNull('progress')->sortByDesc('date')->sortByDesc('id')->first();
        $progress = $latestApproved ? (int) $latestApproved->progress : 0;

        return view('supervisor.logbooks.employee-detail', compact(
            'user', 'logbooks', 'total', 'approved', 'rejected', 'pending', 'progress'
        ));
    }

    /**
     * Approve / Reject satu logbook.
     */
    public function review(Request $request, Logbook $logbook)
    {
        $validated = $request->validate([
            'decision'    => 'required|in:approved,rejected',
            'review_note' => 'nullable|string|max:2000',
        ]);

        $logbook->update([
            'status'      => $validated['decision'],
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
            'review_note' => $validated['review_note'] ?? null,
        ]);

        $action = $validated['decision'] === 'approved' ? 'diapprove' : 'direject';
        return back()->with('success', "Logbook berhasil {$action}.");
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\SubTarget;
use App\Models\Target;
use Illuminate\Http\Request;

class SubTargetController extends Controller
{
    /**
     * Menampilkan daftar Sub Target dari sebuah Target.
     */
    public function index(Target $target)
    {
        $this->authorizeTarget($target);

        $target->load([
            'project',
            'department',
            'division',
            'subTargets' => function ($query) {
                $query->latest('id');
            },
        ]);

        $allocatedPercentage = $target->subTargets->sum('percentage');

        $remainingPercentage = max(
            0,
            100 - $allocatedPercentage
        );

        return view(
            'admin.targets.sub-targets.index',
            compact(
                'target',
                'allocatedPercentage',
                'remainingPercentage'
            )
        );
    }

    /**
     * Menampilkan form tambah Sub Target.
     */
    public function create(Target $target)
    {
        $this->authorizeTarget($target);

        $target->load([
            'project',
            'department',
            'division',
        ]);

        $allocatedPercentage = $target
            ->subTargets()
            ->sum('percentage');

        $remainingPercentage = max(
            0,
            100 - $allocatedPercentage
        );

        return view(
            'admin.targets.sub-targets.create',
            compact(
                'target',
                'allocatedPercentage',
                'remainingPercentage'
            )
        );
    }

    /**
     * Menyimpan Sub Target baru.
     */
    public function store(Request $request, Target $target)
    {
        $this->authorizeTarget($target);

        $allocatedPercentage = $target
            ->subTargets()
            ->sum('percentage');

        $remainingPercentage = 100 - $allocatedPercentage;

        if ($remainingPercentage <= 0) {
            return back()
                ->withInput()
                ->withErrors([
                    'percentage' =>
                        'Total bobot Sub Target sudah mencapai 100%. Tidak dapat menambahkan Sub Target lagi.',
                ]);
        }

        $validated = $request->validate([
            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'percentage' => [
                'required',
                'integer',
                'min:1',
                'max:' . $remainingPercentage,
            ],

            'period_start' => [
                'nullable',
                'date',
                function ($attribute, $value, $fail) use ($target) {

                    if (!$value) {
                        return;
                    }

                    if (
                        $target->period_start &&
                        $value < $target->period_start->format('Y-m-d')
                    ) {
                        $fail(
                            'Tanggal mulai Sub Target tidak boleh sebelum tanggal mulai Target.'
                        );
                    }

                    if (
                        $target->period_end &&
                        $value > $target->period_end->format('Y-m-d')
                    ) {
                        $fail(
                            'Tanggal mulai Sub Target tidak boleh setelah tanggal selesai Target.'
                        );
                    }
                },
            ],

            'period_end' => [
                'nullable',
                'date',
                'after_or_equal:period_start',
                function ($attribute, $value, $fail) use ($target) {

                    if (!$value) {
                        return;
                    }

                    if (
                        $target->period_start &&
                        $value < $target->period_start->format('Y-m-d')
                    ) {
                        $fail(
                            'Tanggal selesai Sub Target tidak boleh sebelum tanggal mulai  Target.'
                        );
                    }

                    if (
                        $target->period_end &&
                        $value > $target->period_end->format('Y-m-d')
                    ) {
                        $fail(
                            'Tanggal selesai Sub Target tidak boleh setelah tanggal selesai Target.'
                        );
                    }
                },
            ],
        ], [
            'title.required' =>
                'Judul Sub Target wajib diisi.',

            'percentage.required' =>
                'Bobot Sub Target wajib diisi.',

            'percentage.min' =>
                'Bobot Sub Target minimal 1%.',

            'percentage.max' =>
                'Bobot Sub Target tidak boleh melebihi sisa bobot '
                . $remainingPercentage
                . '%.',

            'period_start.date' =>
                'Tanggal mulai Sub Target tidak valid.',

            'period_end.date' =>
                'Tanggal selesai Sub Target tidak valid.',

            'period_end.after_or_equal' =>
                'Tanggal selesai harus sama dengan atau setelah tanggal mulai.',
        ]);

        $target->subTargets()->create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'percentage' => $validated['percentage'],
            'period_start' => $validated['period_start'] ?? null,
            'period_end' => $validated['period_end'] ?? null,
            'progress' => 0,
        ]);

        return redirect()
            ->route(
                'admin.targets.sub-targets.index',
                $target
            )
            ->with(
                'success',
                'Sub Target berhasil ditambahkan.'
            );
    }

    /**
     * Menampilkan form edit Sub Target.
     */
    public function edit(
        Target $target,
        SubTarget $subTarget
    ) {
        $this->authorizeTarget($target);

        if ($subTarget->target_id !== $target->id) {
            abort(404);
        }

        $target->load([
            'project',
            'department',
            'division',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Hitung bobot Sub Target lainnya
        |--------------------------------------------------------------------------
        */

        $allocatedPercentage = $target
            ->subTargets()
            ->where('id', '!=', $subTarget->id)
            ->sum('percentage');

        $remainingPercentage = max(
            0,
            100 - $allocatedPercentage
        );

        return view(
            'admin.targets.sub-targets.edit',
            compact(
                'target',
                'subTarget',
                'allocatedPercentage',
                'remainingPercentage'
            )
        );
    }

    /**
     * Memperbarui Sub Target.
     */
    public function update(
        Request $request,
        Target $target,
        SubTarget $subTarget
    ) {
        $this->authorizeTarget($target);

        if ($subTarget->target_id !== $target->id) {
            abort(404);
        }

        /*
        |--------------------------------------------------------------------------
        | Hitung bobot Sub Target lainnya
        |--------------------------------------------------------------------------
        */

        $allocatedPercentage = $target
            ->subTargets()
            ->where('id', '!=', $subTarget->id)
            ->sum('percentage');

        $remainingPercentage = 100 - $allocatedPercentage;

        if ($remainingPercentage < 1) {
            return back()
                ->withInput()
                ->withErrors([
                    'percentage' =>
                        'Tidak tersedia bobot untuk Sub Target ini.',
                ]);
        }

        $validated = $request->validate([
            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'percentage' => [
                'required',
                'integer',
                'min:1',
                'max:' . $remainingPercentage,
            ],

            'period_start' => [
                'nullable',
                'date',
                function ($attribute, $value, $fail) use ($target) {

                    if (!$value) {
                        return;
                    }

                    if (
                        $target->period_start &&
                        $value < $target->period_start->format('Y-m-d')
                    ) {
                        $fail(
                            'Tanggal mulai Sub Target tidak boleh sebelum tanggal mulai Target.'
                        );
                    }

                    if (
                        $target->period_end &&
                        $value > $target->period_end->format('Y-m-d')
                    ) {
                        $fail(
                            'Tanggal mulai Sub Target tidak boleh setelah tanggal selesai Target.'
                        );
                    }
                },
            ],

            'period_end' => [
                'nullable',
                'date',
                'after_or_equal:period_start',
                function ($attribute, $value, $fail) use ($target) {

                    if (!$value) {
                        return;
                    }

                    if (
                        $target->period_start &&
                        $value < $target->period_start->format('Y-m-d')
                    ) {
                        $fail(
                            'Tanggal selesai Sub Target tidak boleh sebelum tanggal mulai Target.'
                        );
                    }

                    if (
                        $target->period_end &&
                        $value > $target->period_end->format('Y-m-d')
                    ) {
                        $fail(
                            'Tanggal selesai Sub Target tidak boleh setelah tanggal selesai Target.'
                        );
                    }
                },
            ],
        ], [
            'title.required' =>
                'Judul Sub Target wajib diisi.',

            'percentage.required' =>
                'Bobot Sub Target wajib diisi.',

            'percentage.min' =>
                'Bobot Sub Target minimal 1%.',

            'percentage.max' =>
                'Bobot Sub Target tidak boleh melebihi sisa bobot '
                . $remainingPercentage
                . '%.',

            'period_start.date' =>
                'Tanggal mulai Sub Target tidak valid.',

            'period_end.date' =>
                'Tanggal selesai Sub Target tidak valid.',

            'period_end.after_or_equal' =>
                'Tanggal selesai harus sama dengan atau setelah tanggal mulai.',
        ]);

        $subTarget->update([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'percentage' => $validated['percentage'],
            'period_start' => $validated['period_start'] ?? null,
            'period_end' => $validated['period_end'] ?? null,
        ]);

        return redirect()
            ->route(
                'admin.targets.sub-targets.index',
                $target
            )
            ->with(
                'success',
                'Sub Target berhasil diperbarui.'
            );
    }

    /**
     * Menghapus Sub Target.
     */
    public function destroy(
        Target $target,
        SubTarget $subTarget
    ) {
        $this->authorizeTarget($target);

        if ($subTarget->target_id !== $target->id) {
            abort(404);
        }

        $subTarget->delete();

        return redirect()
            ->route(
                'admin.targets.sub-targets.index',
                $target
            )
            ->with(
                'success',
                'Sub Target berhasil dihapus.'
            );
    }

    /**
     * Mengecek hak akses user terhadap Target.
     */
    private function authorizeTarget(Target $target)
    {
        $user = auth()->user();

        /*
        |--------------------------------------------------------------------------
        | Admin
        |--------------------------------------------------------------------------
        */

        if ($user->role === 'admin') {
            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Kepala Departemen
        |--------------------------------------------------------------------------
        */

        if (
            $user->role === 'kepala_departemen' &&
            $target->department_id === $user->department_id
        ) {
            return;
        }

        abort(
            403,
            'Anda tidak memiliki akses ke Target ini.'
        );
    }
}

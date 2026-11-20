<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Division;
use App\Models\SubTarget;
use App\Models\Target;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Menampilkan daftar task milik Kepala Divisi.
     */
    public function index()
    {
        $user = auth()->user();

        $tasks = Task::with([
            'subTarget.target',
            'assignedTo',
            'division',
        ])
            ->where(
                'department_id',
                $user->department_id
            )
            ->where(
                'division_id',
                $user->division_id
            )
            ->latest()
            ->get();

        return view(
            'kepala-divisi.tasks.index',
            compact('tasks')
        );
    }

    /**
     * Menampilkan form pembuatan task.
     */
    public function create()
    {
        $user = auth()->user();

        /*
         * Kepala Divisi hanya dapat membuat
         * task untuk divisinya sendiri.
         */
        if ($user->role !== 'kepala_divisi') {
            abort(
                403,
                'Hanya Kepala Divisi yang dapat memberikan pekerjaan.'
            );
        }

        $department = Department::findOrFail(
            $user->department_id
        );

        $division = Division::where(
            'id',
            $user->division_id
        )
            ->where(
                'department_id',
                $user->department_id
            )
            ->firstOrFail();

        /*
         * Hanya Target aktif
         * dari department DAN division Kepala Divisi.
         */
        $targets = Target::with([
            'project',
            'subTargets',
        ])
            ->where(
                'department_id',
                $user->department_id
            )
            ->where(
                'division_id',
                $user->division_id
            )
            ->where(
                'status',
                'active'
            )
            ->orderBy('title')
            ->get();

            
        /*
         * Hanya SubTarget dari Target aktif
         * pada department dan division yang sama.
         */
        $subTargets = SubTarget::whereHas(
            'target',
            function ($query) use ($user) {

                $query
                    ->where(
                        'department_id',
                        $user->department_id
                    )
                    ->where(
                        'division_id',
                        $user->division_id
                    )
                    ->where(
                        'status',
                        'active'
                    );
            }
        )
            ->with('target')
            ->orderBy('title')
            ->get();

        /*
         * Employee hanya dari division
         * Kepala Divisi yang sedang login.
         */
        $employees = User::where(
            'department_id',
            $user->department_id
        )
            ->where(
                'division_id',
                $user->division_id
            )
            ->where(
                'role',
                'employee'
            )
            ->orderBy('name')
            ->get();

        return view(
            'kepala-divisi.tasks.create',
            compact(
                'department',
                'division',
                'targets',
                'subTargets',
                'employees'
            )
        );
    }

    /**
     * Menyimpan task baru.
     */
    public function store(Request $request)
    {
        $user = auth()->user();

        /*
         * Hanya Kepala Divisi yang dapat
         * memberikan pekerjaan.
         */
        if ($user->role !== 'kepala_divisi') {
            abort(
                403,
                'Anda tidak memiliki akses untuk memberikan pekerjaan.'
            );
        }

        $validated = $request->validate([
            'sub_target_id' => [
                'required',
                'exists:sub_targets,id',
            ],

            'work_type' => [
                'required',
                'in:project,daily,other',
            ],

            'other_work_type' => [
                'nullable',
                'string',
                'max:255',
                'required_if:work_type,other',
            ],

            'assigned_to' => [
                'required',
                'exists:users,id',
            ],

            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'description' => [
                'nullable',
                'string',
            ],
        ], [
            'sub_target_id.required' =>
                'Sub Target wajib dipilih.',

            'work_type.required' =>
                'Jenis pekerjaan wajib dipilih.',

            'other_work_type.required_if' =>
                'Jenis pekerjaan lainnya wajib diisi.',

            'assigned_to.required' =>
                'Karyawan wajib dipilih.',

            'title.required' =>
                'Judul pekerjaan wajib diisi.',
        ]);

        /*
         * Pastikan SubTarget:
         * - benar-benar ada
         * - berasal dari department yang sama
         * - berasal dari division yang sama
         * - Target masih aktif
         */
        $subTarget = SubTarget::where(
            'id',
            $validated['sub_target_id']
        )
            ->whereHas(
                'target',
                function ($query) use ($user) {

                    $query
                        ->where(
                            'department_id',
                            $user->department_id
                        )
                        ->where(
                            'division_id',
                            $user->division_id
                        )
                        ->where(
                            'status',
                            'active'
                        );
                }
            )
            ->with('target')
            ->firstOrFail();

        /*
         * Pastikan employee:
         * - berada di department yang sama
         * - berada di division yang sama
         * - memiliki role employee
         */
        $employee = User::where(
            'id',
            $validated['assigned_to']
        )
            ->where(
                'department_id',
                $user->department_id
            )
            ->where(
                'division_id',
                $user->division_id
            )
            ->where(
                'role',
                'employee'
            )
            ->firstOrFail();

        /*
         * Department dan division TIDAK diambil
         * dari request.
         *
         * Nilainya ditentukan berdasarkan
         * Kepala Divisi yang sedang login.
         */
        Task::create([
            'target_id' => $subTarget->target_id,
            'sub_target_id' => $subTarget->id,

            'work_type' => $validated['work_type'],

            'other_work_type' =>
                $validated['other_work_type'] ?? null,

            'assigned_by' => $user->id,

            'assigned_to' => $employee->id,

            'department_id' =>
                $user->department_id,

            'division_id' =>
                $user->division_id,

            'title' =>
                $validated['title'],

            'description' =>
                $validated['description'] ?? null,

            'reported_progress' => 0,

            'verified_progress' => 0,

            'status' => 'assigned',
        ]);

        return redirect()
            ->route('kepala_divisi.tasks.index')
            ->with(
                'success',
                'Pekerjaan berhasil diberikan kepada karyawan.'
            );
    }

    /**
     * Menampilkan detail task.
     */
    public function show(Task $task)
    {
        $user = auth()->user();

        $this->authorizeTask(
            $task,
            $user
        );

        $task->load([
            'subTarget.target',
            'assignedTo',
            'assignedBy',
            'department',
            'division',
            'logbooks',
        ]);

        return view(
            'kepala-divisi.tasks.show',
            compact('task')
        );
    }

    /**
     * Menampilkan form edit task.
     */
    public function edit(Task $task)
    {
        $user = auth()->user();

        $this->authorizeTask(
            $task,
            $user
        );

        $task->load([
            'subTarget.target',
            'assignedTo',
        ]);

        /*
         * SubTarget hanya dari Target aktif
         * milik department dan division Kepala Divisi.
         */
        $subTargets = SubTarget::whereHas(
            'target',
            function ($query) use ($user) {

                $query
                    ->where(
                        'department_id',
                        $user->department_id
                    )
                    ->where(
                        'division_id',
                        $user->division_id
                    )
                    ->where(
                        'status',
                        'active'
                    );
            }
        )
            ->with('target')
            ->orderBy('title')
            ->get();

        /*
         * Employee hanya dari division
         * Kepala Divisi.
         */
        $employees = User::where(
            'department_id',
            $user->department_id
        )
            ->where(
                'division_id',
                $user->division_id
            )
            ->where(
                'role',
                'employee'
            )
            ->orderBy('name')
            ->get();

        $division = Division::where(
            'id',
            $user->division_id
        )
            ->where(
                'department_id',
                $user->department_id
            )
            ->firstOrFail();

        return view(
            'kepala-divisi.tasks.edit',
            compact(
                'task',
                'subTargets',
                'employees',
                'division'
            )
        );
    }

    /**
     * Memperbarui task.
     */
    public function update(
        Request $request,
        Task $task
    ) {
        $user = auth()->user();

        $this->authorizeTask(
            $task,
            $user
        );

        $validated = $request->validate([
            'sub_target_id' => [
                'required',
                'exists:sub_targets,id',
            ],

            'work_type' => [
                'required',
                'in:project,daily,other',
            ],

            'other_work_type' => [
                'nullable',
                'string',
                'max:255',
                'required_if:work_type,other',
            ],

            'assigned_to' => [
                'required',
                'exists:users,id',
            ],

            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'description' => [
                'nullable',
                'string',
            ],
        ], [
            'sub_target_id.required' =>
                'Sub Target wajib dipilih.',

            'work_type.required' =>
                'Jenis pekerjaan wajib dipilih.',

            'other_work_type.required_if' =>
                'Jenis pekerjaan lainnya wajib diisi.',

            'assigned_to.required' =>
                'Karyawan wajib dipilih.',

            'title.required' =>
                'Judul pekerjaan wajib diisi.',
        ]);

        /*
         * Pastikan SubTarget:
         * - berasal dari department yang sama
         * - berasal dari division yang sama
         * - Target masih aktif
         */
        $subTarget = SubTarget::where(
            'id',
            $validated['sub_target_id']
        )
            ->whereHas(
                'target',
                function ($query) use ($user) {

                    $query
                        ->where(
                            'department_id',
                            $user->department_id
                        )
                        ->where(
                            'division_id',
                            $user->division_id
                        )
                        ->where(
                            'status',
                            'active'
                        );
                }
            )
            ->with('target')
            ->firstOrFail();

        /*
         * Pastikan employee masih berada
         * di division Kepala Divisi.
         */
        $employee = User::where(
            'id',
            $validated['assigned_to']
        )
            ->where(
                'department_id',
                $user->department_id
            )
            ->where(
                'division_id',
                $user->division_id
            )
            ->where(
                'role',
                'employee'
            )
            ->firstOrFail();

        $task->update([
            'sub_target_id' =>
                $subTarget->id,

            'work_type' =>
                $validated['work_type'],

            'other_work_type' =>
                $validated['other_work_type'] ?? null,

            'assigned_to' =>
                $employee->id,

            'title' =>
                $validated['title'],

            'description' =>
                $validated['description'] ?? null,
        ]);

        return redirect()
            ->route('kepala_divisi.tasks.index')
            ->with(
                'success',
                'Pekerjaan berhasil diperbarui.'
            );
    }

    /**
     * Menghapus task.
     */
    public function destroy(Task $task)
    {
        $user = auth()->user();

        $this->authorizeTask(
            $task,
            $user
        );

        $task->delete();

        return redirect()
            ->route('kepala_divisi.tasks.index')
            ->with(
                'success',
                'Pekerjaan berhasil dihapus.'
            );
    }

    /**
     * Memastikan task memang berada
     * dalam scope Kepala Divisi.
     */
    private function authorizeTask(
        Task $task,
        User $user
    ): void {
        abort_unless(
            $user->role === 'kepala_divisi'
            &&
            (int) $task->department_id ===
                (int) $user->department_id
            &&
            (int) $task->division_id ===
                (int) $user->division_id,
            403,
            'Anda tidak memiliki akses ke pekerjaan ini.'
        );
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Division;
use App\Models\Project;
use App\Models\Target;
use Illuminate\Http\Request;

class TargetController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $user = auth()->user();

        $query = Target::with([
            'project',
            'department',
            'division',
            'subTargets',
        ]);

        /*
        |--------------------------------------------------------------------------
        | FILTER BERDASARKAN ROLE
        |--------------------------------------------------------------------------
        */

        if ($user->role === 'kepala_departemen') {

            $query->where(
                'department_id',
                $user->department_id
            );

        } elseif ($user->role === 'admin') {

            // Admin dapat melihat semua target.

        } elseif ($user->role === 'kepala_divisi') {

            $query->where(
                'division_id',
                $user->division_id
            );

        } else {

            abort(
                403,
                'Anda tidak memiliki akses untuk melihat Target.'
            );
        }

        $targets = $query
            ->latest('id')
            ->paginate(10);

        return view(
            'kepala-departemen.targets.index',
            compact('targets')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        $user = auth()->user();

        /*
        |--------------------------------------------------------------------------
        | ROLE CHECK
        |--------------------------------------------------------------------------
        */

        if ($user->role === 'kepala_divisi') {

            abort(
                403,
                'Kepala Divisi tidak memiliki akses untuk membuat Target.'
            );
        }

        if (!in_array($user->role, ['admin', 'kepala_departemen'])) {

            abort(
                403,
                'Anda tidak memiliki akses.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | KEPALA DEPARTEMEN
        |--------------------------------------------------------------------------
        */

        if ($user->role === 'kepala_departemen') {

            /*
            |--------------------------------------------------------------------------
            | Department
            |--------------------------------------------------------------------------
            |
            | Kepala Departemen hanya melihat department miliknya.
            |
            */

            $departments = Department::where(
                'id',
                $user->department_id
            )->get();


            /*
            |--------------------------------------------------------------------------
            | Project
            |--------------------------------------------------------------------------
            |
            | Hanya Project yang memiliki minimal satu Division
            | dari department Kepala Departemen.
            |
            */

            $projects = Project::whereHas(
                'divisions',
                function ($query) use ($user) {

                    $query->where(
                        'department_id',
                        $user->department_id
                    );

                }
            )
                ->with('divisions')
                ->orderBy('name')
                ->get();


            /*
            |--------------------------------------------------------------------------
            | Division
            |--------------------------------------------------------------------------
            */

            $divisions = Division::where(
                'department_id',
                $user->department_id
            )
                ->orderBy('name')
                ->get();

        }


        /*
        |--------------------------------------------------------------------------
        | ADMIN
        |--------------------------------------------------------------------------
        */

        elseif ($user->role === 'admin') {

            /*
            |--------------------------------------------------------------------------
            | Semua Department
            |--------------------------------------------------------------------------
            */

            $departments = Department::orderBy(
                'name'
            )->get();


            /*
            |--------------------------------------------------------------------------
            | Semua Project
            |--------------------------------------------------------------------------
            */

            $projects = Project::with(
                'divisions'
            )
                ->orderBy('name')
                ->get();


            /*
            |--------------------------------------------------------------------------
            | Semua Division
            |--------------------------------------------------------------------------
            */

            $divisions = Division::orderBy(
                'name'
            )->get();

        }


        /*
        |--------------------------------------------------------------------------
        | DATA PROJECT UNTUK JAVASCRIPT
        |--------------------------------------------------------------------------
        */

        $projectData = $projects
            ->map(function ($project) {

                return [
                    'id' => $project->id,

                    'name' => $project->name,

                    'description' => $project->description,

                    'start_date' => $project->start_date
                        ? $project->start_date->format('Y-m-d')
                        : null,

                    'end_date' => $project->end_date
                        ? $project->end_date->format('Y-m-d')
                        : null,

                    'divisions' => $project->divisions
                        ->map(function ($division) {

                            return [
                                'id' => $division->id,
                                'name' => $division->name,
                            ];

                        })
                        ->values()
                        ->toArray(),
                ];

            })
            ->values()
            ->toArray();


        return view(
            'kepala-departemen.targets.create',
            compact(
                'departments',
                'projects',
                'divisions',
                'projectData'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $user = auth()->user();


        /*
        |--------------------------------------------------------------------------
        | ROLE CHECK
        |--------------------------------------------------------------------------
        */

        if ($user->role === 'kepala_divisi') {

            abort(
                403,
                'Kepala Divisi tidak memiliki akses untuk membuat Target.'
            );
        }

        if (!in_array(
            $user->role,
            ['admin', 'kepala_departemen']
        )) {

            abort(
                403,
                'Anda tidak memiliki akses.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | VALIDASI FORM
        |--------------------------------------------------------------------------
        |
        | department_id tidak divalidasi dari request karena Department
        | ditentukan berdasarkan akun / Division.
        |
        | target_progress juga tidak diminta dari form karena Target
        | baru otomatis dimulai dari 0%.
        |
        */

        $validated = $request->validate([

            'project_id' =>
                'nullable|exists:projects,id',

            'division_id' =>
                'required|exists:divisions,id',

            'title' =>
                'required|string|max:255',

            'description' =>
                'nullable|string',

            'period_start' =>
                'nullable',

            'period_end' =>
                'nullable',

            'status' =>
                'required|in:active,pending,completed,inactive',

        ]);


        /*
        |--------------------------------------------------------------------------
        | AMBIL DIVISION
        |--------------------------------------------------------------------------
        */

        $division = Division::findOrFail(
            $validated['division_id']
        );


        /*
        |--------------------------------------------------------------------------
        | TENTUKAN DEPARTMENT
        |--------------------------------------------------------------------------
        */

        if ($user->role === 'kepala_departemen') {

            /*
            |--------------------------------------------------------------------------
            | Kepala Departemen
            |--------------------------------------------------------------------------
            |
            | Department otomatis mengikuti akun login.
            |
            */

            $departmentId = $user->department_id;


            /*
            |--------------------------------------------------------------------------
            | Pastikan Division berasal dari Department tersebut
            |--------------------------------------------------------------------------
            */

            if (
                $division->department_id !=
                $user->department_id
            ) {

                abort(
                    403,
                    'Divisi tersebut bukan berada di bawah Department Anda.'
                );
            }

        } else {

            /*
            |--------------------------------------------------------------------------
            | Admin
            |--------------------------------------------------------------------------
            |
            | Admin dapat mengelola banyak Department.
            | Department ditentukan dari Division yang dipilih.
            |
            */

            $departmentId =
                $division->department_id;
        }


        /*
        |--------------------------------------------------------------------------
        | VALIDASI DEPARTMENT
        |--------------------------------------------------------------------------
        */

        $department = Department::findOrFail(
            $departmentId
        );


        /*
        |--------------------------------------------------------------------------
        | VALIDASI PROJECT
        |--------------------------------------------------------------------------
        |
        | Project bersifat opsional.
        |
        | Jika Project dipilih:
        |
        | Project
        |    |
        |    ├── Division A
        |    │      └── Target A
        |    |
        |    └── Division B
        |           └── Target B
        |
        | Maka Division yang dipilih harus memang terlibat
        | dalam Project tersebut.
        |
        */

        if (!empty($validated['project_id'])) {

            $project = Project::with('divisions')->findOrFail(
                $validated['project_id']
            );

            if ($project->start_date) {
                $validated['period_start'] = $project->start_date
                    ->format('Y-m-d');
            }

            if ($project->end_date) {
                $validated['period_end'] = $project->end_date
                    ->format('Y-m-d');
            }

            // Pastikan divisi terlibat dalam Project.
            $divisionBelongsToProject = $project->divisions()
                ->whereKey($division->id)
                ->exists();

            if (!$divisionBelongsToProject) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'division_id' =>
                            'Divisi yang dipilih tidak terlibat dalam Project tersebut.',
                    ]);
            }

            // Periksa apakah Project memiliki divisi dari departemen berbeda.
            $hasDifferentDepartment = $project->divisions
                ->contains(
                    fn ($projectDivision) =>
                        (int) $projectDivision->department_id
                        !== (int) $division->department_id
                );

            if ($hasDifferentDepartment) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'project_id' =>
                            'Project memiliki divisi lintas departemen. Perbaiki divisi Project terlebih dahulu.',
                    ]);
            }
        }

        $validatedPeriods = validator($validated, [
            'period_start' => [
                'required',
                'date',
            ],
            'period_end' => [
                'required',
                'date',
                'after_or_equal:period_start',
            ],
        ])->validate();

        $validated = array_merge($validated, $validatedPeriods);


        /*
        |--------------------------------------------------------------------------
        | SIMPAN TARGET
        |--------------------------------------------------------------------------
        */

        Target::create([

            /*
            |--------------------------------------------------------------------------
            | Project boleh NULL
            |--------------------------------------------------------------------------
            */

            'project_id' =>
                $validated['project_id'] ?? null,


            /*
            |--------------------------------------------------------------------------
            | Department otomatis
            |--------------------------------------------------------------------------
            */

            'department_id' =>
                $department->id,


            /*
            |--------------------------------------------------------------------------
            | Division
            |--------------------------------------------------------------------------
            */

            'division_id' =>
                $division->id,


            /*
            |--------------------------------------------------------------------------
            | Target
            |--------------------------------------------------------------------------
            */

            'title' =>
                $validated['title'],


            /*
            |--------------------------------------------------------------------------
            | Description
            |--------------------------------------------------------------------------
            */

            'description' =>
                $validated['description'] ?? null,


            /*
            |--------------------------------------------------------------------------
            | Progress awal
            |--------------------------------------------------------------------------
            */

            'target_progress' =>
                0,


            /*
            |--------------------------------------------------------------------------
            | Periode
            |--------------------------------------------------------------------------
            */

            'period_start' =>
                $validated['period_start'],

            'period_end' =>
                $validated['period_end'],


            /*
            |--------------------------------------------------------------------------
            | Status
            |--------------------------------------------------------------------------
            */

            'status' =>
                $validated['status'],

        ]);


        /*
        |--------------------------------------------------------------------------
        | REDIRECT
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('admin.targets.index')
            ->with(
                'success',
                'Target berhasil dibuat.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    public function show(Target $target)
    {
        $this->authorizeTargetScope(
            $target
        );


        $target->load([

            'project',

            'department',

            'division',

            'subTargets' => function ($query) {

                $query->orderBy('id');

            },

            'subTargets.tasks' => function ($query) {

                $query->latest('id');

            },

            'subTargets.tasks.assignedTo',

            'subTargets.tasks.division',

            'subTargets.tasks.subTarget',

        ]);


        return view(
            'admin.targets.show',
            compact('target')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    public function edit(Target $target)
    {
        $user = auth()->user();


        /*
        |--------------------------------------------------------------------------
        | Authorization
        |--------------------------------------------------------------------------
        */

        $this->authorizeTargetScope(
            $target
        );


        /*
        |--------------------------------------------------------------------------
        | Kepala Divisi tidak boleh edit
        |--------------------------------------------------------------------------
        */

        if ($user->role === 'kepala_divisi') {

            abort(
                403,
                'Kepala Divisi tidak memiliki akses untuk mengubah Target.'
            );
        }


        if (!in_array(
            $user->role,
            ['admin', 'kepala_departemen']
        )) {

            abort(
                403,
                'Anda tidak memiliki akses.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | KEPALA DEPARTEMEN
        |--------------------------------------------------------------------------
        */

        if ($user->role === 'kepala_departemen') {

            $departments = Department::where(
                'id',
                $user->department_id
            )->get();


            $projects = Project::whereHas(
                'divisions',
                function ($query) use ($user) {

                    $query->where(
                        'department_id',
                        $user->department_id
                    );

                }
            )
                ->with('divisions')
                ->orderBy('name')
                ->get();


            $divisions = Division::where(
                'department_id',
                $user->department_id
            )
                ->orderBy('name')
                ->get();

        }


        /*
        |--------------------------------------------------------------------------
        | ADMIN
        |--------------------------------------------------------------------------
        */

        elseif ($user->role === 'admin') {

            $departments = Department::orderBy(
                'name'
            )->get();


            $projects = Project::with(
                'divisions'
            )
                ->orderBy('name')
                ->get();


            $divisions = Division::orderBy(
                'name'
            )->get();

        }


        /*
        |--------------------------------------------------------------------------
        | DATA PROJECT UNTUK JAVASCRIPT
        |--------------------------------------------------------------------------
        */

        $projectData = $projects
            ->map(function ($project) {

                return [
                    'id' => $project->id,

                    'name' => $project->name,

                    'description' =>
                        $project->description,

                    'start_date' =>
                        $project->start_date
                            ? $project->start_date->format('Y-m-d')
                            : null,

                    'end_date' =>
                        $project->end_date
                            ? $project->end_date->format('Y-m-d')
                            : null,

                    'status' =>
                        $project->status,

                    'divisions' =>
                        $project->divisions
                            ->map(function ($division) {

                                return [
                                    'id' =>
                                        $division->id,

                                    'name' =>
                                        $division->name,
                                ];

                            })
                            ->values()
                            ->toArray(),
                ];

            })
            ->values()
            ->toArray();


        return view(
            'admin.targets.edit',
            compact(
                'target',
                'departments',
                'projects',
                'divisions',
                'projectData'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        Target $target
    ) {

        $user = auth()->user();


        /*
        |--------------------------------------------------------------------------
        | Authorization
        |--------------------------------------------------------------------------
        */

        $this->authorizeTargetScope(
            $target
        );


        /*
        |--------------------------------------------------------------------------
        | ROLE CHECK
        |--------------------------------------------------------------------------
        */

        if ($user->role === 'kepala_divisi') {

            abort(
                403,
                'Kepala Divisi tidak memiliki akses untuk mengubah Target.'
            );
        }


        if (!in_array(
            $user->role,
            ['admin', 'kepala_departemen']
        )) {

            abort(
                403,
                'Anda tidak memiliki akses.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | VALIDASI
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([

            'project_id' =>
                'nullable|exists:projects,id',

            'division_id' =>
                'required|exists:divisions,id',

            'title' =>
                'required|string|max:255',

            'description' =>
                'nullable|string',

            'period_start' =>
                'required|date',

            'period_end' =>
                'required|date|after_or_equal:period_start',

            'status' =>
                'required|in:active,pending,completed,inactive',

        ]);


        /*
        |--------------------------------------------------------------------------
        | DIVISION
        |--------------------------------------------------------------------------
        */

        $division = Division::findOrFail(
            $validated['division_id']
        );


        /*
        |--------------------------------------------------------------------------
        | DEPARTMENT
        |--------------------------------------------------------------------------
        */

        if ($user->role === 'kepala_departemen') {

            if (
                $division->department_id !=
                $user->department_id
            ) {

                abort(
                    403,
                    'Divisi tersebut bukan berada di bawah Department Anda.'
                );
            }

            $departmentId =
                $user->department_id;

        } else {

            $departmentId =
                $division->department_id;
        }


        /*
        |--------------------------------------------------------------------------
        | PROJECT
        |--------------------------------------------------------------------------
        */

        if (!empty($validated['project_id'])) {

            $project = Project::with('divisions')->findOrFail(
                $validated['project_id']
            );

            // Pastikan divisi terlibat dalam Project.
            $divisionBelongsToProject = $project->divisions()
                ->whereKey($division->id)
                ->exists();

            if (!$divisionBelongsToProject) {

                return back()
                    ->withInput()
                    ->withErrors([
                        'division_id' =>
                            'Divisi yang dipilih tidak terlibat dalam Project tersebut.',
                    ]);
            }

            // Pastikan semua divisi Project berasal dari departemen yang sama.
            $hasDifferentDepartment = $project->divisions
                ->contains(
                    fn ($projectDivision) =>
                        (int) $projectDivision->department_id
                        !== (int) $division->department_id
                );

            if ($hasDifferentDepartment) {

                return back()
                    ->withInput()
                    ->withErrors([
                        'project_id' =>
                            'Project memiliki divisi lintas departemen. Perbaiki divisi Project terlebih dahulu.',
                    ]);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | UPDATE TARGET
        |--------------------------------------------------------------------------
        */

        $target->update([

            'project_id' =>
                $validated['project_id'] ?? null,

            'department_id' =>
                $departmentId,

            'division_id' =>
                $division->id,

            'title' =>
                $validated['title'],

            'description' =>
                $validated['description'] ?? null,

            'period_start' =>
                $validated['period_start'],

            'period_end' =>
                $validated['period_end'],

            'status' =>
                $validated['status'],

        ]);


        /*
        |--------------------------------------------------------------------------
        | REDIRECT
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('admin.targets.index')
            ->with(
                'success',
                'Target berhasil diperbarui.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | DESTROY
    |--------------------------------------------------------------------------
    */

    public function destroy(Target $target)
    {
        $user = auth()->user();


        /*
        |--------------------------------------------------------------------------
        | Authorization
        |--------------------------------------------------------------------------
        */

        $this->authorizeTargetScope(
            $target
        );


        /*
        |--------------------------------------------------------------------------
        | ROLE CHECK
        |--------------------------------------------------------------------------
        */

        if (!in_array(
            $user->role,
            ['admin', 'kepala_departemen']
        )) {

            abort(
                403,
                'Anda tidak memiliki akses untuk menghapus Target.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Kepala Departemen
        |--------------------------------------------------------------------------
        */

        if (
            $user->role === 'kepala_departemen' &&
            $target->department_id !=
            $user->department_id
        ) {

            abort(
                403,
                'Anda hanya dapat menghapus target pada department sendiri.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | DELETE
        |--------------------------------------------------------------------------
        */

        $target->delete();


        return redirect()
            ->route('admin.targets.index')
            ->with(
                'success',
                'Target berhasil dihapus.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | AUTHORIZATION
    |--------------------------------------------------------------------------
    */

    private function authorizeTargetScope(
        Target $target
    ) {

        $user = auth()->user();


        /*
        |--------------------------------------------------------------------------
        | ADMIN
        |--------------------------------------------------------------------------
        */

        if ($user->role === 'admin') {

            return true;
        }


        /*
        |--------------------------------------------------------------------------
        | KEPALA DEPARTEMEN
        |--------------------------------------------------------------------------
        */

        if ($user->role === 'kepala_departemen') {

            if (
                $target->department_id !=
                $user->department_id
            ) {

                abort(
                    403,
                    'Target ini bukan bagian dari Department Anda.'
                );
            }

            return true;
        }


        /*
        |--------------------------------------------------------------------------
        | KEPALA DIVISI
        |--------------------------------------------------------------------------
        */

        if ($user->role === 'kepala_divisi') {

            if (
                $target->division_id !=
                $user->division_id
            ) {

                abort(
                    403,
                    'Target ini bukan bagian dari Divisi Anda.'
                );
            }

            return true;
        }


        /*
        |--------------------------------------------------------------------------
        | ROLE LAIN
        |--------------------------------------------------------------------------
        */

        abort(
            403,
            'Anda tidak memiliki akses terhadap Target.'
        );
    }
}

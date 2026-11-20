<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Division;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ProjectController extends Controller
{
    /**
     * Daftar Project yang dibuat pengguna atau melibatkan
     * divisi dari departemennya.
     */
    public function index()
    {
        $user = auth()->user();

        abort_unless($user->role === 'kepala_departemen', 403);

        $projects = Project::with([
            'divisions.department',
            'targets',
        ])
            ->where(function ($query) use ($user) {
                $query->where('created_by', $user->id);

                if ($user->department_id) {
                    $query->orWhereHas(
                        'divisions',
                        function ($divisionQuery) use ($user) {
                            $divisionQuery->where(
                                'department_id',
                                $user->department_id
                            );
                        }
                    );
                }
            })
            ->latest()
            ->paginate(10);

        return view(
            'kepala-departemen.projects.index',
            compact('projects')
        );
    }

    /**
     * Form tambah Project.
     * Hanya menampilkan divisi dari departemen pengguna.
     */
    public function create()
    {
        $user = auth()->user();

        abort_unless($user->role === 'kepala_departemen', 403);
        abort_unless(
            $user->department_id,
            403,
            'Akun Anda belum terhubung dengan departemen.'
        );

        $department = Department::findOrFail($user->department_id);

        $divisions = Division::with('department')
            ->where('department_id', $user->department_id)
            ->orderBy('name')
            ->get();

        return view(
            'kepala-departemen.projects.create',
            compact('department', 'divisions')
        );
    }

    /**
     * Simpan Project beserta divisi yang berkolaborasi.
     */
    public function store(Request $request)
    {
        $user = auth()->user();

        abort_unless($user->role === 'kepala_departemen', 403);
        abort_unless(
            $user->department_id,
            403,
            'Akun Anda belum terhubung dengan departemen.'
        );

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'description' => [
                'nullable',
                'string',
            ],
            'start_date' => [
                'nullable',
                'date',
            ],
            'end_date' => [
                'nullable',
                'date',
                'after_or_equal:start_date',
            ],
            'division_ids' => [
                'required',
                'array',
                'min:1',
            ],
            'division_ids.*' => [
                'required',
                'integer',
                'distinct',
                'exists:divisions,id',
            ],
        ], [
            'name.required' =>
                'Nama Project wajib diisi.',
            'end_date.after_or_equal' =>
                'Tanggal selesai harus setelah atau sama dengan tanggal mulai.',
            'division_ids.required' =>
                'Pilih minimal satu divisi yang terlibat.',
            'division_ids.min' =>
                'Pilih minimal satu divisi yang terlibat.',
            'division_ids.*.distinct' =>
                'Divisi yang sama tidak boleh dipilih berulang kali.',
            'division_ids.*.exists' =>
                'Salah satu divisi yang dipilih tidak ditemukan.',
        ]);

        $divisionIds = array_map(
            'intval',
            $validated['division_ids']
        );

        // Pastikan semua divisi berada di departemen pengguna.
        $this->validateDivisionIds(
            $divisionIds,
            (int) $user->department_id
        );

        DB::transaction(function () use (
            $validated,
            $divisionIds,
            $user
        ) {
            $project = Project::create([
                'name' => $validated['name'],
                'description' => $validated['description'] ?? null,
                'start_date' => $validated['start_date'] ?? null,
                'end_date' => $validated['end_date'] ?? null,
                'status' => 'active',
                'created_by' => $user->id,
            ]);

            $project->divisions()->sync($divisionIds);
        });

        return redirect()
            ->route('kepala_departemen.projects.index')
            ->with('success', 'Project berhasil dibuat.');
    }

    /**
     * Detail Project.
     * Pembuat dan kepala departemen yang divisinya terlibat
     * dapat melihat detail.
     */
    public function show(Project $project)
    {
        $user = auth()->user();

        abort_unless($user->role === 'kepala_departemen', 403);

        $this->ensureCanView($project, $user);

        $project->load([
            'divisions.department',
            'targets.division',
            'targets.subTargets.tasks',
        ]);

        return view(
            'kepala-departemen.projects.show',
            compact('project')
        );
    }

    /**
     * Form edit hanya untuk pembuat Project.
     */
    public function edit(Project $project)
    {
        $user = auth()->user();

        abort_unless($user->role === 'kepala_departemen', 403);

        $this->ensureCanManage($project, $user);

        abort_unless(
            $user->department_id,
            403,
            'Akun Anda belum terhubung dengan departemen.'
        );

        $department = Department::findOrFail($user->department_id);

        $divisions = Division::with('department')
            ->where('department_id', $user->department_id)
            ->orderBy('name')
            ->get();

        $project->load('divisions');

        return view(
            'kepala-departemen.projects.edit',
            compact('project', 'department', 'divisions')
        );
    }

    /**
     * Perbarui Project beserta divisi yang terlibat.
     */
    public function update(Request $request, Project $project)
    {
        $user = auth()->user();

        abort_unless($user->role === 'kepala_departemen', 403);

        $this->ensureCanManage($project, $user);

        abort_unless(
            $user->department_id,
            403,
            'Akun Anda belum terhubung dengan departemen.'
        );

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'description' => [
                'nullable',
                'string',
            ],
            'start_date' => [
                'nullable',
                'date',
            ],
            'end_date' => [
                'nullable',
                'date',
                'after_or_equal:start_date',
            ],
            'status' => [
                'required',
                'in:active,pending,completed,inactive',
            ],
            'division_ids' => [
                'required',
                'array',
                'min:1',
            ],
            'division_ids.*' => [
                'required',
                'integer',
                'distinct',
                'exists:divisions,id',
            ],
        ], [
            'name.required' =>
                'Nama Project wajib diisi.',
            'end_date.after_or_equal' =>
                'Tanggal selesai harus setelah atau sama dengan tanggal mulai.',
            'division_ids.required' =>
                'Pilih minimal satu divisi yang terlibat.',
            'division_ids.min' =>
                'Pilih minimal satu divisi yang terlibat.',
            'division_ids.*.distinct' =>
                'Divisi yang sama tidak boleh dipilih berulang kali.',
            'division_ids.*.exists' =>
                'Salah satu divisi yang dipilih tidak ditemukan.',
        ]);

        $divisionIds = array_map(
            'intval',
            $validated['division_ids']
        );

        // Cegah perubahan Project ke divisi lintas departemen.
        $this->validateDivisionIds(
            $divisionIds,
            (int) $user->department_id
        );

        DB::transaction(function () use (
            $project,
            $validated,
            $divisionIds
        ) {
            $project->update([
                'name' => $validated['name'],
                'description' => $validated['description'] ?? null,
                'start_date' => $validated['start_date'] ?? null,
                'end_date' => $validated['end_date'] ?? null,
                'status' => $validated['status'],
            ]);

            $project->divisions()->sync($divisionIds);
        });

        return redirect()
            ->route('kepala_departemen.projects.show', $project)
            ->with('success', 'Project berhasil diperbarui.');
    }

        public function destroy(Project $project)
    {
        $user = auth()->user();

        abort_unless(
            $user && $user->role === 'kepala_departemen',
            403,
            'Anda tidak memiliki izin untuk menghapus Project.'
        );

        $this->ensureCanManage($project, $user);

        DB::transaction(function () use ($project) {
            // Lepaskan hubungan Target dari Project.
            // Target tetap tersimpan.
            $project->targets()->update([
                'project_id' => null,
            ]);

            // Hapus hubungan Project dengan divisi.
            $project->divisions()->detach();

            // Hapus Project.
            $project->delete();
        });

        return redirect()
            ->route('kepala_departemen.projects.index')
            ->with('success', 'Project berhasil dihapus.');
    }


    /**
     * Validasi bahwa seluruh divisi yang dipilih benar-benar
     * berada di departemen yang sama.
     */
    private function validateDivisionIds(
        array $divisionIds,
        int $departmentId
    ): void {
        $validCount = Division::whereIn('id', $divisionIds)
            ->where('department_id', $departmentId)
            ->count();

        if ($validCount !== count($divisionIds)) {
            throw ValidationException::withMessages([
                'division_ids' =>
                    'Project hanya boleh melibatkan divisi dari departemen yang sama. Periksa kembali divisi yang dipilih.',
            ]);
        }
    }

    /**
     * Pastikan pengguna boleh melihat Project.
     */
    private function ensureCanView(Project $project, $user): void
    {
        $isCreator = (int) $project->created_by === (int) $user->id;

        $isInvolved = $user->department_id
            && $project->divisions()
                ->where('department_id', $user->department_id)
                ->exists();

        abort_unless(
            $isCreator || $isInvolved,
            403,
            'Anda tidak memiliki akses ke Project ini.'
        );
    }

    /**
     * Hanya pembuat Project yang boleh mengubahnya.
     */
    private function ensureCanManage(Project $project, $user): void
    {
        abort_unless(
            (int) $project->created_by === (int) $user->id,
            403,
            'Hanya pembuat Project yang dapat mengubahnya.'
        );
    }
}

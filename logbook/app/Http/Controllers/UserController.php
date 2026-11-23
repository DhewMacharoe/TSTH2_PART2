<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Division;
use App\Models\User;
use App\Services\EmployeeCodeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    private function roles(): array
    {
        return [
            'admin',
            'pimpinan',
            'kepala_departemen',
            'kepala_divisi',
            'employee',
            'pic_gudang',
            'pic_lab',
        ];
    }

    private function roleOptions(): array
    {
        return [
            (object) [
                'slug'     => 'pic_gudang',
                'name'     => 'PIC Gudang (General Affair)',
                'category' => 'pic',
                'group'    => 'Gudang & Lab (PIC)',
                'badge'    => 'bg-amber-100 text-amber-800',
            ],
            (object) [
                'slug'     => 'pic_lab',
                'name'     => 'PIC Lab (Riset dan Inovasi)',
                'category' => 'pic',
                'group'    => 'Gudang & Lab (PIC)',
                'badge'    => 'bg-emerald-100 text-emerald-800',
            ],
            (object) [
                'slug'     => 'kepala_departemen',
                'name'     => 'Kepala Departemen',
                'category' => 'kepala_departemen',
                'group'    => 'Struktural Organisasi',
                'badge'    => 'bg-blue-100 text-blue-700',
            ],
            (object) [
                'slug'     => 'kepala_divisi',
                'name'     => 'Kepala Divisi',
                'category' => 'kepala_divisi',
                'group'    => 'Struktural Organisasi',
                'badge'    => 'bg-cyan-100 text-cyan-700',
            ],
            (object) [
                'slug'     => 'employee',
                'name'     => 'Karyawan / Employee',
                'category' => 'employee',
                'group'    => 'Operasional Lapangan',
                'badge'    => 'bg-green-100 text-green-700',
            ],
            (object) [
                'slug'     => 'pimpinan',
                'name'     => 'Pimpinan / Direktur Eksekutif',
                'category' => 'pimpinan',
                'group'    => 'Sistem & Eksekutif',
                'badge'    => 'bg-indigo-100 text-indigo-700',
            ],
            (object) [
                'slug'     => 'admin',
                'name'     => 'Administrator Logbook',
                'category' => 'admin',
                'group'    => 'Sistem & Eksekutif',
                'badge'    => 'bg-purple-100 text-purple-700',
            ],
        ];
    }

    /**
     * Validasi hubungan Role, Department, dan Division.
     */
    private function validateOrganizationStructure(
        array &$validated
    ): ?RedirectResponse {
        $role = $validated['role'];

        /*
        |--------------------------------------------------------------------------
        | ADMIN, PIMPINAN, PIC GUDANG, PIC LAB
        |--------------------------------------------------------------------------
        | Admin & Pimpinan tidak memiliki Department maupun Division.
        | PIC Gudang & PIC Lab opsional department (Gudang = GA, Lab = Riset).
        */
        if (in_array($role, ['admin', 'pimpinan'], true)) {
            $validated['department_id'] = null;
            $validated['division_id']   = null;

            return null;
        }

        if (in_array($role, ['pic_gudang', 'pic_lab'], true)) {
            // PIC Gudang & Lab tidak terikat division individual logbook
            $validated['division_id'] = null;
            // Jika tidak diisi otomatis tetapkan: GA untuk gudang, Riset untuk lab
            if (empty($validated['department_id'])) {
                if ($role === 'pic_gudang') {
                    $ga = Department::where('name', 'LIKE', '%General%')->first();
                    $validated['department_id'] = $ga?->id;
                } else {
                    $rdi = Department::where('name', 'LIKE', '%Riset%')->first();
                    $validated['department_id'] = $rdi?->id;
                }
            }
            return null;
        }

        /*
        |--------------------------------------------------------------------------
        | KEPALA DEPARTEMEN
        |--------------------------------------------------------------------------
        | Memiliki Department, tetapi tidak memiliki Division.
        */
        if ($role === 'kepala_departemen') {
            if (empty($validated['department_id'])) {
                return back()
                    ->withErrors([
                        'department_id' => 'Department wajib dipilih untuk Kepala Departemen.',
                    ])
                    ->withInput();
            }

            $validated['division_id'] = null;

            return null;
        }

        /*
        |--------------------------------------------------------------------------
        | KEPALA DIVISI & EMPLOYEE
        |--------------------------------------------------------------------------
        | Keduanya wajib memiliki Department dan Division yang valid.
        */
        if (in_array($role, ['kepala_divisi', 'employee'], true)) {
            if (empty($validated['department_id'])) {
                return back()
                    ->withErrors([
                        'department_id' => 'Department wajib dipilih.',
                    ])
                    ->withInput();
            }

            if (empty($validated['division_id'])) {
                return back()
                    ->withErrors([
                        'division_id' => 'Division wajib dipilih.',
                    ])
                    ->withInput();
            }

            $division = Division::find($validated['division_id']);

            if (!$division || (int) $division->department_id !== (int) $validated['department_id']) {
                return back()
                    ->withErrors([
                        'division_id' => 'Division tidak sesuai dengan Department yang dipilih.',
                    ])
                    ->withInput();
            }
        }

        return null;
    }

    public function index(Request $request)
    {
        $statusFilter = $request->query('status'); // 'all', 'pending', 'approved'

        $query = User::with([
            'department',
            'divisionRelation',
        ])->latest();

        if ($statusFilter && in_array($statusFilter, ['pending', 'approved', 'rejected'], true)) {
            $query->where('status', $statusFilter);
        }

        $users = $query->paginate(15)->withQueryString();

        $totalUsers    = User::count();
        $pendingCount  = User::where('status', 'pending')->count();
        $approvedCount = User::where('status', 'approved')->count();

        $departments = Department::orderBy('name')->get();
        $divisions   = Division::with('department')->orderBy('name')->get();
        $roles       = $this->roleOptions();

        return view(
            'admin.users.index',
            compact(
                'users',
                'totalUsers',
                'pendingCount',
                'approvedCount',
                'statusFilter',
                'departments',
                'divisions',
                'roles'
            )
        );
    }

    public function create()
    {
        $departments = Department::orderBy('name')->get();
        $divisions   = Division::with('department')->orderBy('name')->get();
        $roles       = $this->roleOptions();

        return view(
            'admin.users.create',
            compact(
                'departments',
                'divisions',
                'roles'
            )
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'email' => [
                'required',
                'email',
                'unique:users,email',
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],
            'role' => [
                'required',
                Rule::in($this->roles()),
            ],
            'status' => [
                'nullable',
                Rule::in(['pending', 'approved', 'rejected']),
            ],
            'department_id' => [
                'nullable',
                'exists:departments,id',
            ],
            'division_id' => [
                'nullable',
                'exists:divisions,id',
            ],
            'join_year' => [
                'nullable',
                'integer',
                'min:2000',
                'max:' . (date('Y') + 1),
            ],
        ]);

        $validated['status'] = $validated['status'] ?? 'approved';

        $organizationError = $this->validateOrganizationStructure($validated);
        if ($organizationError) {
            return $organizationError;
        }

        $roleNeedsCode = in_array(
            $validated['role'],
            ['kepala_departemen', 'kepala_divisi', 'employee'],
            true
        );

        if ($roleNeedsCode && empty($validated['join_year'])) {
            return back()
                ->withErrors([
                    'join_year' => 'Tahun masuk wajib diisi untuk role ini.',
                ])
                ->withInput();
        }

        $validated['password'] = Hash::make($validated['password']);
        $flashMessage = '';

        try {
            DB::transaction(function () use (
                &$validated,
                &$flashMessage,
                $roleNeedsCode
            ) {
                $code = null;

                if ($roleNeedsCode && !empty($validated['department_id'])) {
                    $department = Department::findOrFail($validated['department_id']);
                    $service    = new EmployeeCodeService();

                    if ($validated['role'] === 'kepala_departemen') {
                        $code = $service->generateForKepalaDepartemen(
                            $department->name,
                            (int) $validated['join_year']
                        );
                    } elseif (
                        in_array($validated['role'], ['kepala_divisi', 'employee'], true) &&
                        !empty($validated['division_id'])
                    ) {
                        $division = Division::findOrFail($validated['division_id']);
                        $code = $service->generateForDivisionMember(
                            $department->name,
                            $division->name,
                            (int) $validated['join_year']
                        );
                    }
                }

                $validated['employee_code'] = $code;
                $validated['force_logout']  = false;
                User::create($validated);

                $flashMessage = $code
                    ? 'User berhasil ditambahkan. Kode Karyawan: ' . $code
                    : 'User berhasil ditambahkan.';
            }, 3);

        } catch (\Exception $e) {
            Log::error(
                '[EmployeeCode] Gagal membuat user / generate kode: ' . $e->getMessage(),
                ['trace' => $e->getTraceAsString()]
            );

            return redirect()
                ->route('admin.users.index')
                ->with(
                    'warning',
                    'User berhasil ditambahkan, tetapi kode karyawan tidak dapat digenerate: ' . $e->getMessage()
                );
        }

        return redirect()
            ->route('admin.users.index')
            ->with('success', $flashMessage ?: 'User berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        $departments = Department::orderBy('name')->get();
        $divisions   = Division::with('department')->orderBy('name')->get();
        $roles       = $this->roleOptions();

        return view(
            'admin.users.edit',
            compact(
                'user',
                'departments',
                'divisions',
                'roles'
            )
        );
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'role' => [
                'required',
                Rule::in($this->roles()),
            ],
            'status' => [
                'required',
                Rule::in(['pending', 'approved', 'rejected']),
            ],
            'department_id' => [
                'nullable',
                'exists:departments,id',
            ],
            'division_id' => [
                'nullable',
                'exists:divisions,id',
            ],
            'join_year' => [
                'nullable',
                'integer',
                'min:2000',
                'max:' . (date('Y') + 1),
            ],
        ]);

        $organizationError = $this->validateOrganizationStructure($validated);
        if ($organizationError) {
            return $organizationError;
        }

        // Jika user belum memiliki kode unik karyawan dan role membutuhkan kode unik
        $roleNeedsCode = in_array(
            $validated['role'],
            ['kepala_departemen', 'kepala_divisi', 'employee'],
            true
        );

        if (!$user->employee_code && $roleNeedsCode && !empty($validated['join_year']) && !empty($validated['department_id'])) {
            try {
                $department = Department::findOrFail($validated['department_id']);
                $service    = new EmployeeCodeService();

                if ($validated['role'] === 'kepala_departemen') {
                    $validated['employee_code'] = $service->generateForKepalaDepartemen(
                        $department->name,
                        (int) $validated['join_year']
                    );
                } elseif (!empty($validated['division_id'])) {
                    $division = Division::findOrFail($validated['division_id']);
                    $validated['employee_code'] = $service->generateForDivisionMember(
                        $department->name,
                        $division->name,
                        (int) $validated['join_year']
                    );
                }
            } catch (\Exception $e) {
                Log::warning('[EmployeeCode] Gagal generate kode saat update: ' . $e->getMessage());
            }
        }

        // Terapkan force_logout = true agar user otomatis keluar jika sedang aktif
        $validated['force_logout'] = true;

        // Invalidate record sesi pengguna di tabel sessions jika ada
        try {
            DB::table('sessions')->where('user_id', $user->id)->delete();
        } catch (\Throwable $e) {}

        $user->update($validated);

        return redirect()
            ->route('admin.users.index')
            ->with(
                'success',
                "User '{$user->name}' berhasil diperbarui dan disetujui. Sesi aktif user otomatis di-logout."
            );
    }

    /**
     * Setujui akun pengguna (Approval) dan tetapkan role & divisinya secara langsung.
     * Setelah disetujui, akun tersebut otomatis di-logout dari semua sesi aktif.
     */
    public function approve(Request $request, User $user)
    {
        $validated = $request->validate([
            'role' => [
                'required',
                Rule::in($this->roles()),
            ],
            'department_id' => [
                'nullable',
                'exists:departments,id',
            ],
            'division_id' => [
                'nullable',
                'exists:divisions,id',
            ],
            'join_year' => [
                'nullable',
                'integer',
                'min:2000',
                'max:' . (date('Y') + 1),
            ],
        ]);

        $organizationError = $this->validateOrganizationStructure($validated);
        if ($organizationError) {
            return $organizationError;
        }

        $validated['status']       = 'approved';
        $validated['force_logout'] = true;

        $roleNeedsCode = in_array(
            $validated['role'],
            ['kepala_departemen', 'kepala_divisi', 'employee'],
            true
        );

        $joinYear = $validated['join_year'] ?? $user->join_year ?? (int) date('Y');
        $validated['join_year'] = $joinYear;

        if (!$user->employee_code && $roleNeedsCode && !empty($validated['department_id'])) {
            try {
                $department = Department::findOrFail($validated['department_id']);
                $service    = new EmployeeCodeService();

                if ($validated['role'] === 'kepala_departemen') {
                    $validated['employee_code'] = $service->generateForKepalaDepartemen(
                        $department->name,
                        $joinYear
                    );
                } elseif (!empty($validated['division_id'])) {
                    $division = Division::findOrFail($validated['division_id']);
                    $validated['employee_code'] = $service->generateForDivisionMember(
                        $department->name,
                        $division->name,
                        $joinYear
                    );
                }
            } catch (\Exception $e) {
                Log::warning('[EmployeeCode] Gagal generate kode saat approve: ' . $e->getMessage());
            }
        }

        // Hapus sesi aktif dari database agar user langsung logout
        try {
            DB::table('sessions')->where('user_id', $user->id)->delete();
        } catch (\Throwable $e) {}

        $user->update($validated);

        return redirect()
            ->route('admin.users.index')
            ->with(
                'success',
                "Akun '{$user->name}' berhasil disetujui (Approved) sebagai " .
                ucwords(str_replace('_', ' ', $user->role)) .
                ". Sesi user otomatis di-logout."
            );
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()
                ->route('admin.users.index')
                ->with(
                    'error',
                    'Anda tidak dapat menghapus akun sendiri.'
                );
        }

        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with(
                'success',
                'User berhasil dihapus.'
            );
    }
}

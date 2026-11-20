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
                'slug' => 'admin',
                'name' => 'Admin',
                'group' => 'Sistem',
            ],
            (object) [
                'slug' => 'pimpinan',
                'name' => 'Pimpinan / Direktur',
                'group' => 'Sistem',
            ],
            (object) [
                'slug' => 'kepala_departemen',
                'name' => 'Kepala Departemen',
                'group' => 'Struktural',
            ],
            (object) [
                'slug' => 'kepala_divisi',
                'name' => 'Kepala Divisi',
                'group' => 'Struktural',
            ],
            (object) [
                'slug' => 'employee',
                'name' => 'Karyawan / Employee',
                'group' => 'Operasional',
            ],
            (object) [
                'slug' => 'pic_gudang',
                'name' => 'PIC Gudang',
                'group' => 'Gudang & Inventaris',
            ],
            (object) [
                'slug' => 'pic_lab',
                'name' => 'PIC Lab',
                'group' => 'Gudang & Inventaris',
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
        | Tidak memiliki Department maupun Division.
        */
        if (in_array($role, ['admin', 'pimpinan', 'pic_gudang', 'pic_lab'], true)) {
            $validated['department_id'] = null;
            $validated['division_id'] = null;

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
                        'department_id' =>
                            'Department wajib dipilih untuk Kepala Departemen.',
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
        | Keduanya wajib memiliki Department dan Division.
        */
        if (in_array($role, ['kepala_divisi', 'employee'], true)) {

            if (empty($validated['department_id'])) {
                return back()
                    ->withErrors([
                        'department_id' =>
                            'Department wajib dipilih.',
                    ])
                    ->withInput();
            }

            if (empty($validated['division_id'])) {
                return back()
                    ->withErrors([
                        'division_id' =>
                            'Division wajib dipilih.',
                    ])
                    ->withInput();
            }

            /*
            |--------------------------------------------------------------------------
            | Pastikan Division berada di Department yang dipilih
            |--------------------------------------------------------------------------
            */
            $division = Division::findOrFail(
                $validated['division_id']
            );

            if (
                (int) $division->department_id !==
                (int) $validated['department_id']
            ) {
                return back()
                    ->withErrors([
                        'division_id' =>
                            'Division tidak sesuai dengan Department yang dipilih.',
                    ])
                    ->withInput();
            }
        }

        return null;
    }

    public function index()
    {
        $users = User::with([
            'department',
            'divisionRelation',
        ])
            ->latest()
            ->paginate(10);

        return view(
            'admin.users.index',
            compact('users')
        );
    }

    public function create()
    {
        $departments = Department::orderBy('name')->get();

        $divisions = Division::with('department')
            ->orderBy('name')
            ->get();

        $roles = $this->roleOptions();

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

            'department_id' => [
                'nullable',
                'exists:departments,id',
            ],

            'division_id' => [
                'nullable',
                'exists:divisions,id',
            ],

            /*
             * join_year WAJIB diisi untuk role yang mendapat kode unik.
             * Untuk admin & pimpinan, field ini boleh kosong.
             */
            'join_year' => [
                'nullable',
                'integer',
                'min:2000',
                'max:' . (date('Y') + 1),
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Validasi struktur organisasi
        |--------------------------------------------------------------------------
        */
        $organizationError =
            $this->validateOrganizationStructure($validated);

        if ($organizationError) {
            return $organizationError;
        }

        /*
        |--------------------------------------------------------------------------
        | Validasi join_year wajib untuk role berkode
        |--------------------------------------------------------------------------
        */
        $roleNeedsCode = in_array(
            $validated['role'],
            ['kepala_departemen', 'kepala_divisi', 'employee'],
            true
        );

        if ($roleNeedsCode && empty($validated['join_year'])) {
            return back()
                ->withErrors([
                    'join_year' =>
                        'Tahun masuk wajib diisi untuk role ini.',
                ])
                ->withInput();
        }

        /*
        |--------------------------------------------------------------------------
        | Hash Password
        |--------------------------------------------------------------------------
        */
        $validated['password'] = Hash::make(
            $validated['password']
        );

        /*
        |--------------------------------------------------------------------------
        | Simpan User + Generate Kode dalam satu Transaksi
        |--------------------------------------------------------------------------
        | DB::transaction + lockForUpdate mencegah dua request bersamaan
        | menghasilkan nomor urut yang sama (race condition).
        */
        $flashMessage = '';

        try {
            DB::transaction(function () use (
                &$validated,
                &$flashMessage,
                $roleNeedsCode
            ) {
                $code = null;

                if ($roleNeedsCode && ! empty($validated['department_id'])) {
                    $department = Department::findOrFail($validated['department_id']);
                    $service    = new EmployeeCodeService();

                    /*
                    |----------------------------------------------------------
                    | KEPALA DEPARTEMEN → format DDDKDYYNNN
                    |----------------------------------------------------------
                    */
                    if ($validated['role'] === 'kepala_departemen') {
                        $code = $service->generateForKepalaDepartemen(
                            $department->name,
                            (int) $validated['join_year']
                        );
                    }

                    /*
                    |----------------------------------------------------------
                    | KEPALA DIVISI & EMPLOYEE → format DDDBBYYNNN
                    |----------------------------------------------------------
                    | Nomor urut (NNN) bersifat SHARED dalam satu kombinasi
                    | DDD+BB+YY, sehingga Kepala Divisi dan Employee pada divisi
                    | & tahun yang sama tidak pernah mendapat nomor yang sama.
                    */
                    elseif (
                        in_array($validated['role'], ['kepala_divisi', 'employee'], true) &&
                        ! empty($validated['division_id'])
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
                User::create($validated);

                $flashMessage = $code
                    ? 'User berhasil ditambahkan. Kode: ' . $code
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
                    'User berhasil ditambahkan, tetapi kode karyawan ' .
                    'tidak dapat digenerate: ' . $e->getMessage()
                );
        }

        return redirect()
            ->route('admin.users.index')
            ->with('success', $flashMessage ?: 'User berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        $departments = Department::orderBy('name')->get();

        $divisions = Division::with('department')
            ->orderBy('name')
            ->get();

        $roles = $this->roleOptions();

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

    public function update(
        Request $request,
        User $user
    ) {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')
                    ->ignore($user->id),
            ],

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
        ]);

        /*
        |--------------------------------------------------------------------------
        | Validasi struktur organisasi
        |--------------------------------------------------------------------------
        */
        $organizationError =
            $this->validateOrganizationStructure($validated);

        if ($organizationError) {
            return $organizationError;
        }

        /*
        |--------------------------------------------------------------------------
        | Update User
        |--------------------------------------------------------------------------
        */
        $user->update($validated);

        return redirect()
            ->route('admin.users.index')
            ->with(
                'success',
                'User berhasil diperbarui.'
            );
    }

    public function destroy(User $user)
    {
        /*
        |--------------------------------------------------------------------------
        | Tidak boleh menghapus akun sendiri
        |--------------------------------------------------------------------------
        */
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
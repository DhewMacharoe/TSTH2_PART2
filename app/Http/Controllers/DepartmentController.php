<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    /**
     * Menampilkan daftar Department.
     */
    public function index()
    {
        $departments = Department::withCount([
            'divisions',
            'users',
        ])
            ->orderBy('name')
            ->paginate(10);

        return view(
            'admin.departments.index',
            compact('departments')
        );
    }

    /**
     * Menampilkan form tambah Department.
     */
    public function create()
    {
        return view('admin.departments.create');
    }

    /**
     * Menyimpan Department baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                'unique:departments,name',
            ],
        ], [
            'name.required' => 'Nama Department wajib diisi.',
            'name.unique' => 'Department tersebut sudah tersedia.',
        ]);

        Department::create($validated);

        return redirect()
            ->route('admin.departments.index')
            ->with(
                'success',
                'Department berhasil ditambahkan.'
            );
    }

    /**
     * Menampilkan form edit Department.
     */
    public function edit(Department $department)
    {
        return view(
            'admin.departments.edit',
            compact('department')
        );
    }

    /**
     * Memperbarui Department.
     */
    public function update(
        Request $request,
        Department $department
    ) {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                'unique:departments,name,' . $department->id,
            ],
        ], [
            'name.required' => 'Nama Department wajib diisi.',
            'name.unique' => 'Department tersebut sudah tersedia.',
        ]);

        $department->update($validated);

        return redirect()
            ->route('admin.departments.index')
            ->with(
                'success',
                'Department berhasil diperbarui.'
            );
    }

    /**
     * Menghapus Department.
     */
    public function destroy(Department $department)
    {
        /*
        |--------------------------------------------------------------------------
        | Cek apakah Department masih digunakan oleh Division
        |--------------------------------------------------------------------------
        */

        if ($department->divisions()->exists()) {
            return redirect()
                ->route('admin.departments.index')
                ->with(
                    'error',
                    'Department tidak dapat dihapus karena masih memiliki Division.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Cek apakah Department masih digunakan oleh User
        |--------------------------------------------------------------------------
        */

        if ($department->users()->exists()) {
            return redirect()
                ->route('admin.departments.index')
                ->with(
                    'error',
                    'Department tidak dapat dihapus karena masih memiliki User.'
                );
        }

        $department->delete();

        return redirect()
            ->route('admin.departments.index')
            ->with(
                'success',
                'Department berhasil dihapus.'
            );
    }
}
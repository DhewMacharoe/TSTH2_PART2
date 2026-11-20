<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Division;
use Illuminate\Http\Request;

class DivisionController extends Controller
{
    public function index(Department $department)
    {
        $divisions = $department->divisions()
            ->withCount('users')
            ->orderBy('name')
            ->paginate(10);

        return view(
            'admin.divisions.index',
            compact('department', 'divisions')
        );
    }

    public function create(Department $department)
    {
        return view(
            'admin.divisions.create',
            compact('department')
        );
    }

    public function store(Request $request, Department $department)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                'unique:divisions,name',
            ],
        ], [
            'name.required' => 'Nama Division wajib diisi.',
            'name.unique' => 'Division tersebut sudah tersedia.',
        ]);

        $department->divisions()->create($validated);

        return redirect()
            ->route('admin.departments.divisions.index', $department)
            ->with('success', 'Division berhasil ditambahkan.');
    }

    public function edit(
        Department $department,
        Division $division
    ) {
        if ((int) $division->department_id !== (int) $department->id) {
            abort(404);
        }

        return view(
            'admin.divisions.edit',
            compact('department', 'division')
        );
    }

    public function update(
        Request $request,
        Department $department,
        Division $division
    ) {
        if ((int) $division->department_id !== (int) $department->id) {
            abort(404);
        }

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                'unique:divisions,name,' . $division->id,
            ],
        ], [
            'name.required' => 'Nama Division wajib diisi.',
            'name.unique' => 'Division tersebut sudah tersedia.',
        ]);

        $division->update($validated);

        return redirect()
            ->route('admin.departments.divisions.index', $department)
            ->with('success', 'Division berhasil diperbarui.');
    }

    public function destroy(
        Department $department,
        Division $division
    ) {
        if ((int) $division->department_id !== (int) $department->id) {
            abort(404);
        }

        if ($division->users()->exists()) {
            return redirect()
                ->route('admin.departments.divisions.index', $department)
                ->with(
                    'error',
                    'Division tidak dapat dihapus karena masih digunakan oleh User.'
                );
        }

        $division->delete();

        return redirect()
            ->route('admin.departments.divisions.index', $department)
            ->with('success', 'Division berhasil dihapus.');
    }
}
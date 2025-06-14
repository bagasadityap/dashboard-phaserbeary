<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Yajra\DataTables\Facades\DataTables;

class RoleController extends Controller
{
    public function index() {
        $page = 'Role & Permission';
        $role = Role::all();

        if (request()->ajax()) {
            return DataTables::of(Role::query())
                ->addColumn('_', function($model) {
                    $html = '';
                    if (auth()->user()->can('Role Setting')) {
                        $html .= '<button href="" class="btn btn-outline-primary px-2 me-1 d-inline-flex align-items-center" onclick="setting(\'' . $model->id . '\')"><i class="iconoir-settings fs-14"></i></button>';
                    }
                    if (auth()->user()->can('Role Edit')) {
                        $html .= '<button href="" class="btn btn-outline-warning px-2 me-1 d-inline-flex align-items-center" onclick="edit(\'' . $model->id . '\')"><i class="iconoir-edit fs-14"></i></button>';
                    }
                    if (auth()->user()->can('Role Delete')) {
                        $html .= '<button href="" class="btn btn-outline-danger px-2 d-inline-flex align-items-center" onclick="remove(\'' . $model->id . '\')"><i class="iconoir-trash fs-14"></i></button>';
                    }
                    return $html;
                })
                ->rawColumns(['_'])
                ->make(true);
        }

        return view('configuration.role.index', compact('page', 'role'));
    }

    public function store(Request $request) {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
            ]);

            $role = Role::create([
                'name' => $request->name,
            ]);

            $role->save();

            return redirect()->back()->with('success', 'Data berhasil ditambahkan.');
        } catch (ValidationException $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan.');
        }
    }

    public function create() {

        return view('configuration.role.create');
    }

    public function edit($id) {
        $role = Role::where('id', $id)->firstOrFail();

        return view('configuration.role.update', compact('role'));
    }

    public function update(Request $request, $id) {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
            ]);

            $role = Role::where('id', $id)->firstOrFail();
            $role->name = $request->name;
            $role->save();

            return redirect()->back()->with('success', 'Data berhasil diperbarui.');
        } catch (ValidationException $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan');
        }
    }

    public function updatePermission(Request $request, $id) {
        DB::beginTransaction();

        $role = Role::where('id', $id)->firstOrFail();
        $role->permissions()->detach();

        $permissions = $request->all();

        foreach ($permissions as $fieldName => $value) {
            if ($fieldName !== '_token') {
                $role->givePermissionTo($value);
            }
        }

        DB::commit();

        return redirect()->back()->with('success', 'Data berhasil diperbarui.');
    }

    public function delete($id) {
        $role = Role::findOrFail($id);

        if ($role->users()->exists()) {
            session()->flash('error', 'Role tidak dapat dihapus karena terdapat user yang ditempatkan pada role ini.');
            return response()->json([
                'success' => false,
                'message' => 'Role tidak dapat dihapus karena terdapat user yang ditempatkan pada role ini.'
            ]);
        }

        $role->delete();
        session()->flash('success', 'Data berhasil dihapus.');
        return response()->json([
            'success' => true,
            'message' => 'Data berhasil dihapus'
        ]);
    }

    public function setting($id) {
        $role = Role::where('id', $id)->firstOrFail();
        $permissions = Permission::parent()->orderByDesc('created_at')->get();
        $permissionsChild = Permission::child()->get();

        return view('configuration.role.setting', compact('role', 'permissions', 'permissionsChild'));
    }
}

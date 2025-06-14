<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function index() {
        $page = 'Configuration \ User';
        $users = User::all();

        if (request()->ajax()) {
            return DataTables::of(User::query())
                ->addColumn('role', function($model) {
                    return $model->getRoleNames()->first();
                })
                ->addColumn('_', function($model) {
                    $html = '';
                    if (auth()->user()->can('User Edit')) {
                        $html .= '<button href="" class="btn btn-outline-warning px-2 me-1 d-inline-flex align-items-center" onclick="edit(\'' . $model->id . '\')"><i class="iconoir-edit fs-14"></i></button>';
                    }
                    if (auth()->user()->can('User Delete')) {
                        $html .= '<button href="" class="btn btn-outline-danger px-2 d-inline-flex align-items-center" onclick="remove(\'' . $model->id . '\')"><i class="iconoir-trash fs-14"></i></button>';
                    }
                    return $html;
                })
                ->rawColumns(['_'])
                ->make(true);
        }

        return view('configuration.user.index', compact('page', 'users'));
    }

    public function create(Request $request) {
        $datas = Role::all();

        return view('configuration.user.create', compact('datas'));
    }

    public function store(Request $request) {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'username' => 'required|string|max:255',
                'email' => 'required|string|max:255|unique:users',
                'instansi' => 'required|string|max:255',
                'password' => 'required|string',
                'role' => 'required',
            ]);

            $user = User::create([
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'instansi' => $request->instansi,
                'password' => Hash::make($request->password),
            ]);

            $role = Role::where('name', $request->role)->first();
            if ($role) {
                $user->assignRole($role);
            }

            $user->save();

            return redirect()->back()->with('success', 'Data berhasil ditambahkan.');
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        }
    }

    public function edit($id) {
        $user = User::findOrFail($id);
        $datas = Role::all();

        return view('configuration.user.update', compact('user', 'datas'));
    }

    public function update(Request $request, $id) {
        try {
            $user = User::findOrFail($id);

            $request->validate([
                'name' => 'required|string|max:255',
                'username' => 'required|string|max:255',
                'email' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('users')->ignore($user),
                ],
                'instansi' => 'required|string|max:255',
                'password' => 'nullable|string',
                'role' => 'required',
            ]);

            $user->name = $request->name;
            $user->username = $request->username;
            $user->email = $request->email;
            $user->instansi = $request->instansi;
            if (!empty($request->password)) {
                $user->password = Hash::make($request->password);
            }

            $user->syncRoles([$request->role]);

            $user->save();

            return redirect()->back()->with('success', 'Data berhasil diperbarui.');
        } catch (ValidationException $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan');
        }
    }

    public function delete($id) {
        $user = User::findOrFail($id);


        if ($user) {
            if ($user->username == 'admin' || $id == auth()->user()->id) {
                session()->flash('error', 'Anda tidak dapat menghapus user ini.');
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak dapat menghapus user ini'
                ]);
            } elseif ($user->pesananGedung()->exists() || $user->pesananPublikasi()->exists()) {
                session()->flash('error', 'User tidak dapat dihapus karena terdapat pesanan yang terkait.');
                return response()->json([
                    'success' => false,
                    'message' => 'User tidak dapat dihapus karena tedapat pesanan yang terkait.'
                ]);
            }
            $user->delete();
            session()->flash('success', 'Data berhasil dihapus.');
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil dihapus'
            ]);
        }
    }

}

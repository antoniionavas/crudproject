<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;


class UserController extends Controller
{

    public function index()
    {
        return view('admin.users.index');
    }

        public function create()
    {
        return view('admin.users.create');
    }

    public function edit(User $user)
    {
        $data = [
            'user' => $user,
        ];
        return view('admin.users.edit', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email|max:255',
            'password' => 'required|string|min:8|confirmed',
        ]);
    
        $is_admin = $request->has('is_admin') ? true : false;
    
        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')),
            'is_admin' => $is_admin,
        ]);
    
        return redirect()->route('usuarios.index')->with('message', 'El usuario se creó con éxito');
    }
    

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'password' => 'nullable|string|min:8', 
        ]);
    
        $is_admin = $request->has('is_admin') ? true : false;
    
        $user->update([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => $request->filled('password') ? bcrypt($request->input('password')) : $user->password, 
            'is_admin' => $is_admin,
        ]);
    
        return redirect()->route('usuarios.index')->with('message', 'El usuario se actualizó con éxito');
    }
    

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('usuarios.index')->with('message', 'El usuario se eliminó con éxito');;
    }
}

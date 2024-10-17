<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;


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
        return view('admin.users.edit', compact('user'));
    }

        public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'password' => 'required',
        ]);

        $user = User::create($request->all());
        return redirect()->route('usuarios.edit', $user)->with('info', 'El usuario se creó con éxito');
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'password' => 'required',
        ]);

        $user->update($request->all());

        return redirect()->route('usuarios.edit', $user)->with('info', 'El usuario se actualizó con éxito');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('usuarios.index')->with('info', 'El usuario se eliminó con éxito');;
    }
}

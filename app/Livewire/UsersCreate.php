<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Hash;

class UsersCreate extends Component
{
    public $name;
    public $email;
    public $password;
    public $password_confirmation;
    public $terms = false;
    public $is_admin = false; 

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:8|confirmed',
        'terms' => 'accepted',
    ];

    public function create()
    {
        $this->validate();

        User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'is_admin' => $this->is_admin, 
        ]);

        session()->flash('message', 'Usuario creado con éxito.');
        return redirect()->route('home');
    }
    
    public function render()
    {
        return view('livewire.users-create');
    }
}



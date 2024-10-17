<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;

class UsersEdit extends Component
{
    public $user;
    public $name;
    public $email;
    public $is_admin = false;

    public function mount(User $user)
    {
        $this->user = $user;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->is_admin = $user->is_admin; 
    }

    public function updateUser()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
        ]);

        $this->user->update([
            'name' => $this->name,
            'email' => $this->email,
            'is_admin' => $this->is_admin,
        ]);

        session()->flash('message', 'Usuario actualizado con éxito.');
    }

    public function render()
    {
        return view('livewire.users-edit');
    }
}

<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class UsersIndex extends Component
{
    use WithPagination;

    protected $paginationTheme="bootstrap";

    public $search;

    public function updatingSearch()
    {
        $this->resetPage();
        logger('Search updated: ' . $this->search);
    }

    public function render()
    {
        $search = '%' . strtolower($this->search) . '%';

        $users = User::whereRaw('LOWER(name) LIKE ?', [$search])
            ->orWhereRaw('LOWER(email) LIKE ?', [$search])
            ->paginate();

        return view('livewire.users-index', compact('users'));
    }
}

<?php

namespace App\Livewire;

use App\Models\Project;
use Livewire\Component;
use Livewire\WithPagination;

class ProjectsIndex extends Component
{
    use WithPagination;

    protected $paginationTheme = "bootstrap";

    public $search;

    public function updatingSearch()
    {
        $this->resetPage();
        logger('Search updated: ' . $this->search);
    }

    public function render()
    {
        $search = '%' . strtolower($this->search) . '%';
    
        $projects = Project::where('name', 'like', $search) 
            ->orWhereHas('user', function($query) use ($search) { 
                $query->whereRaw('LOWER(name) LIKE ?', [$search]);
            })
            ->paginate();
    
        return view('livewire.projects-index', compact('projects'));
    }
    
}

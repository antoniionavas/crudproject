<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::with('user')->orderBy('updated_at', 'desc')->get();
        return view('admin.projects.index', compact('projects'));
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);

        Project::create([
            'name' => $request->name,
            'user_id' => auth()->id(),
        ]);

        return response()->log(['message' => 'El proyecto se creó con éxito']);
    }
}

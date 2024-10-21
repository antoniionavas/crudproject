<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index()
    {
        return view('admin.projects.index');
    }

    public function getProjects()
    {
        $projects = Project::with('user')->orderBy('updated_at', 'desc')->get();

        return response()->json($projects);
    }

    public function createProjects(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);

        Project::create([
            'name' => $request->name,
            'user_id' => auth()->id(),
        ]);

        return response()->log(['message' => 'El proyecto se creó con éxito']);
    }

    public function load()
    {
    
        $user = auth()->user();    
        
        if ($user->is_admin){
            $tasks = Task::all();
        } else {
            $tasks = Task::where('user_id', $user->id)->get();
        }
        
        $events = [];
        foreach ($tasks as $task) {
            $events[] = [
                'title' => $task->project->name,
                'start' => $task->start_time,
                'end' => $task->end_time,
                'description' => $task->description,
            ];
        }

        return response()->json($events);
    }

    public function storeTask(Request $request)
    {
        Task::create([
            'project_id' => $request->project_id,
            'user_id' => auth()->id(),
            'start' => $request->start_time,
            'end' => $request->end_time,
            'description' => $request->description
        ]);

        return response()->json(['success' => true]);
    }
}

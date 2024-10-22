<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use App\Models\Project;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::all();
        $users = User::all();
        return view('admin.projects.index', compact('projects', 'users'));
    }

    public function getProjects()
    {
        $projects = Project::with('user')->orderBy('updated_at', 'desc')->get();


        $events = [];
        foreach ($projects as $project) {
            $date =  Carbon::parse($project->updated_at)->format('d-m-Y');
            $events[] = [
                'id' => $project->id,
                'name' => $project->name,
                'user' => $project->user->name,
                'date' => $date,
            ];
        }

        return response()->json($events);
    }

    public function createProjects(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);

        Project::create([
            'name' => $request->name,
            'user_id' => auth()->id(),
        ]);

        return response()->json([
            'message' => 'El proyecto se creó con éxito'
        ]);
    }

    public function load()
    {
        $user = auth()->user();

        if ($user->is_admin) {
            $tasks = Task::all();
        } else {
            $tasks = Task::where('user_id', $user->id)->get();
        }

        $events = [];
        foreach ($tasks as $task) {
            $events[] = [
                'id' => $task->id,
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
        $request->validate([
            'project_id' => 'required|integer',
            'description' => 'required|string|max:255'
        ]);

    
        $tarea = Task::create([
            'project_id' => $request->project_id,
            'user_id' => auth()->id(),
            'start_time' => Carbon::parse($request->start),
            'end_time' => Carbon::parse($request->end),
            'description' => $request->description
        ]);
    
        return response()->json(['success' => true, 'message' => 'Tarea creada con éxito.', 'tarea' => $tarea]);
    }


    public function generatePdf(Request $request)
    {
        $projectId = $request->input('project_id');
        $userId = $request->input('user_id');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $tasksQuery = Task::with('project', 'user');

        if ($projectId) {
            $tasksQuery->where('project_id', $projectId);
        }
        if ($userId) {
            $tasksQuery->where('user_id', $userId);
        }
        if ($startDate) {
            $tasksQuery->where('start_time', '>=', $startDate);
        }
        if ($endDate) {
            $tasksQuery->where('end_time', '<=', $endDate);
        }

        $tasks = $tasksQuery->get();
        $tasksByProject = $tasks->groupBy('project_id');

        $projectName = $projectId ? Project::find($projectId)->name : 'Todos los proyectos';
        $userName = $userId ? User::find($userId)->name : 'Todos los usuarios';

        $pdf = PDF::loadView('admin.projects.report', compact('tasksByProject', 'startDate', 'endDate', 'projectName', 'userName'));

        return $pdf->download('informe-tareas.pdf');
    }
}

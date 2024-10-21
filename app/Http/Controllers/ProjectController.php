<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use App\Models\Project;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Http\Request;

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

        if ($user->is_admin) {
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

        // Obtén el nombre del proyecto y del usuario (si se seleccionó)
        $projectName = $projectId ? Project::find($projectId)->name : 'Todos los proyectos';
        $userName = $userId ? User::find($userId)->name : 'Todos los usuarios';

        $pdf = PDF::loadView('admin.projects.report', compact('tasksByProject', 'startDate', 'endDate', 'projectName', 'userName'));

        return $pdf->download('informe-tareas.pdf');
    }
}

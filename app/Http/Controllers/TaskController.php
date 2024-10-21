<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $tasks = Task::where('user_id', $request->user_id)->get();

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

    public function store(Request $request)
    {
        Task::create([
            'project_id' => $request->project_id,
            'user_id' => auth()->id(),
            'start' => $request->start_time,
            'end' => $request->start_time,
            'description' => $request->description
        ]);

        return response()->json(['success' => true]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ArchivedTaskController extends Controller
{
    public function index()
    {
        $tasks = Task::with('user:id,name')
            ->where('status', 'Archived')
            ->latest()
            ->get();

        return Inertia::render('ArchivedTasks', [
            'tasks' => $tasks,
            'users' => User::all(),
            'current_user' => auth()->user(),
        ]);
    }
}

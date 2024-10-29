<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        return Inertia::render('Task/Index', [
            'tasks' => Task::with('user:id,name')->latest()->get(),
            'users' => User::all(),
            'current_user' => auth()->user()->id,

        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'task' => 'required|string|max:255',
            'taskdescription' => 'required|string',
        ]);

        $task = $request->user()->tasks()->create([
            'task' => $validated['task'],
            'taskdescription' => $validated['taskdescription'],
            'user_id' => $request->user()->id,
        ]);

        $task->user()->attach($request->assigned_to);

        return redirect(route('tasks.index'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task): RedirectResponse
    {
        // Authorize the action using the Task model
        Gate::authorize('update', $task);

        $validated = $request->validate([
            'task' => 'required|string|max:255', // Validate task title
            'taskdescription' => 'required|string', // Validate task description
        ]);

        $task->update($validated);

        return redirect(route('tasks.index'));

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task): RedirectResponse
    {
        Gate::authorize('delete', $task);

        $task->delete();

        return redirect(route('tasks.index'));
    }
}

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
            'due' => 'required|date',
        ]);

        $task = $request->user()->tasks()->create([
            'task' => $validated['task'],
            'taskdescription' => $validated['taskdescription'],
            'user_id' => $request->user()->id,
            'due' => $validated['due'],
        ]);

        $task->user()->attach($request->assigned_to);

        return redirect(route('dashboard'));
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
            'due' => 'required|date'
        ]);
        $task->update($validated);

        $assignedUsers = $request->input('assigned_to', []);
        $task->user()->sync($assignedUsers);

        return redirect(route('dashboard'));

    }

    public function updateStatus(Request $request, Task $task): RedirectResponse
    {

        $task->status = ($task->status === 'Done') ? 'Pending' : 'Done';
        $task->save();

        return redirect(route('dashboard'));
    }

    public function addToArchive(Request $request, Task $task): RedirectResponse
    {
        if ($task->status === 'Archived') {
            $task->status = 'Pending'; // Or restore to its previous status
        } else {
            $task->status = 'Archived';
        }
        $task->save();

        return redirect(route('dashboard'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task): RedirectResponse
    {
        Gate::authorize('delete', $task);

        $task->delete();

        return redirect(route('dashboard'));
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class PlanController extends Controller

{

    public function index(Request $request)
    {
        $path = $request->path();

        if ($path === 'day') 
        {
            $viewName = 'day';
            $perPage = 15;
        } 
        else if ($path === 'tasks') 
        {
            $viewName = 'tasks';
            $perPage = 50;
        } 
        else if ($path === 'planned') 
        {
            $viewName = 'planned';
            $perPage = 500;
        }
        else if ($path === 'gotSend') 
        {
            $viewName = 'send';
            $perPage = 500;

            $tasks = Task::where('user_id', auth()->id())
                ->where('send',  true)
                ->paginate($perPage);

            return view($viewName, ['tasks' => $tasks]);
        }
        else if ($path === 'important') 
        {
            $viewName = 'important';
            $perPage = 500;

            $tasks = Task::where('user_id', auth()->id())
                ->where('important',  true)
                ->paginate($perPage);

            return view($viewName, ['tasks' => $tasks]);
        }
        else 
        {
            $viewName = 'day';
            $perPage = 15;
        }
        


        $tasks = Task::where('user_id', auth()->id()) 
            ->paginate($perPage);

        return view($viewName, ['tasks' => $tasks]);
    }

    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:100',
            'message' => 'required|string|max:1000',
        ], [
            'message.required' => 'Het bericht veld is verplicht.',
            'message.max' => 'Het bericht mag niet langer zijn dan 1000 tekens.',
        ]);

        auth()->user()->tasks()->create($validated);

        return back()->with('success', 'Task is gemaakt!');
    }

    public function show(string $task)
    {

    }

    public function toggleImportant(Request $request, Task $task)
    {
        if (auth()->user()->cannot('update', $task)) 
        {
            abort(403, 'Je hebt geen toestemming om deze taak als belangrijk te markeren.');
        }


        $task->important = $request->has('important');
        $task->save();

        return back();
    }

    public function edit(string $id)
    {
        $task = Task::findOrFail($id);

        if ($task->user_id !== auth()->id()) {
            abort(403, 'Je mag deze taak niet bewerken.');
        }

        return view('tasks.edit', compact('task'));
    }

    public function update(Request $request, Task $task)
    {
        if ($task->user_id !== auth()->id()) {
            abort(403, 'Je mag deze taak niet bewerken.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:100',
            'message' => 'required|string|max:1000'
        ]);

        $task->update($validated);

        return redirect('/tasks')->with('success', 'Task is aangepast!');
    }

    public function destroy(Task $task)
    {
        if (auth()->user()->cannot('delete', $task)) 
        {
            abort(403, 'Je hebt geen toestemming om deze taak te verwijderen.');
        }
        $task->delete();
        return back()->with('success', 'Task is verwijderd!');
    }
}

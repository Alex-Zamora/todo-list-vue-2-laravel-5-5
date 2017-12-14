<?php

namespace App\Http\Controllers;

use App\Task;
use App\Http\Requests\CreateTaskRequest;
use Illuminate\Http\Request;

class TaskController extends Controller
{

    public function index()
    {
        return Task::latest()->get();
    }


    public function store(CreateTaskRequest $request)
    {
        // $this->validate($request, [
        //     'body' => 'required'
        // ],[
        //     'body.required' => 'Por favor escribe tu tarea'
        // ]);

        return Task::create($request->all()); //Guardar datos en la DB
 
    }


    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'title' => 'required',
            'body' => 'required'
        ],[
            'title.required' => 'El campo Title es requerido.',
            'body.required' => 'El campo Body es requerido.',
        ]);

        // Task::find($id)->update($request->all());

        $task = Task::findOrFail($id);
        $task->update($request->all());

        return $task;
    }


    public function destroy($id)
    {
        $task = Task::findOrFail($id);
        $task->delete();
        return 204;
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    /**
     * Display a listing of the resource.
     */


    public function index()
    {
        return Todo::all();
    }

    public function store(Request $request)
    {
        return Todo::create([
            'title' => $request->title,
        ]);
    }





    public function toggleStatus($id)
    {
        $todo = Todo::findOrFail($id);
        $todo->completed = !$todo->completed;
        $todo->save();
        return response()->json($todo);
    }








    public function destroy($id)
    {
        Todo::findOrFail($id)->delete();
        return response()->noContent();
    }

}

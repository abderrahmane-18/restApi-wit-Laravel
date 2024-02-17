<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskCollection;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\QueryBuilder\QueryBuilder;


class TaskController extends Controller
{

    public function index(Request $request)
    {

        $tasks = QueryBuilder::for(Task::class)
    ->allowedFilters('is_done')
    ->defaultSort('created_at')// Tasks are returned to you based on which one was created first
    //->defaultSort('-created_at')// Tasks are returned to you based on which one was created  last
->allowedSorts('title','is_done','created_at')
//{{DOMAIN}}/api/tasks?sort=title
//{{DOMAIN}}/api/tasks?sort=title,is_done
//{{DOMAIN}}/api/tasks?sort=-title,-is_done
    ->paginate();
        //return response()->json(Task::all());
        return new TaskCollection($tasks);

    }
    public function  show(Request $request, Task $task)
    {
        return  new TaskResource($task);
    }

    public function  store(StoreTaskRequest $request)     
    {
       
        
    $request->validated($request->all());
    $task=Task::create([
       'user_id'=>Auth::user()->id,
       'title'=>$request->title
        
    ]);
    return new TaskResource($task);
    }
public function update(UpdateTaskRequest $request , Task $task)
{
$validated=$request->validated();
$task->update($validated);
return new TaskResource($task);

}
public function destroy(Request $request ,Task $task)
{
$task->delete();
return response()->noContent();
}
}

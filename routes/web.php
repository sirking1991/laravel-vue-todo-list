<?php
use App\Todo;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// returns list of todo not yet done
Route::get('/todo', function(){
    return response()->json(Todo::where('completed', 0)->get());
});

// save new todo
Route::post('/todo', function(Request $request){
    $validator = Validator::make($request->all(), ['description' => 'required|max:250']);
    if($validator->fails()){
        return response()->json($validator->errors(), 400);
    }

    $todo = new Todo;
    $todo->description = $request->description;
    $todo->save();

    return response()->json($todo);
});

// edit todo
Route::put('/todo/{id}', function(Request $request, $id){
    $validator = Validator::make($request->all(), ['description' => 'required|max:250']);
    if($validator->fails()){
        return response()->json($validator->errors(), 400);
    }

    $todo = Todo::find($id);
    
    // we id not find the id, return an error
    if (!$todo) return response('Not found', 404);

    $todo->description = $request->description;
    $todo->save();

    return response()->json($todo);
    
});

// mark todo as done
Route::put('/todo/{id}/mark-done', function(Request $request, $id){
    $todo = Todo::find($id);
    
    // we id not find the id, return an error
    if (!$todo) return response('Not found', 404);

    $todo->completed = 1;
    $todo->save();

    return response()->json($todo);    
});
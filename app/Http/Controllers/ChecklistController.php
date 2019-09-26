<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Todo;
use App\Checklist;
use Auth;
class ChecklistController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $todo = Auth::user()->todo()->get();
        return response()->json(['status' => 'success','result' => $todo]);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
        'todo' => 'required',
        'description' => 'required',
        'category' => 'required'
         ]);
        if(Auth::user()->todo()->Create($request->all())){
            return response()->json(['status' => 'success']);
        }else{
            return response()->json(['status' => 'fail']);
        }
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $todo = Checklist::where('id', $id)->get();
        if(count($todo) == 0){
            $response = array(
                'status' => '404',
                'error' => 'Not Found'
            );
            $status_code = 404;
        }else{
            $response = $todo;
            $status_code = 200;
            $attributes = $todo[0];
            $links = array(
                'self' => url('/api/checklists/'.$id)
            );
            $data = array(
                'type' => 'checklists',
                'id' => $id,
                'attributes' => $attributes,
                'links' => $links
            );
            $response = array(
                'data' => $data
            );
        }
        return response()->json($response, $status_code); 
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $todo = Checklist::where('id', $id)->get();
        return view('todo.edittodo',['todos' => $todo]);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
        'todo' => 'filled',
        'description' => 'filled',
        'category' => 'filled'
         ]);
        $todo = Todo::find($id);
        if($todo->fill($request->all())->save()){
           return response()->json(['status' => 'success']);
        }
        return response()->json(['status' => 'failed']);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(Todo::destroy($id)){
             return response()->json(['status' => 'success']);
        }
    }
}
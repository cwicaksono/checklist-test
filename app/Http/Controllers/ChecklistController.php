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
        // $content = json_decode($request->getContent());
        // $this->validate($request, [
        //     'data' => 'required'
        // ]);

        $content = json_decode($request->getContent());

        if($content){
            $attributes = $content->data->attributes;

            $data = array(
                'data' => $content,
                'attributes' => $content->data->attributes,
                'object_domain' => $attributes->object_domain,
                'object_id' => $attributes->object_id,
                'due' => $attributes->due,
                'urgency' => $attributes->urgency,
                'description' => $attributes->description,
                'items' => $attributes->items,
                'task_id' => $attributes->task_id
            );
            
            $rules = array(
                'data' => 'required',
                'attributes' => 'required',
                'object_domain' => 'required',
                'object_id' => 'required',
                'urgency' => 'required',
            );
            $validator = \Validator::make($data, $rules);

            if ($validator->fails()) {
                $error = $validator->errors();
                echo $error;
            }else{
                
                $checklist = new Checklist;
                $checklist->object_id = $attributes->object_id;
                $checklist->object_domain = $attributes->object_domain;
                $checklist->description = $attributes->description;
                $checklist->due = date('Y/m/d H:i:s', strtotime($attributes->due));
                $checklist->urgency = $attributes->urgency;
                $checklist->created_by = Auth::user()->id;
                $checklist->is_completed = false;
                $checklist->save();

                $status_code = 200;
                
                $data = array(
                    'type' => 'checklists',
                    'id' => $checklist->id,
                    'attributes' => array(
                        'object_domain' => $checklist->object_domain,
                        'object_id' => $checklist->object_id,
                        'task_id' => '123', // belum ada
                        'description' => $checklist->description,
                        'is_completed' => false,
                        'due' => $checklist->due,
                        'urgency' => $checklist->urgency,
                        'completed_at' => null,
                        'updated_by' => null,
                        'created_by' => $checklist->created_by,
                        'created_at' => $checklist->created_at,
                        'updated_at' => $checklist->updated_at
                    ),
                    'links' => array(
                        'self' => url("api/v1/checklists/".$checklist->id)
                    )
                );
                $response = array(
                    'data' => $data
                );
            }
        }else{
            $response = array(
                'status' => '500',
                'error' => 'Server Error'
            );
            $status_code = 404;
        }
        return response()->json($response, $status_code); 

        // print_r($validation);
        
        
        // $this->validate($request, [
        //     'todo' => 'required',
        //     'description' => 'required',
        //     'category' => 'required'
        // ]);
        // if(Auth::user()->todo()->Create($request->all())){
        //     return response()->json(['status' => 'success']);
        // }else{
        //     return response()->json(['status' => 'fail']);
        // }
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
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use Datatable;
use DB;
class TaskController extends Controller
{
    // function index() {




    //     return view('tasks.index');
    // }


    public function index()
    {
        if(request()->ajax()) {
    return datatables()->of(DB::table('tasks')->orderby('priority', 'desc'))
            ->addColumn('priority', function($row){
                if($row->priority == 1){
                    $response =  "Default";
                    $textClass = "text-default";
                    
                }elseif($row->priority == 2){
                    $response = "Important";
                    $textClass = "text-success";
                }else{

                    $response = "Very Important";
                    $textClass = "text-danger";
                }
                return '<p class="'.$textClass.'">'.$response.'</p>';
            })
        ->addColumn('action', function($row) {
            $editUrl = url("admin/subcategory/{$row->id}/edit");
            $deleteUrl = url("admin/subcategory/{$row->id}/delete");
            if($row->completed == '0'){
            
            $text = "Mark Completed";
            $class="btn-outline-success";

        }else{
            $text = "Mark Uncompleted";
            $class="btn-outline-danger";
        }

           return '<button class="btn btn-outline-success editform" data-id="'.$row->id.'" id="">Edit</button>
            
           <button class="btn btn-outline-danger deletetask" data-id="'.$row->id.'" id="">DELETE</button>
           
            '  ;
        })
        ->rawColumns(['action', 'priority'])
        ->addIndexColumn()
        ->make(true);
}
        return view('tasks.index');
    }




    public function savedata(request $request) {
        $validator = $request->validate([
            'title' => 'required|max:255',
            'description' => 'required',
            'due_date' => 'required|date',
            'priority' => 'required',
            // Add more validation rules as needed
        ]);
        // if ($validator->fails()) {
        //     return response()->json(['errors' => $validator->errors()], 422);
        // }
        $id = $request->id;
            $taskPost = Task::updateOrCreate(
                [
                    'id' => $id
                ],
                [
                'title' => $request->title,
                'description' => $request->description,
                'priority' => $request->priority,
                'due_date' => $request->due_date
    
        ]);
        

        

        return Response()->json($taskPost);
    }

    public function editdata($id){
        $where = array('id' => $id);
	    $task  = Task::where($where)->first();
	 
	    return Response()->json($task);
    }
    public function delete($id){
        $where = array('id' => $id);
	    $task  = Task::where($where)->delete();
	 
	    return Response()->json($task);
    }


}

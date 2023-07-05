<?php

namespace App\Http\Controllers\Seller;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Course;
use App\Module;
use Validator;
use Session;

class ModuleController extends Controller
{
    public function index($id){
        $course = Course::findOrFail($id);
        $modules = Module::where('course_id',$course->id)->get();
        return view('seller.module.index', compact('course', 'modules'));
    }
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required',
            'duration' => 'required'
        ];
      
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $errmsgs = $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }
        $module = new Module;
        $module->course_id = $request->course_id;
        $module->name = $request->name;
        $module->duration = $request->duration;
        $module->save();
      
        return response()->json(['success','Module Created']);
    }
    public function edit($id){
        $module = Module::find($id);
        return view('seller.module.edit', compact('module'));
    }
    public function update(Request $request, $id)
    {
        $rules = [
        'name' => 'required',
        'duration' => 'required'
        ];
    
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $errmsgs = $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }
    
        $module = Module::find($id);
        $module->name = $request->name;
        $module->duration = $request->duration;
        $module->save();
    
        return response()->json(['success','Module Edited']);
    }
    public function destroy(Request $request)
    {
        if ($request->status=='delete') {
            if ($request->ids) {
                foreach ($request->ids as $row) {
                    $id=base64_decode($row);
                    $module = Module::find($id);
                    if($module){
                        $module->delete();
                    }
                }
            }
        }
        return response()->json('Module Deleted');
    }
}

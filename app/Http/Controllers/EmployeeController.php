<?php

namespace App\Http\Controllers;

use App\Models\EmployeeModel;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
//use Illuminate\Auth\Events\Validator;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index(){

        $emp = EmployeeModel::all();

        return view('employee',compact('emp'));
    }

    public function store(Request $request){


        $validator = Validator::make($request->all(), [

            'name' => 'required | max:191',
            'phone' => 'required | max:191',
            'image' => 'required | image | mimes:jpeg,png,jpg | max:2048',

        ]);


        if ($validator->fails()){
            return response()->json([
                'status'=>400,
                'errors'=>$validator->messages()
            ]);
        }else{

            $employee = new EmployeeModel;
            $employee->name = $request->name;
            $employee->phone = $request->phone;
            $employee->timestamps = false;

            if ($request->hasFile('image')){
                $file = $request->file("image");
                $extension = $file->getClientOriginalExtension();
                $filename = time() . "." . $extension;

//                $file->storeAs('uploads/employee/', $filename);
//                $path = $request->file('image')->store('public/images');
                $file->move('uploads/employee/',$filename);
                $employee->image = $filename;

            }
            $employee->save();

            return response()->json([
                'status'=>200,
                'errors'=>"Employee Image Added"
            ]);


        }

    }

    public function edit($id){

        $emp = EmployeeModel::where('id','=',$id)->first();

        if ($emp){


            return response()->json([
                'status'=>200,
                'employee'=>$emp
            ]);
        }else{
            return response()->json([
                'status'=>400,
                'message'=>"Not Found"
            ]);
        }

    }

    public function update(Request $request,$id){


//        $validator = Validator::make($request->all(), [
//
//            'name' => 'required | max:191',
//            'phone' => 'required | max:191',
//            'image' => 'required | image | mimes:jpeg,png,jpg | max:2048',
//
//        ]);


//        if ($validator->fails()){
//            return response()->json([
//                'status'=>400,
//                'errors'=>$validator->messages()
//            ]);
////        }else{
///
///

//        dd($request->all());

            $employee = EmployeeModel::find($id);
            $employee->name = $request->name;
            $employee->phone = $request->phone;
            $employee->timestamps = false;

        if ($request->hasFile('image')){

//                delete img
                $path ='uploads/employee/'.$employee->image;
                if (File::exists($path)){
                    File::delete($path);
                }


//                store img
                $file = $request->file("image");
                $extension = $file->getClientOriginalExtension();
                $filename = time() . "." . $extension;
                $file->move('uploads/employee/',$filename);
                $employee->image = $filename;

//            }
            $employee->save();

            return response()->json([
                'status'=>200,
                'errors'=>"Employee Image Updated"
            ]);


        }

    }


    public function delete($id){

        $emp = EmployeeModel::where('id','=',$id)->first();

        if ($emp){

            //                delete img
            $path ='uploads/employee/'.$emp->image;
            if (File::exists($path)){
                File::delete($path);
            }

            $emp->delete();

            return response()->json([
                'status'=>200,
                'message'=>"Employee Deleted Successfully"
            ]);
        }else{
            return response()->json([
                'status'=>400,
                'message'=>"Not Found"
            ]);
        }

    }


}

<?php

namespace App\Http\Controllers;
use App\Models\Student;

use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function store(Request $request)
    {

    $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:students,email',
            'mobile' => 'required|string',
            'dob' => 'required|date',
            'age' => 'required|integer',
            'section' => 'required|string',
            'marks' => 'required|numeric',
            'status' => 'required|string',
        ]);

        $student = Student::create($request->all());
         return response()->json([
            'success' => true,
            'message' => 'Student Created Successfully!',
            'data' => $student
         ] , 201);
    }


    public function index()
    {
        $students = Student::all();

        return response()->json([
            'success' => 'true',
            'message' => 'Students Fetched Successfully!',
            'data' => $students
        ] , 200);
    }

    public function show($id)
    {
        $student = Student::find($id);
        
        if(!$student)
            {
                return response()->json([
                    'success' => false,
                    'message' => 'Student Not Found!'
                ] , 404 );
            }

            return response()->json([
                'success' => true,
                'message' => 'Student Fetched Successfully!',
                'data' => $student
            ] , 200);
    }

    public function update(Request $request , $id)
    {
        $student = Student::find($id);

        if(!$student)
            {
            return response()->json([
        'success' => 'false' ,
        'message' => 'Student Not Found!'] , 404);
            }

            $validateData = $request->validate([
                'name' => 'sometimes|required|string|max:255',
                'email' => 'sometimes|required|email|unique:students,email,' . $id ,
                'mobile' => 'sometimes|required|string',
                'dob' => 'sometimes|required|date',
                'age' => 'sometimes|required|integer',
                'section' => 'sometimes|required|string',
                'marks' => 'sometimes|required|numeric',
                'status' => 'sometimes|required|in:Pass,Fail,pass,Fail,0,1'
            ]);

            $student->update($validateData);

            return response()->json([
                'success' => 'true',
                'message' => 'Student Updated Successfully!',
                'data' => $student
            ] , 200);

    }

    public function destroy($id)
    {
          $student = Student::find($id);

          if(!$student)
            {
                return response()->json([
                    'success' => 'false',
                    'message' => 'Student Not Found!'
                ] , 404);
            }

            $student->delete();

            return response()->json([
                'success' => 'true',
                'message' => 'Sudent Deleted Successfully!'
            ] , 200);
    }
  
}

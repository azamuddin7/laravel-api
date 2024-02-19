<?php

namespace App\Http\Controllers\Api;

use App\Models\Shape;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Rules\ValidShape;

class ShapeController extends Controller
{
    public function index(Request $request)
    {
        $page = $request->query('page', 1);
        $perPage = $request->query('perPage', 10); // You can set the desired number of items per page here

        $shapes = Shape::paginate($perPage, ['*'], 'page', $page);

        if($shapes->count() > 0){
            // if record > 0, return success
            return response()->json($shapes);

        }else{
            // if record < 0, return not found
            return response()->json([
                'status' => 404,
                'data' => 'No Records Found.'
            ], 404);
        }
    }

    public function store(Request $request)
    {
        // validation request
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string'],
            'color' =>  ['required', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'],
            'shape' => ['required', 'string', new ValidShape],
            'timestamp' => ['required', 'date'],
        ]);

        if($validator->fails()){
            // if validation fails, will send 422 error message
            return response()->json([
                'status' => 422,
                'data' => $validator->messages()
            ], 422);
        }

        $data = $validator->validated();
        $shapeInfo = Shape::create($data);

        if($shapeInfo){
            // return record data and success 201
            return response()->json([
                'status' => 201,
                'data' => $shapeInfo
            ], 201);
        }else{
            return response()->json([
                'status' => 500,
                'data' => 'Something Went Wrong'
            ], 500);
        }
    }

    public function show($id)
    {
        $shape = Shape::find($id);

        if($shape){
            // if record exist, will return success
            return response()->json([
                'status' => 200,
                'data' => $shape
            ], 200);
        }else{
            // if record does not exist, will return error 404 message
            return response()->json([
                'status' => 404,
                'data' => 'No Record Found.'
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        // validation request. Add "sometimes" to allow validation only when the field is present
        $validator = Validator::make($request->all(), [
            'name' => ['sometimes', 'required', 'string'],
            'color' =>  ['sometimes', 'required', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'],
            'shape' => ['sometimes', 'required', 'string', new ValidShape],
            'timestamp' => ['sometimes', 'required', 'date'],
        ]);

        if($validator->fails()){
            // if validation fails, return 422 error message
            return response()->json([
                'status' => 422,
                'data' => $validator->messages()
            ], 422);
        }

        $data = $validator->validated();

        $shape = Shape::find($id);

        if($shape){
            $shape->update($data);

            return response()->json([
                'status' => 200,
                'data' => $shape
            ], 200);
        }else{
            return response()->json([
                'status' => 404,
                'data' => 'No Record Found.'
            ], 404);
        }
    }

    public function destroy($id)
    {
        $shape = Shape::find($id);

        if($shape){
            // if record exist, will delete the record and return success
            $shape->delete();
            return response()->json([
                'status' => 204,
                'data' => 'Sucessfully delete shape.'
            ], 204);
        }else{
            // if record does not exist, will return 404 error message
            return response()->json([
                'status' => 404,
                'data' => 'No Record Found.'
            ], 404);
        }
    }
}

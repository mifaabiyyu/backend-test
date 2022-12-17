<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $category       = Category::all();

        $response   = [
            'message'   => 'Data fetched successfully !',
            'data'      => $category      
        ];

        return response()->json($response, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required|unique:categories,name'
        ]);

        $create     = Category::create([
            'name'  => $request->name,
            'slug'  => Str::slug($request->name),
        ]);

        $response   = [
            'message'   => 'Data created successfully !',
            'data'      => $create 
        ];

        return response()->json($response, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $findData   = Category::find($id);

        if (!$findData) {
            return response()->json(['message'  => 'Data not found !'], 422);
        }

        $response   = [
            'message'   => 'Data fetched successfully !',
            'data'      => $findData
        ];

        return response()->json($response, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        $findData   = Category::find($id);

        if (!$findData) {
            return response()->json(['message'  => 'Data not found !'], 422);
        }

        $request->validate([
            'name'  => 'required|unique:categories,name,' . $id
        ]);

        $findData->update([
            'name'  => $request->name,
            'slug'  => Str::slug($request->name),
        ]);

        $updated   = Category::find($id);

        $response   = [
            'message'   => 'Data updated successfully !',
            'data'      => $updated 
        ];

        return response()->json($response, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $findData   = Category::find($id);

        if (!$findData) {
            return response()->json(['message'  => 'Data not found !'], 422);
        }

        $checkPost  = Post::where('category_id', $id)->first();

        if ($checkPost) {
            return response()->json(['message'  => 'Cannot delete, Category have Post !'], 422);
        }

        $findData->delete();

        $response   = [
            'message'   => 'Data deleted successfully !',
        ];

        return response()->json($response, 200);
    }

    public function get_post()
    {
        $category       = Category::with('get_post')->get();

        $response   = [
            'message'   => 'Category created successfully !',
            'data'      => $category      
        ];

        return response()->json($response, 200);
    }
}

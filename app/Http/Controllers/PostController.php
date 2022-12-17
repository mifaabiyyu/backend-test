<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (auth()->user()->hasRole(['admin', 'manager'])) {
            $getData = Post::with('get_category:name,slug')->get();
        } else {
            $getData = Post::with('get_category:name,slug')->where('user_id', auth()->user()->id)->get();
        }

        $response   = [
            'message'   => 'Data fetched successfully !',
            'data'      => $getData
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
            'title'     => 'required',
            'content'   => 'required',
            'category'   => 'required',
        ]);

        $photo = null;

        if($request->hasFile('photo'))
        {
            $photo = date("Ymd").time().rand().'.'.$request->photo->extension();
            $request->photo->move(public_path('images'), $photo);
        }

        $create     = Post::create([
            'title'         => $request->title,
            'content'       => $request->content,
            'category_id'   => $request->category,
            'slug'          => Str::slug($request->title),
            'photo'         => $photo,
            'user_id'       => auth()->user()->id
        ]);

        $response   = [
            'message'   => 'Post created successfully !',
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
        $findData   = Post::with('get_category:name,slug')->find($id);

        if (!$findData) {
            return response()->json(['message'  => 'Data not found !'], 422);
        }

        if (auth()->user()->hasRole('user') && $findData->user_id != auth()->user()->id) {
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
        $findData   = Post::find($id);

        if (!$findData) {
            return response()->json(['message'  => 'Data not found !'], 422);
        }

        $request->validate([
            'title'     => 'required',
            'content'   => 'required',
            'category'  => 'required',
        ]);

        if (auth()->user()->hasRole('user') && $findData->user_id != auth()->user()->id) {
            return response()->json(['message'  => 'Data not found !'], 422);
        }

        $photo = $findData->photo;

        if($request->hasFile('photo'))
        {
            if ($findData->photo != null && file_exists( public_path() . 'images/' . $findData->photo)) {
                unlink("images/" . $findData->photo);
            }

            $photo = date("Ymd").time().rand().'.'.$request->photo->extension();
            $request->photo->move(public_path('images'), $photo);
        }

        $findData->update([
            'title'         => $request->title,
            'category_id'   => $request->category,
            'slug'          => Str::slug($request->title),
            'content'       => $request->content,
            'photo'         => $photo,
        ]);

        $updated    = Post::find($id);

        $response   = [
            'message'   => 'Post updated successfully !',
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
        $findData   = Post::find($id);

        if (!$findData) {
            return response()->json(['message'  => 'Data not found !'], 422);
        }

        if (auth()->user()->hasRole('user') && $findData->user_id != auth()->user()->id) {
            return response()->json(['message'  => 'Data not found !'], 422);
        }

        $findData->delete();

        $response = [
            'message'   => 'User deleted successfully !',
        ];

        return response()->json($response, 200);
    }
}

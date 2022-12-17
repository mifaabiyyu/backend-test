<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $getData    = User::all();

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
        $attribute = $request->validate([
            'email'     => 'required|email|unique:users,email',
            'phone'     => 'required|unique:users,phone',
            'name'      => 'required|string',
            'password'  => 'required|min:8|confirmed',
            'role'      => 'required',
            'password_confirmation' => 'required',
        ]);

        $attribute['password']  = bcrypt($request->password);
        $attribute['status']     = false;

        $user = User::create($attribute);

        $user->assignRole($request->role);        

        $response = [
            'message'   => 'User created successfully !',
            'user'      => $user,
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
        $findData   = User::find($id);

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
        $findData   = User::find($id);

        if (!$findData) {
            return response()->json(['message'  => 'Data not found !'], 422);
        }

        $request->validate([
            'email'     => 'required|email|unique:users,email,' . $id,
            'phone'     => 'required|unique:users,phone,' . $id,
            'name'      => 'required|string',
            'password'  => 'required|min:8|confirmed',
            'role'      => 'required',
            'password_confirmation' => 'required',
        ]);

        $findData->update([
            'email'     => $request->email,
            'phone'     => $request->phone,
            'name'      => $request->name,
            'password'  => bcrypt($request->password),
        ]);

        $findData->assignRole($request->role);  
        
        $updated    = User::find($id);

        $response = [
            'message'   => 'User created successfully !',
            'user'      => $updated,
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
        $findData   = User::find($id);

        if (!$findData) {
            return response()->json(['message'  => 'Data not found !'], 422);
        }

        if (auth()->user()->id == $id) {
            return response()->json(['message'  => 'Cannot delete your user !'], 422);
        }

        $checkUser  = Post::where('user_id', $id)->get();

        $checkUser->each->delete();

        $response = [
            'message'   => 'User deleted successfully !',
        ];

        return response()->json($response, 200);
    }
}

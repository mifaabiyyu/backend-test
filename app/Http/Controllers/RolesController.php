<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Models\ModelHasRole;

class RolesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = Role::all();

        $response   = [
            'message'   => 'Data fetched successfully !',
            'data'      => $roles      
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
            'name' => 'required|unique:roles,name',
        ]);

        $create = Role::create([
            'name'  => $request->name,
            'guard_name'    => 'web'
        ]);

        $response = [
            'message'   => 'Roles successfully created',
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
        $findData = Role::find($id);

        if (!$findData) {
            return response()->json(['message' => 'Data not found !'], 422);
        }

        $response = [
            'message'           => 'Data fetched successfully !',
            'data'              => $findData,
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
        $findData = Role::find($id);

        if (!$findData) {
            return response()->json(['message' => 'Data not found !'], 422);
        }

        $request->validate([
            'name' => 'required|unique:roles,name,' . $id
        ]);

        $findData->name = $request->name;
        $findData->save();

        $updated = Role::find($id);

        $response = [
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
        $findData = Role::find($id);

        if (!$findData) {
            return response()->json(['message' => 'Data not found !'], 422);
        }

        $findUserRole = ModelHasRole::where('role_id', $id)->first();

        if ($findUserRole) {
            return response()->json(['message' => 'Role has used in User Role !'], 422);
        }

        $findData->delete();

        $response = [
            'message' => 'Roles deleted successfully',
        ];

        return response()->json($response, 200);
    }
}

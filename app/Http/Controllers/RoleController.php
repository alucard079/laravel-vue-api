<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Role\IndexRequest;
use App\Http\Requests\Role\StoreRequest;
use App\Http\Requests\Role\UpdateRequest;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(IndexRequest $request)
    {
        $roles = Role::query()
        ->with('permissions')
        ->paginate($request->perPage ? $request->perPage : 5)
        ->withQueryString();
        return response()->json($roles);  
    }

    /**
     * Display a listing of the roles.
     *
     * @return \Illuminate\Http\Response
     */
    public function allRole()
    {
        $roles = Role::select('id as value','name')->get();
        return response()->json($roles);  
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        $data = $request->validated();
        $role = Role::create([
            'name' => $data['name']
        ]);

        $role->givePermissionTo($data['permissions']);
        
        if($role) {
            return response()->json([
                'message' => 'Role Created Successfully!',
            ], 200);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Role $role)
    {
        return response()->json($role);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Role $role)
    {
        if($role) {
            $role->selected_permissions = $role->permissions->pluck('name');
        }
        return response()->json($role);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, Role $role)
    {
        $data = $request->validated();
        $role->update([
            'name' => $data['name']
        ]);
        $role->syncPermissions($data['permissions']);
        if($role) {
            return response()->json([
                'message' => 'Role Updated Successfully!',
            ], 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role)
    {
        $role->delete();
        return response()->json([
            'message'=>'Role Deleted Successfully!'
        ]);
    }
}

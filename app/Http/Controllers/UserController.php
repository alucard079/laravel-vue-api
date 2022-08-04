<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\User\IndexRequest;
use App\Http\Requests\User\StoreRequest;
use App\Http\Requests\User\UpdateRequest;
use App\Notifications\VerifyUserNotification;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Notification;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(IndexRequest $request)
    {
        $users = User::query()
        ->paginate($request->perPage ? $request->perPage : 5)
        ->withQueryString();
        return response()->json($users); 
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
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
        $role = Role::findOrFail($data['role']);

        if($user && $role) {
            $user->assignRole($role);

            $details = [
                'greeting' => 'Hi, ' . $user->firstname,
                'body' => 'We received your registration to our system.',
                'below' =>
                    'Click the button, to verify your email',
                'action_text' => 'Click to Verify',
                'action_url' => url('/verify/'.$user->email)
            ];
            $user->notify(new VerifyUserNotification($details));
            return response()->json([
                'message' => 'User Created Successfully!',
            ], 200);
            
            return response()->json([
                'message' => 'User Created Successfully!',
            ], 200);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return response()->json($user);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $user->role = $user->roles->pluck('id')[0];
        return response()->json($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, User $user)
    {
        $data = $request->validated();
        $user->update([
            'name' => $data['name'],
            'email' => $data['email'],
        ]);
        $role = Role::findOrFail($data['role']);
        if($role && $user) {
            $user->syncRoles($role);
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
    public function destroy(User $user)
    {
        $user->delete();
        return response()->json([
            'message'=>'User Deleted Successfully!'
        ]);
    }
}

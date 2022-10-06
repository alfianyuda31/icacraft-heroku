<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Untuk paginasi
        $user = User::paginate(1);
        
        return view('users.index',[
            'user' => $user
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    // public function store(UserRequest $request)
    // {
    //     //mengambil data user dari validasi di UserRequest
    //     $data = $request->all();

    //     //upload foto profile user
    //     // if ($request->file('profile_photo_Path')) {
    //     // }
        
    //     $data['profile_photo_path'] = $request->file('profile_photo_path')->store('assets/user', 'public');
    //      //$data['password'] = Hash::make($request->pasword);
    //     // $data['current_current_team_id'] = 1;
    //     //pembuatan data user
    //     User::create($data);
        
    //     return redirect()->route('users.index');
    // }
    public function store(Request $request)
    {

        $this->validate($request, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => 'required|min:6|confirmed',
            'password_confirmation' => 'required',
            'address' => ['required', 'string'],
            'roles' => ['required', 'string', 'max:255', 'in:USER,ADMIN'],
            'houseNumber' => ['required', 'string', 'max:255'],
            'phoneNumber' => ['required', 'string', 'max:255'],
            'postalCode' => ['required', 'string', 'max:255'],
        ]);

        $data = $request->all();
        if ($request->file('profile_photo_path')) 
        {
            $data['profile_photo_path'] = $request->file('profile_photo_path')->store('assets/user', 'public');
        }
        $data['password'] = Hash::make($request->password);
        $data['current_team_id'] = 1;

        User::create($data);

        return redirect()->route('users.index')->with('success', 'User Berhasil Ditambahkan!!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        return view('users.edit', [
            'item' => $user
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, User $user)
    {
        $data = $request->all();

        if ($request->file('profile_photo_path')) 
        {
            $data['profile_photo_path'] = $request->file('profile_photo_path')->store('assets/user', 'public');
        }

        $data['password'] = Hash::make($request->password);
        $data['current_team_id'] = 1;
        $user->update($data);

        return redirect()->route('users.index');
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

        return redirect()->route('users.index');
    }
}

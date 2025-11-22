<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Outlet;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with('outlet')->get();
        $outlets = Outlet::all();

        return view('dashboard.user.index', compact('users', 'outlets'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $outlets = Outlet::all();

        return view('dashboard.user.create', compact('outlets'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:100',
            'username' => 'required|max:100|unique:users,username',
            'password' => 'required|min:8|max:150|confirmed',
            'role' => 'required|in:driver,operation,administrator',
            'outlet_id' => 'nullable|numeric',
        ]);

        $validatedData['uuid'] = Str::uuid()->toString();
        $validatedData['password'] = Hash::make($validatedData['password']);

        User::create($validatedData);

        return redirect('/dashboard/user')->with('success', 'User berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $outlets = Outlet::all();

        return view('dashboard.user.edit', compact('user', 'outlets'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $rules = [
            'name' => 'required|max:100',
            'username' => 'required|max:100',
            'role' => 'required|in:driver,operation,administrator',
            'outlet_id' => 'nullable|numeric',
        ];

        if ($request->username != $user->username) {
            $rules['username'] = 'required|max:100|unique:users,username';
        }

        $validatedData = $request->validate($rules);

        $user->update($validatedData);

        return redirect('/dashboard/user')->with('success', 'User berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        User::destroy($user->id);

        return redirect('/dashboard/user')->with('success', 'User berhasil dihapus');
    }

    public function resetPassword(User $user, Request $request)
    {
        $validatedData = $request->validate([
            'password' => 'required|min:8|confirmed',
        ]);

        $user->update(['password' => Hash::make($validatedData['password'])]);

        return redirect('/dashboard/user')->with('success', 'Password berhasil direset');
    }
}

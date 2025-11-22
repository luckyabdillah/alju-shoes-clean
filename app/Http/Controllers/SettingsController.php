<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function edit()
    {
        return view('dashboard.settings.edit');
    }

    public function update(Request $request)
    {
        $rules = [
            'name' => 'required|max:100',
            'username' => 'required|max:100',
        ];

        if ($request->username != auth()->user()->username) {
            $rules['username'] = 'required|max:100|unique:users,username';
        }

        $validatedData = $request->validate($rules);

        User::whereId(auth()->user()->id)->update($validatedData);

        return redirect('/dashboard/settings')->with('success', 'Data berhasil diupdate');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        if(!Hash::check($request->old_password, auth()->user()->password)){
            return back()->with('failed', 'Password lama tidak sesuai');
        }

        User::whereId(auth()->user()->id)->update([
            'password' => Hash::make($request->new_password)
        ]);

        return redirect('/dashboard/settings')->with('success', 'Password berhasil diganti');
    }
}

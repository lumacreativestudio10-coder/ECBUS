<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Services\ActivityLogger;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('admin.profile');
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $rules = [
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone_number' => 'nullable|digits_between:7,15',
        ];

        if ($request->filled('password')) {
            $rules['password'] = 'required|string|min:8|confirmed';
        }

        $validatedData = $request->validate($rules);

        $user->name = $validatedData['name'];
        $user->email = $validatedData['email'];
        $user->phone_number = $validatedData['phone_number'];

        if ($request->filled('password')) {
            $user->password = Hash::make($validatedData['password']);
        }

        $user->save();

        ActivityLogger::log('Profile Updated', 'User updated their personal profile settings.');

        return back()->with('success', 'Profile updated successfully.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\BusCompany;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        // Base query - exclude Driver and Conductor from this module as per requirements (IDs 4 and 5)
        $query = User::with(['company', 'role'])->whereNotIn('role_id', [4, 5]);

        // Company Admin can only see their own staff
        if ($user->isCompanyAdmin()) {
            $query->where('company_id', $user->company_id);
            // Additionally, they can't see Super Admin (ID 1)
            $query->where('role_id', '!=', 1);
        }

        // Apply filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone_number', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role_id')) {
            $query->where('role_id', $request->role_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('company_id') && $user->isSuperAdmin()) {
            $query->where('company_id', $request->company_id);
        }

        $users = $query->latest()->paginate(10);
        $companies = $user->isSuperAdmin() ? BusCompany::all() : collect();

        return view('admin.users', compact('users', 'companies'));
    }

    public function store(Request $request)
    {
        $currentUser = auth()->user();

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'phone_number' => 'nullable|digits_between:7,15',
            'role_id' => ['required', Rule::in([2, 3])],
        ];

        // Super admin must select a company for the user
        if ($currentUser->isSuperAdmin()) {
            $rules['company_id'] = 'required|exists:bus_companies,id';
        }

        $request->validate($rules);

        $companyId = $currentUser->isSuperAdmin() ? $request->company_id : $currentUser->company_id;

        // Company Admin can only create Staff
        $roleId = $request->role_id;
        if ($currentUser->isCompanyAdmin()) {
            $roleId = 3; // Force to staff if company admin tries to spoof
        }

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone_number' => $request->phone_number,
            'role_id' => $roleId,
            'company_id' => $companyId,
            'status' => 1, // Default active
        ]);

        return redirect()->route('admin.users')->with('success', 'User created successfully.');
    }

    public function update(Request $request, User $user)
    {
        $currentUser = auth()->user();

        // Authorization check
        if ($currentUser->isCompanyAdmin() && $user->company_id !== $currentUser->company_id) {
            abort(403, 'Unauthorized action.');
        }

        $rules = [
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone_number' => 'nullable|digits_between:7,15',
            'role_id' => ['required', Rule::in([2, 3])],
        ];

        if ($currentUser->isSuperAdmin()) {
            $rules['company_id'] = 'required|exists:bus_companies,id';
        }

        if ($request->filled('password')) {
            $rules['password'] = 'string|min:8';
        }

        $request->validate($rules);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
        ];

        // Only super admin can change company
        if ($currentUser->isSuperAdmin()) {
            $data['company_id'] = $request->company_id;
            $data['role_id'] = $request->role_id;
        } else {
            // Company Admin can only edit staff roles to staff
            $data['role_id'] = 3;
        }

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.users')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        $currentUser = auth()->user();

        // Authorization check
        if ($currentUser->isCompanyAdmin() && $user->company_id !== $currentUser->company_id) {
            abort(403, 'Unauthorized action.');
        }
        
        if ($user->id === $currentUser->id) {
             return redirect()->back()->with('error', 'You cannot delete yourself.');
        }

        $user->delete();

        return redirect()->route('admin.users')->with('success', 'User deleted successfully.');
    }

    public function updateStatus(Request $request, User $user)
    {
        $currentUser = auth()->user();

        // Authorization check
        if ($currentUser->isCompanyAdmin() && $user->company_id !== $currentUser->company_id) {
            abort(403, 'Unauthorized action.');
        }

        if ($user->id === $currentUser->id) {
             return redirect()->back()->with('error', 'You cannot deactivate yourself.');
        }

        $user->update(['status' => $request->status]);

        return redirect()->route('admin.users')->with('success', 'User status updated successfully.');
    }
}

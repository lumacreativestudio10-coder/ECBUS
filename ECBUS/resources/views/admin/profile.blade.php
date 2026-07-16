@extends('layouts.admin')

@section('title', 'My Profile')
@section('header', 'My Profile')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Success Message -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl relative mb-6">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-8">
            <h2 class="text-xl font-bold text-dark-maroon mb-6">Update Profile Information</h2>
            
            <form action="{{ route(auth()->user()->getRolePrefix().'.profile.update') }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Full Name</label>
                        <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" required class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-gold focus:border-transparent @error('name') border-red-500 @enderror">
                        @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Email Address</label>
                        <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" required class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-gold focus:border-transparent @error('email') border-red-500 @enderror">
                        @error('email') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Phone Number</label>
                        <input type="text" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '')" name="phone_number" value="{{ old('phone_number', auth()->user()->phone_number) }}" class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-gold focus:border-transparent @error('phone_number') border-red-500 @enderror">
                        @error('phone_number') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Current Role</label>
                        <input type="text" value="{{ auth()->user()->role->name }}" disabled class="w-full px-4 py-2 border border-gray-200 bg-gray-50 text-gray-500 rounded-xl cursor-not-allowed">
                    </div>
                </div>

                <hr class="my-8 border-gray-200">

                <h2 class="text-xl font-bold text-dark-maroon mb-6">Change Password</h2>
                <p class="text-sm text-gray-500 mb-4">Leave the password fields blank if you do not wish to change your password.</p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">New Password</label>
                        <input type="password" name="password" class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-gold focus:border-transparent @error('password') border-red-500 @enderror">
                        @error('password') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Confirm New Password</label>
                        <input type="password" name="password_confirmation" class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-gold focus:border-transparent">
                    </div>
                </div>

                <div class="flex justify-end mt-8">
                    <button type="submit" class="bg-primary-maroon text-white px-8 py-3 rounded-xl font-bold hover:bg-dark-maroon transition shadow-md">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

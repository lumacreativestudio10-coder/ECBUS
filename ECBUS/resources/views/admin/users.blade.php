@extends('layouts.admin')

@section('title', 'Manage Users')
@section('header', 'Manage Users')

@section('content')
<div x-data="userManagement()" x-init="initData()">
    <!-- Toast Notifications -->
    @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" class="fixed top-4 right-4 z-50 bg-green-500 text-white px-6 py-3 rounded-xl shadow-lg flex items-center">
            <i data-lucide="check-circle" class="w-5 h-5 mr-3"></i>
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" class="fixed top-4 right-4 z-50 bg-red-500 text-white px-6 py-3 rounded-xl shadow-lg flex items-center">
            <i data-lucide="alert-circle" class="w-5 h-5 mr-3"></i>
            {{ session('error') }}
        </div>
    @endif

    <div class="mb-6 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-dark-text">System Users</h2>
            <p class="text-sm text-gray-500">Manage office users, staff and company admins.</p>
        </div>
        <div>
            <button @click="openModal()" class="bg-primary-maroon text-white px-5 py-2.5 rounded-xl font-bold hover:bg-dark-maroon transition shadow-md flex items-center">
                <i data-lucide="user-plus" class="w-5 h-5 mr-2"></i> Add New User
            </button>
        </div>
    </div>

    <!-- Filters & Search -->
    <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 mb-6">
        <form method="GET" action="{{ route(auth()->user()->getRolePrefix().'.users') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Search</label>
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Name, Email, Phone..." class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-gold focus:border-transparent">
                    <i data-lucide="search" class="w-4 h-4 text-gray-400 absolute left-3 top-3"></i>
                </div>
            </div>
            
            @if(auth()->user()->isSuperAdmin())
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Company</label>
                <select name="company_id" class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-gold">
                    <option value="">All Companies</option>
                    @foreach($companies as $company)
                        <option value="{{ $company->id }}" {{ request('company_id') == $company->id ? 'selected' : '' }}>{{ $company->company_name }}</option>
                    @endforeach
                </select>
            </div>
            @endif

            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Role</label>
                <select name="role_id" class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-gold">
                    <option value="">All Roles</option>
                    <option value="2" {{ request('role_id') == 2 ? 'selected' : '' }}>Company Admin</option>
                    <option value="3" {{ request('role_id') == 3 ? 'selected' : '' }}>Staff</option>
                    @if(auth()->user()->isSuperAdmin())
                        <option value="1" {{ request('role_id') == 1 ? 'selected' : '' }}>Super Admin</option>
                    @endif
                </select>
            </div>

            <div class="flex items-end space-x-2">
                <button type="submit" class="bg-dark-text text-white px-5 py-2 rounded-xl font-bold hover:bg-black transition w-full md:w-auto">
                    Filter
                </button>
                <a href="{{ route(auth()->user()->getRolePrefix().'.users') }}" class="bg-gray-100 text-gray-600 px-5 py-2 rounded-xl font-bold hover:bg-gray-200 transition text-center w-full md:w-auto">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Users Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left whitespace-nowrap">
                <thead>
                    <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider font-bold border-b border-gray-100">
                        <th class="px-6 py-4">User</th>
                        <th class="px-6 py-4">Role</th>
                        <th class="px-6 py-4">Company</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm">
                    @forelse($users as $user)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded-full bg-primary-gold/20 flex items-center justify-center text-primary-gold font-bold text-lg mr-3">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-dark-text">{{ $user->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $user->email }}</p>
                                        @if($user->phone_number)
                                            <p class="text-xs text-gray-400 mt-0.5"><i data-lucide="phone" class="w-3 h-3 inline"></i> {{ $user->phone_number }}</p>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full text-xs font-bold 
                                    @if($user->isSuperAdmin()) bg-purple-100 text-purple-700
                                    @elseif($user->isCompanyAdmin()) bg-blue-100 text-blue-700
                                    @else bg-gray-100 text-gray-700 @endif">
                                    {{ $user->role->name ?? 'Unknown' }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @if($user->company)
                                    <span class="font-medium text-dark-text">{{ $user->company->company_name }}</span>
                                @else
                                    <span class="text-gray-400 italic">No Company (System)</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <form action="{{ route(auth()->user()->getRolePrefix().'.users.updateStatus', $user) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="{{ $user->status ? 0 : 1 }}">
                                    <button type="submit" class="relative inline-flex items-center h-6 rounded-full w-11 transition-colors focus:outline-none {{ $user->status ? 'bg-green-500' : 'bg-gray-300' }}" @if($user->id === auth()->id()) disabled @endif>
                                        <span class="inline-block w-4 h-4 transform bg-white rounded-full transition-transform {{ $user->status ? 'translate-x-6' : 'translate-x-1' }}"></span>
                                    </button>
                                </form>
                            </td>
                            <td class="px-6 py-4 text-right space-x-2">
                                <button @click="openModal({{ json_encode($user) }})" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Edit">
                                    <i data-lucide="edit-2" class="w-4 h-4"></i>
                                </button>
                                @if($user->id !== auth()->id())
                                <button onclick="confirmDelete('{{ route(auth()->user()->getRolePrefix().'.users.destroy', $user) }}')" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition" title="Delete">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                <i data-lucide="users" class="w-12 h-12 mx-auto text-gray-300 mb-3"></i>
                                <p class="font-medium">No users found.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($users->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $users->links() }}
            </div>
        @endif
    </div>

    <!-- Slide-over Modal -->
    <div x-show="isModalOpen" class="fixed inset-0 z-50 overflow-hidden" style="display: none;">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm transition-opacity" @click="closeModal()" x-transition.opacity></div>
        <div class="fixed inset-y-0 right-0 max-w-md w-full flex">
            <div class="w-full h-full bg-white shadow-2xl flex flex-col transform transition-transform" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="translate-x-full"
                 x-transition:enter-end="translate-x-0"
                 x-transition:leave="transition ease-in duration-300"
                 x-transition:leave-start="translate-x-0"
                 x-transition:leave-end="translate-x-full">
                
                <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                    <h3 class="text-lg font-bold text-dark-text" x-text="isEdit ? 'Edit User' : 'Add New User'"></h3>
                    <button @click="closeModal()" class="text-gray-400 hover:text-gray-600 transition">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>

                <div class="flex-1 overflow-y-auto p-6">
                    <form :action="formAction" method="POST" id="userForm">
                        @csrf
                        <template x-if="isEdit">
                            <input type="hidden" name="_method" value="PUT">
                        </template>
                        <input type="hidden" name="id" x-model="form.id">

                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1">Full Name *</label>
                                <input type="text" name="name" x-model="form.name" required class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-gold focus:border-transparent @error('name') border-red-500 @enderror">
                                @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1">Email Address *</label>
                                <input type="email" name="email" x-model="form.email" required class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-gold focus:border-transparent @error('email') border-red-500 @enderror">
                                @error('email') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1">Phone Number</label>
                                <input type="text" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '')" name="phone_number" x-model="form.phone_number" class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-gold focus:border-transparent @error('phone_number') border-red-500 @enderror">
                                @error('phone_number') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div x-show="isEdit">
                                <label class="block text-sm font-bold text-gray-700 mb-1">Password</label>
                                <input type="password" name="password" class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-gold focus:border-transparent @error('password') border-red-500 @enderror" placeholder="Leave blank to keep current password">
                                @error('password') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                            </div>

                            @if(auth()->user()->isSuperAdmin())
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-1">Company *</label>
                                    <select name="company_id" x-model="form.company_id" required class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-gold @error('company_id') border-red-500 @enderror">
                                        <option value="">Select Company</option>
                                        @foreach($companies as $company)
                                            <option value="{{ $company->id }}">{{ $company->company_name }}</option>
                                        @endforeach
                                    </select>
                                    @error('company_id') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-1">Role *</label>
                                    <select name="role_id" x-model="form.role_id" required class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-gold @error('role_id') border-red-500 @enderror">
                                        <option value="">Select Role</option>
                                        <option value="2">Company Admin</option>
                                        <option value="3">Staff</option>
                                    </select>
                                    @error('role_id') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                                </div>
                            @else
                                <input type="hidden" name="role_id" value="3"> <!-- Company Admin creates Staff -->
                            @endif
                        </div>
                    </form>

                    <!-- Validation Errors -->
                    @if ($errors->any())
                        <div class="mt-4 p-4 bg-red-50 text-red-600 rounded-xl text-sm border border-red-100">
                            <ul class="list-disc pl-5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>

                <div class="p-6 border-t border-gray-100 bg-gray-50 flex space-x-3">
                    <button @click="closeModal()" type="button" class="flex-1 bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded-xl font-bold hover:bg-gray-50 transition">
                        Cancel
                    </button>
                    <button type="submit" form="userForm" class="flex-1 bg-primary-maroon text-white px-4 py-2 rounded-xl font-bold hover:bg-dark-maroon transition shadow-md">
                        Save User
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Hidden Form for Delete -->
    <form id="deleteForm" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function userManagement() {
        return {
            isModalOpen: false,
            isEdit: false,
            formAction: '{{ route(auth()->user()->getRolePrefix().'.users.store') }}',
            form: {
                id: null,
                name: '',
                email: '',
                phone_number: '',
                company_id: '',
                role_id: ''
            },
            initData() {
                // If there are validation errors, reopen the modal and preserve values
                @if($errors->any())
                    this.isModalOpen = true;
                    this.isEdit = {{ old('_method') == 'PUT' ? 'true' : 'false' }};
                    this.formAction = this.isEdit && '{{ old('id') }}' 
                        ? `/admin/users/{{ old('id') }}` 
                        : '{{ route(auth()->user()->getRolePrefix().'.users.store') }}';
                    
                    this.form.id = '{{ old('id') }}';
                    this.form.name = @json(old('name'));
                    this.form.email = @json(old('email'));
                    this.form.phone_number = @json(old('phone_number'));
                    this.form.company_id = '{{ old('company_id') }}';
                    this.form.role_id = '{{ old('role_id') }}';
                @endif
            },
            openModal(user = null) {
                if (user) {
                    this.isEdit = true;
                    this.formAction = `/admin/users/${user.id}`;
                    this.form.id = user.id;
                    this.form.name = user.name;
                    this.form.email = user.email;
                    this.form.phone_number = user.phone_number || '';
                    this.form.company_id = user.company_id || '';
                    this.form.role_id = user.role_id || '';
                } else {
                    this.isEdit = false;
                    this.formAction = '{{ route(auth()->user()->getRolePrefix().'.users.store') }}';
                    this.form = {
                        id: null, name: '', email: '', phone_number: '', company_id: '', role_id: ''
                    };
                }
                this.isModalOpen = true;
            },
            closeModal() {
                this.isModalOpen = false;
            }
        }
    }

    function confirmDelete(url) {
        Swal.fire({
            title: 'Are you sure?',
            text: "This user will be permanently deleted!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#7A0016',
            cancelButtonColor: '#6B7280',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                let form = document.getElementById('deleteForm');
                form.action = url;
                form.submit();
            }
        });
    }
</script>
@endpush

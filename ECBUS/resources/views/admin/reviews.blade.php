@extends('layouts.admin')

@section('title', 'Manage Reviews')
@section('header', 'Manage Customer Reviews')

@section('content')

@if(session('success'))
<div class="mb-6 bg-green-100 border border-green-200 text-green-700 px-4 py-3 rounded-xl relative" role="alert">
    <strong class="font-bold">Success!</strong>
    <span class="block sm:inline">{{ session('success') }}</span>
</div>
@endif

@if($errors->any())
<div class="mb-6 bg-red-100 border border-red-200 text-red-700 px-4 py-3 rounded-xl relative" role="alert">
    <strong class="font-bold">Error!</strong>
    <ul class="list-disc pl-5 mt-2">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div x-data="reviewManager()">

    <!-- Header Actions & Search -->
    <div class="flex justify-between items-center mb-6 gap-4">
        <div class="relative w-full max-w-md">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i data-lucide="search" class="w-4 h-4 text-gray-400"></i>
            </div>
            <input type="text" x-model="searchQuery" placeholder="Search by description..." class="w-full pl-10 pr-4 py-2.5 text-sm border border-gray-200 rounded-lg focus:border-primary-maroon focus:ring-1 focus:ring-primary-maroon outline-none transition">
        </div>
        <button @click="openAdd()" class="bg-primary-maroon hover:bg-dark-maroon text-white font-bold rounded-lg px-4 py-2.5 text-sm transition flex items-center shadow-md whitespace-nowrap">
            <i data-lucide="plus" class="w-4 h-4 mr-2"></i> Add Review Image
        </button>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex flex-col">
        <div class="px-6 py-4 border-b border-gray-100 bg-white">
            <h3 class="font-extrabold text-lg text-dark-text">All Reviews</h3>
        </div>
        
        <div class="overflow-x-auto flex-grow">
            <table class="w-full text-sm text-left">
                <thead>
                    <tr class="bg-gray-50 text-gray-400 text-[10px] uppercase font-bold border-b border-gray-100">
                        <th class="px-6 py-4">Image</th>
                        <th class="px-4 py-4 w-1/2">Description</th>
                        <th class="px-4 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($reviews as $review)
                    <tr class="hover:bg-gray-50/50 transition group" x-show="matchesSearch('{{ addslashes($review->description ?? '') }}')">
                        <td class="px-6 py-4">
                            <div class="w-24 h-24 rounded-lg overflow-hidden border border-gray-200 bg-gray-100 flex items-center justify-center">
                                @if($review->image)
                                    <img src="{{ Storage::url($review->image) }}" class="w-full h-full object-cover hover:scale-110 transition duration-300">
                                @else
                                    <i data-lucide="image" class="w-8 h-8 text-gray-300"></i>
                                @endif
                            </div>
                        </td>
                        <td class="px-4 py-4">
                            <p class="text-gray-700 text-sm whitespace-pre-wrap">{{ $review->description ?? 'No description provided.' }}</p>
                        </td>
                        <td class="px-4 py-4">
                            @if($review->status == 'published')
                                <span class="bg-green-100 text-green-700 px-2.5 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider">Published</span>
                            @elseif($review->status == 'pending')
                                <span class="bg-yellow-100 text-yellow-700 px-2.5 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider">Pending</span>
                            @else
                                <span class="bg-red-100 text-red-700 px-2.5 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider">Rejected</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                <button @click='openEdit({{ json_encode($review) }})' type="button" class="text-gray-400 hover:text-primary-maroon transition p-2 rounded-lg hover:bg-red-50" title="Edit">
                                    <i data-lucide="edit-2" class="w-4 h-4"></i>
                                </button>
                                <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this review?');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-gray-400 hover:text-red-500 transition p-2 rounded-lg hover:bg-red-50" title="Delete">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-gray-400">
                            <i data-lucide="image" class="w-12 h-12 mx-auto mb-3 text-gray-300"></i>
                            <p class="text-sm font-medium">No reviews submitted yet.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add/Edit Modal -->
    <div x-show="isModalOpen" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="isModalOpen" @click="isModalOpen = false" class="fixed inset-0 bg-gray-900/75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div x-show="isModalOpen" class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl sm:w-full">
                
                <div class="bg-white px-6 pt-5 pb-4 sm:p-6 sm:pb-4 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="text-lg leading-6 font-extrabold text-gray-900" id="modal-title" x-text="isEditing ? 'Edit Review Image' : 'Add Review Image'"></h3>
                    <button @click="isModalOpen = false" type="button" class="text-gray-400 hover:text-gray-500 focus:outline-none">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>

                <form :action="isEditing ? `{{ url('/admin/reviews') }}/${formData.id}` : '{{ route('admin.reviews.store') }}'" method="POST" enctype="multipart/form-data">
                    @csrf
                    <template x-if="isEditing">
                        <input type="hidden" name="_method" value="PUT">
                    </template>
                    
                    <div class="px-6 py-4 space-y-4">
                        
                        <div>
                            <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-2">Review Image (Max: 5MB)</label>
                            
                            <template x-if="isEditing && formData.image">
                                <div class="mb-3">
                                    <p class="text-xs text-gray-500 mb-1">Current Image:</p>
                                    <img :src="`/storage/${formData.image}`" class="h-32 rounded-lg border border-gray-200">
                                </div>
                            </template>

                            <input type="file" name="image" accept="image/jpeg,image/png,image/webp,image/gif" :required="!isEditing" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-bold file:bg-primary-maroon/10 file:text-primary-maroon hover:file:bg-primary-maroon/20">
                            <p class="text-xs text-gray-400 mt-2">The image will automatically be compressed and saved in WebP format.</p>
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-2">Description (Optional)</label>
                            <textarea name="description" x-model="formData.description" rows="3" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:border-primary-maroon focus:ring-1 focus:ring-primary-maroon outline-none transition" placeholder="Add a short description..."></textarea>
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-2">Status</label>
                            <select name="status" x-model="formData.status" required class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:border-primary-maroon focus:ring-1 focus:ring-primary-maroon outline-none transition appearance-none bg-white">
                                <option value="published">Published</option>
                                <option value="pending">Pending</option>
                                <option value="rejected">Rejected</option>
                            </select>
                        </div>

                    </div>
                    
                    <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3 rounded-b-2xl border-t border-gray-100">
                        <button type="button" @click="isModalOpen = false" class="bg-white border border-gray-200 text-gray-700 font-bold py-2.5 px-5 rounded-lg hover:bg-gray-50 transition text-sm">
                            Cancel
                        </button>
                        <button type="submit" class="bg-primary-maroon text-white font-bold py-2.5 px-5 rounded-lg hover:bg-dark-maroon transition text-sm shadow-md">
                            <span x-text="isEditing ? 'Update Review' : 'Save Review'"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('reviewManager', () => ({
        searchQuery: '',
        isModalOpen: false,
        isEditing: false,
        formData: {
            id: null,
            image: null,
            description: '',
            status: 'published'
        },
        
        matchesSearch(description) {
            if(this.searchQuery.trim() === '') return true;
            return (description || '').toLowerCase().includes(this.searchQuery.toLowerCase());
        },

        openAdd() {
            this.isEditing = false;
            this.formData = { id: null, image: null, description: '', status: 'published' };
            this.isModalOpen = true;
        },

        openEdit(review) {
            this.isEditing = true;
            this.formData = { ...review };
            this.isModalOpen = true;
        }
    }));
});
</script>
@endpush

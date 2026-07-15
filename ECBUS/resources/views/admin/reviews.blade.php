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

<div x-data="{ isEditOpen: false, editData: {}, openEdit(review) { this.editData = review; this.isEditOpen = true; } }" class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
        <h3 class="font-extrabold text-lg text-dark-text">All Reviews</h3>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead>
                <tr class="bg-gray-50 text-gray-500 text-xs uppercase font-bold border-b border-gray-100">
                    <th class="px-6 py-4">Customer</th>
                    <th class="px-6 py-4">Rating</th>
                    <th class="px-6 py-4 w-1/3">Comment</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reviews as $review)
                <tr class="border-b border-gray-50 hover:bg-gray-50/50 transition">
                    <td class="px-6 py-4">
                        <div class="font-bold text-dark-text">{{ $review->customer_name }}</div>
                        <div class="text-xs text-gray-500">{{ $review->created_at->format('d M Y, H:i') }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex text-primary-gold">
                            @for($i = 1; $i <= 5; $i++)
                                <i data-lucide="star" class="w-4 h-4 {{ $i <= $review->rating ? 'fill-current' : 'text-gray-300' }}"></i>
                            @endfor
                        </div>
                    </td>
                    <td class="px-6 py-4 text-gray-600">
                        <p class="text-xs leading-relaxed italic">"{{ Str::limit($review->comment, 100) }}"</p>
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-xs font-bold px-2 py-1 rounded-full 
                            {{ $review->status == 'published' ? 'bg-green-100 text-green-700' : '' }}
                            {{ $review->status == 'pending' ? 'bg-yellow-100 text-yellow-700' : '' }}
                            {{ $review->status == 'rejected' ? 'bg-red-100 text-red-700' : '' }}
                        ">
                            {{ ucfirst($review->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <button @click="openEdit({{ json_encode($review) }})" type="button" class="text-blue-400 hover:text-blue-600 transition p-2 rounded-lg hover:bg-blue-50" title="Edit">
                                <i data-lucide="edit" class="w-4 h-4"></i>
                            </button>
                            <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this review?');">
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
                    <td colspan="5" class="px-6 py-12 text-center text-gray-400 font-medium">
                        <i data-lucide="message-square" class="w-12 h-12 mx-auto mb-3 text-gray-300"></i>
                        No reviews submitted yet.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Edit Modal -->
    <div x-show="isEditOpen" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="isEditOpen" @click="isEditOpen = false" class="fixed inset-0 bg-gray-900/75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div x-show="isEditOpen" class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form :action="`{{ url('/admin/reviews') }}/${editData.id}`" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                                <i data-lucide="edit" class="h-5 w-5 text-blue-600"></i>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-extrabold text-gray-900" id="modal-title">Edit Review</h3>
                                <div class="mt-4 space-y-4">
                                    <div>
                                        <label class="block text-xs font-bold text-gray-700 mb-1">Customer Name</label>
                                        <input type="text" name="customer_name" x-model="editData.customer_name" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:border-primary-maroon focus:ring-primary-maroon outline-none transition" required>
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-xs font-bold text-gray-700 mb-1">Rating</label>
                                            <input type="number" name="rating" x-model="editData.rating" min="1" max="5" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:border-primary-maroon focus:ring-primary-maroon outline-none transition" required>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-gray-700 mb-1">Status</label>
                                            <select name="status" x-model="editData.status" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:border-primary-maroon focus:ring-primary-maroon outline-none transition" required>
                                                <option value="pending">Pending</option>
                                                <option value="published">Published</option>
                                                <option value="rejected">Rejected</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-700 mb-1">Comment</label>
                                        <textarea name="comment" x-model="editData.comment" rows="4" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:border-primary-maroon focus:ring-primary-maroon outline-none transition"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-gray-100">
                        <button type="submit" class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-primary-maroon text-base font-medium text-white hover:bg-dark-maroon sm:ml-3 sm:w-auto sm:text-sm transition">
                            Save Changes
                        </button>
                        <button type="button" @click="isEditOpen = false" class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

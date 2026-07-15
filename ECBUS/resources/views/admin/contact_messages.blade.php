@extends('layouts.admin')

@section('title', 'Contact Messages')
@section('header', 'Contact Messages')

@section('content')

@if(session('success'))
<div class="mb-6 bg-green-100 border border-green-200 text-green-700 px-4 py-3 rounded-xl relative" role="alert">
    <strong class="font-bold">Success!</strong>
    <span class="block sm:inline">{{ session('success') }}</span>
</div>
@endif

<div x-data="contactManager()">

    <!-- Header Actions & Search -->
    <div class="flex justify-between items-center mb-6 gap-4">
        <div class="relative w-full max-w-md">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i data-lucide="search" class="w-4 h-4 text-gray-400"></i>
            </div>
            <input type="text" x-model="searchQuery" placeholder="Search by name, email or subject..." class="w-full pl-10 pr-4 py-2.5 text-sm border border-gray-200 rounded-lg focus:border-primary-maroon focus:ring-1 focus:ring-primary-maroon outline-none transition">
        </div>
    </div>

    <!-- Messages List -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-500 text-xs uppercase font-bold border-b border-gray-100">
                        <th class="px-6 py-4">Sender Info</th>
                        <th class="px-6 py-4">Subject</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Date</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($messages as $message)
                    <tr class="border-b border-gray-50 hover:bg-gray-50/50 transition {{ $message->status == 'new' ? 'bg-blue-50/20' : '' }}"
                        x-show="matchesSearch('{{ addslashes($message->name) }}', '{{ addslashes($message->email) }}', '{{ addslashes($message->subject) }}')">
                        <td class="px-6 py-4">
                            <div class="font-bold text-dark-text">{{ $message->name }}</div>
                            <div class="text-xs text-gray-500">{{ $message->email }}</div>
                            <div class="text-xs text-gray-400">{{ $message->phone }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-medium text-gray-700 max-w-xs truncate">{{ $message->subject }}</div>
                        </td>
                        <td class="px-6 py-4">
                            @if($message->status == 'new')
                                <span class="bg-red-100 text-red-700 px-2 py-1 rounded text-xs font-bold uppercase">New</span>
                            @elseif($message->status == 'read')
                                <span class="bg-blue-100 text-blue-700 px-2 py-1 rounded text-xs font-bold uppercase">Read</span>
                            @else
                                <span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs font-bold uppercase">Replied</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-gray-500 text-xs">
                            {{ $message->created_at->format('Y-m-d h:i A') }}
                        </td>
                        <td class="px-6 py-4 text-right whitespace-nowrap">
                            <div class="flex items-center justify-end gap-2">
                                <button @click='openView({{ json_encode($message) }})' type="button" class="text-blue-400 hover:text-blue-600 transition p-2 rounded-lg hover:bg-blue-50" title="View Message">
                                    <i data-lucide="eye" class="w-4 h-4"></i>
                                </button>
                                <form action="{{ route('admin.contact_messages.destroy', $message) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this message?');">
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
                        <td colspan="5" class="px-6 py-8 text-center text-gray-400 font-medium">
                            <i data-lucide="inbox" class="w-12 h-12 mx-auto mb-3 text-gray-300"></i>
                            No contact messages received yet.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- View Modal -->
    <div x-show="isViewOpen" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="isViewOpen" @click="isViewOpen = false" class="fixed inset-0 bg-gray-900/75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div x-show="isViewOpen" class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                
                <div class="bg-white px-6 pt-5 pb-4 sm:p-6 sm:pb-4 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="text-lg leading-6 font-extrabold text-gray-900" id="modal-title" x-text="viewData.subject"></h3>
                    <button @click="isViewOpen = false" class="text-gray-400 hover:text-gray-500 focus:outline-none">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>
                <div class="bg-white px-6 py-4">
                    <div class="grid grid-cols-2 gap-4 mb-6 text-sm">
                        <div>
                            <p class="text-gray-500 font-bold text-xs uppercase mb-1">From</p>
                            <p class="font-medium text-dark-text" x-text="viewData.name"></p>
                            <p class="text-blue-500" x-text="viewData.email"></p>
                            <p class="text-gray-600" x-text="viewData.phone"></p>
                        </div>
                        <div class="text-right">
                            <p class="text-gray-500 font-bold text-xs uppercase mb-1">Date Received</p>
                            <p class="text-gray-700" x-text="formatDate(viewData.created_at)"></p>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 p-4 rounded-xl text-gray-700 whitespace-pre-wrap leading-relaxed" x-text="viewData.message">
                    </div>

                    <div class="mt-6 border-t border-gray-100 pt-4">
                        <form :action="`{{ url('/admin/contact-messages') }}/${viewData.id}`" method="POST" class="flex items-center gap-4">
                            @csrf
                            @method('PATCH')
                            <label class="text-sm font-bold text-gray-700">Update Status:</label>
                            <select name="status" x-model="viewData.status" class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:border-primary-maroon outline-none">
                                <option value="new">New</option>
                                <option value="read">Read</option>
                                <option value="replied">Replied</option>
                            </select>
                            <button type="submit" class="bg-primary-maroon text-white font-bold rounded-lg px-4 py-2 text-sm hover:bg-dark-maroon transition shadow-md">
                                Save Status
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('contactManager', () => ({
        isViewOpen: false, 
        viewData: {}, 
        searchQuery: '',
        
        matchesSearch(name, email, subject) {
            if(this.searchQuery.trim() === '') return true;
            let q = this.searchQuery.toLowerCase();
            return (name || '').toLowerCase().includes(q) || 
                   (email || '').toLowerCase().includes(q) || 
                   (subject || '').toLowerCase().includes(q);
        },
        
        openView(message) { 
            this.viewData = { ...message }; 
            this.isViewOpen = true; 
        },

        formatDate(dateString) {
            if(!dateString) return '';
            const date = new Date(dateString);
            return date.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute:'2-digit' });
        }
    }));
});
</script>
@endpush

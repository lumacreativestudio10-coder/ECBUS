@extends('layouts.admin')

@section('title', 'Company Settlements')
@section('header', 'Company Settlements')

@section('content')

@if(session('success'))
<div class="mb-6 bg-green-100 border border-green-200 text-green-700 px-4 py-3 rounded-xl relative" role="alert">
    <strong class="font-bold">Success!</strong>
    <span class="block sm:inline">{{ session('success') }}</span>
</div>
@endif

@if($errors->any())
<div class="mb-6 bg-red-100 border border-red-200 text-red-700 px-4 py-3 rounded-xl relative" role="alert">
    <ul class="list-disc list-inside font-bold text-sm">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex items-center justify-between">
        <div>
            <p class="text-sm font-bold text-gray-500 uppercase">Total Settlements</p>
            <h3 class="text-2xl font-extrabold text-dark-text mt-1">{{ $settlements->count() }}</h3>
        </div>
        <div class="w-12 h-12 rounded-full bg-blue-50 flex items-center justify-center text-blue-600">
            <i data-lucide="file-text" class="w-6 h-6"></i>
        </div>
    </div>
    
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex items-center justify-between">
        <div>
            <p class="text-sm font-bold text-gray-500 uppercase">Pending Settl.</p>
            <h3 class="text-2xl font-extrabold text-red-600 mt-1">{{ $settlements->where('status', 'pending')->count() }}</h3>
        </div>
        <div class="w-12 h-12 rounded-full bg-red-50 flex items-center justify-center text-red-600">
            <i data-lucide="clock" class="w-6 h-6"></i>
        </div>
    </div>
    
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex items-center justify-between">
        <div>
            <p class="text-sm font-bold text-gray-500 uppercase">Paid Settl.</p>
            <h3 class="text-2xl font-extrabold text-green-600 mt-1">{{ $settlements->where('status', 'paid')->count() }}</h3>
        </div>
        <div class="w-12 h-12 rounded-full bg-green-50 flex items-center justify-center text-green-600">
            <i data-lucide="check-circle" class="w-6 h-6"></i>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex items-center justify-between">
        <div>
            <p class="text-sm font-bold text-gray-500 uppercase">Total Payouts</p>
            <h3 class="text-2xl font-extrabold text-dark-text mt-1">LKR {{ number_format($settlements->where('status', 'paid')->sum('net_amount'), 2) }}</h3>
        </div>
        <div class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center text-gray-600">
            <i data-lucide="dollar-sign" class="w-6 h-6"></i>
        </div>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
        <h3 class="font-extrabold text-lg text-dark-text">Settlement Records</h3>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-white border-b border-gray-100 text-xs text-gray-400 uppercase tracking-wider">
                    <th class="px-6 py-4 font-bold">Period</th>
                    <th class="px-6 py-4 font-bold">Company</th>
                    <th class="px-6 py-4 font-bold">Total Sales</th>
                    <th class="px-6 py-4 font-bold">Commission</th>
                    <th class="px-6 py-4 font-bold">Net Payout</th>
                    <th class="px-6 py-4 font-bold">Status</th>
                    <th class="px-6 py-4 font-bold text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-sm">
                @forelse($settlements as $settlement)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 font-medium text-dark-text whitespace-nowrap">
                        {{ \Carbon\Carbon::parse($settlement->period_start)->format('M d, Y') }} - 
                        {{ \Carbon\Carbon::parse($settlement->period_end)->format('M d, Y') }}
                    </td>
                    <td class="px-6 py-4 font-bold text-dark-text">
                        {{ $settlement->company->company_name }}
                    </td>
                    <td class="px-6 py-4 text-gray-600">
                        LKR {{ number_format($settlement->total_sales, 2) }}
                    </td>
                    <td class="px-6 py-4 text-red-600 font-medium">
                        LKR {{ number_format($settlement->total_commission, 2) }}
                    </td>
                    <td class="px-6 py-4 font-extrabold text-green-700">
                        LKR {{ number_format($settlement->net_amount, 2) }}
                    </td>
                    <td class="px-6 py-4">
                        @if($settlement->status === 'paid')
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700">
                                Paid
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700">
                                Pending
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right space-x-3" x-data="{ payOpen: false }">
                        @if($settlement->status === 'pending')
                            <button @click="payOpen = true" class="px-3 py-1.5 bg-primary-maroon text-white text-xs font-bold rounded-lg hover:bg-dark-maroon transition shadow-sm">
                                Mark Paid
                            </button>
                            
                            <!-- Pay Modal -->
                            <div x-show="payOpen" class="fixed inset-0 z-50 overflow-y-auto text-left" style="display: none;">
                                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                                    <div x-show="payOpen" x-transition.opacity class="fixed inset-0 transition-opacity" aria-hidden="true">
                                        <div class="absolute inset-0 bg-dark-maroon/50 backdrop-blur-sm"></div>
                                    </div>
                                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                                    <div x-show="payOpen" x-transition.scale.origin.bottom class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                                        <form action="{{ route('admin.settlements.pay', $settlement) }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                                                <h3 class="font-extrabold text-lg text-dark-text">Process Settlement Payment</h3>
                                                <button type="button" @click="payOpen = false" class="text-gray-400 hover:text-red-500 transition">
                                                    <i data-lucide="x" class="w-5 h-5"></i>
                                                </button>
                                            </div>
                                            <div class="p-6 space-y-4">
                                                <div class="bg-green-50 text-green-800 p-4 rounded-xl border border-green-100 flex justify-between items-center">
                                                    <div>
                                                        <p class="text-xs font-bold uppercase text-green-600 mb-1">Company</p>
                                                        <p class="font-bold">{{ $settlement->company->company_name }}</p>
                                                    </div>
                                                    <div class="text-right">
                                                        <p class="text-xs font-bold uppercase text-green-600 mb-1">Amount to Pay</p>
                                                        <p class="text-xl font-extrabold">LKR {{ number_format($settlement->net_amount, 2) }}</p>
                                                    </div>
                                                </div>
                                                
                                                <div>
                                                    <label class="block text-xs font-bold text-gray-700 mb-1">Amount Paid (LKR)</label>
                                                    <input type="number" step="0.01" name="amount" value="{{ $settlement->net_amount }}" required readonly class="w-full bg-gray-100 text-gray-600 cursor-not-allowed border border-gray-200 rounded-lg px-4 py-2.5 text-sm outline-none transition">
                                                </div>
                                                
                                                <div class="grid grid-cols-2 gap-4">
                                                    <div>
                                                        <label class="block text-xs font-bold text-gray-700 mb-1">Payment Date</label>
                                                        <input type="date" name="payment_date" required value="{{ date('Y-m-d') }}" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:border-primary-maroon focus:ring-primary-maroon outline-none transition">
                                                    </div>
                                                    <div>
                                                        <label class="block text-xs font-bold text-gray-700 mb-1">Transaction ID</label>
                                                        <input type="text" name="transaction_id" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:border-primary-maroon focus:ring-primary-maroon outline-none transition" placeholder="Optional">
                                                    </div>
                                                </div>
                                                
                                                <div>
                                                    <label class="block text-xs font-bold text-gray-700 mb-1">Reference Number</label>
                                                    <input type="text" name="reference_number" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:border-primary-maroon focus:ring-primary-maroon outline-none transition" placeholder="Bank ref / Cheque No">
                                                </div>

                                                <div>
                                                    <label class="block text-xs font-bold text-gray-700 mb-1">Upload Receipt</label>
                                                    <input type="file" name="receipt" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition">
                                                </div>
                                                
                                                <div>
                                                    <label class="block text-xs font-bold text-gray-700 mb-1">Notes</label>
                                                    <textarea name="notes" rows="2" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:border-primary-maroon focus:ring-primary-maroon outline-none transition"></textarea>
                                                </div>
                                            </div>
                                            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-end space-x-3">
                                                <button type="button" @click="payOpen = false" class="px-4 py-2.5 text-sm font-bold text-gray-600 hover:text-dark-text transition">Cancel</button>
                                                <button type="submit" class="bg-primary-maroon text-white font-bold rounded-lg px-6 py-2.5 hover:bg-dark-maroon transition shadow-md">Confirm Payment</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @else
                            <span class="text-xs text-gray-400 font-bold">Settled</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-8 text-center text-gray-400">
                        <i data-lucide="inbox" class="w-12 h-12 mx-auto mb-3 text-gray-300"></i>
                        <p class="font-medium text-sm">No settlements generated yet.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection

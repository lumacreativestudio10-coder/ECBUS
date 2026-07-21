@extends('layouts.admin')

@section('title', 'My Settlements')
@section('header', 'My Settlements')

@section('content')

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
            <p class="text-sm font-bold text-gray-500 uppercase">Total Received</p>
            <h3 class="text-2xl font-extrabold text-dark-text mt-1">LKR {{ number_format($settlements->where('status', 'paid')->sum('net_amount'), 2) }}</h3>
        </div>
        <div class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center text-gray-600">
            <i data-lucide="dollar-sign" class="w-6 h-6"></i>
        </div>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50">
        <h3 class="font-extrabold text-lg text-dark-text">Settlement Records</h3>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-white border-b border-gray-100 text-xs text-gray-400 uppercase tracking-wider">
                    <th class="px-6 py-4 font-bold">Period</th>
                    <th class="px-6 py-4 font-bold">Total Sales</th>
                    <th class="px-6 py-4 font-bold">Commission Deducted</th>
                    <th class="px-6 py-4 font-bold">Net Payout</th>
                    <th class="px-6 py-4 font-bold">Status</th>
                    <th class="px-6 py-4 font-bold text-right">Payment Info</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-sm">
                @forelse($settlements as $settlement)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 font-medium text-dark-text whitespace-nowrap">
                        {{ \Carbon\Carbon::parse($settlement->period_start)->format('M d, Y') }} - 
                        {{ \Carbon\Carbon::parse($settlement->period_end)->format('M d, Y') }}
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
                    <td class="px-6 py-4 text-right">
                        @if($settlement->status === 'paid' && $settlement->payments->count() > 0)
                            <div x-data="{ infoOpen: false }" class="relative inline-block text-left">
                                <button @click="infoOpen = !infoOpen" class="text-blue-600 hover:text-blue-800 font-bold text-xs transition flex items-center justify-end w-full">
                                    View Info <i data-lucide="chevron-down" class="w-4 h-4 ml-1"></i>
                                </button>
                                
                                <div x-show="infoOpen" @click.away="infoOpen = false" x-transition class="origin-top-right absolute right-0 mt-2 w-64 rounded-xl shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50 text-left p-4" style="display: none;">
                                    @php $payment = $settlement->payments->first(); @endphp
                                    <div class="text-xs space-y-2">
                                        <p><span class="font-bold text-gray-500">Date:</span> {{ \Carbon\Carbon::parse($payment->payment_date)->format('M d, Y') }}</p>
                                        <p><span class="font-bold text-gray-500">Amount:</span> LKR {{ number_format($payment->amount, 2) }}</p>
                                        @if($payment->transaction_id)
                                        <p><span class="font-bold text-gray-500">Trx ID:</span> {{ $payment->transaction_id }}</p>
                                        @endif
                                        @if($payment->reference_number)
                                        <p><span class="font-bold text-gray-500">Ref:</span> {{ $payment->reference_number }}</p>
                                        @endif
                                        @if($payment->receipt_path)
                                        <div class="mt-3">
                                            <a href="{{ Storage::url($payment->receipt_path) }}" target="_blank" class="inline-flex items-center px-3 py-1.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition font-bold">
                                                <i data-lucide="download" class="w-3 h-3 mr-1"></i> Receipt
                                            </a>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @else
                            <span class="text-gray-400 italic text-xs">Waiting for admin</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-8 text-center text-gray-400">
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

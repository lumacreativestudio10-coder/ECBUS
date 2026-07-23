@extends('layouts.admin')

@section('title', 'Commission Settlements')
@section('header', 'Commission Settlements')

@section('content')

<div style="max-width: 100%; overflow-x: hidden;">
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

    <!-- Admin Bank Details Section -->
    <div class="rounded-2xl shadow-lg border p-4 sm:p-6 mb-6" style="background: linear-gradient(135deg, #1e293b, #0f172a); border-color: #334155; color: #ffffff;">
        <div style="display: flex; gap: 12px; align-items: flex-start; margin-bottom: 16px;">
            <div style="width: 40px; height: 40px; border-radius: 12px; background: rgba(239, 68, 68, 0.2); border: 1px solid rgba(239, 68, 68, 0.4); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                <i data-lucide="landmark" class="w-5 h-5 text-red-400"></i>
            </div>
            <div>
                <h3 class="font-extrabold text-base text-white" style="margin: 0; padding: 0;">Admin Bank Transfer Details</h3>
                <p class="text-xs text-slate-300" style="margin: 4px 0 0 0; line-height: 1.4;">Please make your commission payments to the following bank account and upload your receipt below.</p>
            </div>
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 mt-4 pt-4 border-t border-slate-700 text-sm">
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase">Bank Name</p>
                <p class="font-bold mt-1 text-slate-200">{{ config('app.admin_bank.name') }}</p>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase">Account Name</p>
                <p class="font-bold mt-1 text-slate-200">{{ config('app.admin_bank.account_name') }}</p>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase">Account Number</p>
                <p class="font-mono font-bold mt-1 text-red-400 tracking-wider text-base">{{ config('app.admin_bank.account_number') }}</p>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase">Branch</p>
                <p class="font-bold mt-1 text-slate-200">{{ config('app.admin_bank.branch') }}</p>
            </div>
        </div>
    </div>

    <!-- Stats Section -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 sm:p-6 flex items-center justify-between">
            <div>
                <p class="text-xs sm:text-sm font-bold text-gray-500 uppercase">Total Settlements</p>
                <h3 class="text-lg sm:text-2xl font-extrabold text-dark-text mt-1">{{ $settlements->count() }}</h3>
            </div>
            <div class="w-8 h-8 sm:w-12 sm:h-12 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 flex-shrink-0">
                <i data-lucide="file-text" class="w-4 h-4 sm:w-6 sm:h-6"></i>
            </div>
        </div>
        
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 sm:p-6 flex items-center justify-between">
            <div>
                <p class="text-xs sm:text-sm font-bold text-gray-500 uppercase">Pending Settl.</p>
                <h3 class="text-lg sm:text-2xl font-extrabold text-red-600 mt-1">{{ $settlements->where('status', 'pending')->count() }}</h3>
            </div>
            <div class="w-8 h-8 sm:w-12 sm:h-12 rounded-full bg-red-50 flex items-center justify-center text-red-600 flex-shrink-0">
                <i data-lucide="clock" class="w-4 h-4 sm:w-6 sm:h-6"></i>
            </div>
        </div>
        
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 sm:p-6 flex items-center justify-between">
            <div>
                <p class="text-xs sm:text-sm font-bold text-gray-500 uppercase">Paid Settl.</p>
                <h3 class="text-lg sm:text-2xl font-extrabold text-green-600 mt-1">{{ $settlements->where('status', 'paid')->count() }}</h3>
            </div>
            <div class="w-8 h-8 sm:w-12 sm:h-12 rounded-full bg-green-50 flex items-center justify-center text-green-600 flex-shrink-0">
                <i data-lucide="check-circle" class="w-4 h-4 sm:w-6 sm:h-6"></i>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 sm:p-6 flex items-center justify-between col-span-2 lg:col-span-1">
            <div>
                <p class="text-xs sm:text-sm font-bold text-gray-500 uppercase">Total Commission Owed</p>
                <h3 class="text-lg sm:text-2xl font-extrabold text-dark-text mt-1">LKR {{ number_format($settlements->sum('net_payable'), 2) }}</h3>
            </div>
            <div class="w-8 h-8 sm:w-12 sm:h-12 rounded-full bg-gray-100 flex items-center justify-center text-gray-600 flex-shrink-0">
                <i data-lucide="dollar-sign" class="w-4 h-4 sm:w-6 sm:h-6"></i>
            </div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-6" style="max-width: 100%;">
        <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50">
            <h3 class="font-extrabold text-lg text-dark-text">Settlement Records</h3>
        </div>
        
        <div style="width: 100%; overflow-x: auto; -webkit-overflow-scrolling: touch; display: block;">
            <table class="w-full text-left border-collapse" style="min-width: 750px;">
                <thead>
                    <tr class="bg-white border-b border-gray-100 text-xs text-gray-400 uppercase tracking-wider">
                        <th class="px-6 py-4 font-bold">Period</th>
                        <th class="px-6 py-4 font-bold">Total Sales</th>
                        <th class="px-6 py-4 font-bold">Commission Owed</th>
                        <th class="px-6 py-4 font-bold">Paid / Rem.</th>
                        <th class="px-6 py-4 font-bold">Status</th>
                        <th class="px-6 py-4 font-bold text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm">
                    @forelse($settlements as $settlement)
                    @php
                        $paidAmount = $settlement->payments->sum('amount');
                        $remainingAmount = $settlement->net_payable - $paidAmount;
                    @endphp
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 font-medium text-dark-text whitespace-nowrap">
                            {{ \Carbon\Carbon::parse($settlement->period_start)->format('M d, Y') }} - 
                            {{ \Carbon\Carbon::parse($settlement->period_end)->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 text-gray-600 whitespace-nowrap">
                            LKR {{ number_format($settlement->total_revenue, 2) }}
                        </td>
                        <td class="px-6 py-4 text-red-600 font-extrabold whitespace-nowrap">
                            LKR {{ number_format($settlement->net_payable, 2) }}
                        </td>
                        <td class="px-6 py-4 text-xs font-semibold text-gray-600 whitespace-nowrap">
                            Paid: LKR {{ number_format($paidAmount, 2) }}<br/>
                            Rem: LKR {{ number_format($remainingAmount, 2) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($settlement->status === 'paid')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700">
                                    Paid
                                </span>
                            @elseif($settlement->status === 'partially_paid')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-yellow-100 text-yellow-700">
                                    Partial Paid
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700">
                                    Pending
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right whitespace-nowrap" x-data="{ infoOpen: false, payOpen: false }">
                            <div class="flex justify-end gap-2">
                                @if($settlement->payments->count() > 0)
                                    <button @click="infoOpen = true" class="px-2.5 py-1.5 border border-gray-200 text-gray-600 hover:bg-gray-50 text-xs font-bold rounded-lg transition inline-flex items-center gap-1 shadow-sm">
                                        <i data-lucide="eye" class="w-3.5 h-3.5"></i> View History
                                    </button>
                                @endif

                                @if($settlement->status !== 'paid')
                                    <button @click="payOpen = true" class="px-3 py-1.5 bg-primary-maroon text-white text-xs font-bold rounded-lg hover:bg-dark-maroon transition shadow-sm inline-flex items-center gap-1">
                                        <i data-lucide="upload" class="w-3.5 h-3.5"></i> Pay Owed / Upload Receipt
                                    </button>
                                @endif
                            </div>
                            
                            <!-- Payments Info Modal -->
                            @if($settlement->payments->count() > 0)
                            <div x-show="infoOpen" class="fixed inset-0 z-50 overflow-y-auto text-left" style="display: none;">
                                <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center">
                                    <div x-show="infoOpen" x-transition.opacity class="fixed inset-0 transition-opacity" aria-hidden="true" @click="infoOpen = false">
                                        <div class="absolute inset-0 bg-dark-maroon/50 backdrop-blur-sm"></div>
                                    </div>
                                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                                    <div x-show="infoOpen" x-transition.scale.origin.bottom class="inline-block align-middle bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all my-8 max-w-[95%] sm:max-w-md w-full mx-auto z-50 relative">
                                        <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                                            <h3 class="font-extrabold text-lg text-dark-text">Your Submitted Payments</h3>
                                            <button type="button" @click="infoOpen = false" class="text-gray-400 hover:text-red-500 transition">
                                                <i data-lucide="x" class="w-5 h-5"></i>
                                            </button>
                                        </div>
                                        <div class="p-6 space-y-4 max-h-[60vh] overflow-y-auto">
                                            @foreach($settlement->payments as $payment)
                                                <div class="p-4 bg-gray-50 rounded-xl border border-gray-100 space-y-2 text-xs">
                                                    <div class="flex justify-between items-center border-b pb-2 mb-2">
                                                        <span class="text-gray-400 font-bold">Payment Date</span>
                                                        <span class="font-extrabold text-dark-text">{{ \Carbon\Carbon::parse($payment->payment_date)->format('M d, Y') }}</span>
                                                    </div>
                                                    <div class="flex justify-between items-center">
                                                        <span class="text-gray-500 font-medium">Amount Submitted:</span>
                                                        <span class="font-extrabold text-green-700">LKR {{ number_format($payment->amount, 2) }}</span>
                                                    </div>
                                                    @if($payment->transaction_id)
                                                    <div class="flex justify-between items-center">
                                                        <span class="text-gray-500 font-medium">Transaction ID:</span>
                                                        <span class="font-bold text-gray-700">{{ $payment->transaction_id }}</span>
                                                    </div>
                                                    @endif
                                                    @if($payment->reference_number)
                                                    <div class="flex justify-between items-center">
                                                        <span class="text-gray-500 font-medium">Ref Number:</span>
                                                        <span class="font-bold text-gray-700">{{ $payment->reference_number }}</span>
                                                    </div>
                                                    @endif
                                                    @if($payment->notes)
                                                    <div class="pt-2 border-t text-gray-600">
                                                        <p class="font-bold text-gray-500 mb-0.5">Notes:</p>
                                                        <p class="italic bg-white p-2 rounded border border-gray-100 text-left">{{ $payment->notes }}</p>
                                                    </div>
                                                    @endif
                                                    @if($payment->receipt_path)
                                                    <div class="pt-2 text-right">
                                                        <a href="{{ Storage::url($payment->receipt_path) }}" target="_blank" class="inline-flex items-center px-3 py-1.5 bg-primary-maroon text-white text-[10px] font-bold rounded-lg hover:bg-dark-maroon transition shadow-sm">
                                                            <i data-lucide="file-text" class="w-3.5 h-3.5 mr-1"></i> View Receipt
                                                        </a>
                                                    </div>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-end">
                                            <button type="button" @click="infoOpen = false" class="bg-gray-200 text-gray-700 font-bold rounded-lg px-6 py-2 hover:bg-gray-300 transition text-xs">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif

                            <!-- Submit Owed Payment Modal -->
                            @if($settlement->status !== 'paid')
                            <div x-show="payOpen" class="fixed inset-0 z-50 overflow-y-auto text-left" style="display: none;">
                                <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center">
                                    <div x-show="payOpen" x-transition.opacity class="fixed inset-0 transition-opacity" aria-hidden="true" @click="payOpen = false">
                                        <div class="absolute inset-0 bg-dark-maroon/50 backdrop-blur-sm"></div>
                                    </div>
                                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                                    <div x-show="payOpen" x-transition.scale.origin.bottom class="inline-block align-middle bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all my-8 max-w-[95%] sm:max-w-lg w-full mx-auto z-50 relative">
                                        <form action="{{ route(auth()->user()->getRolePrefix() . '.settlements.pay', $settlement) }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                                                <h3 class="font-extrabold text-lg text-dark-text">Submit Owed Commission Payment</h3>
                                                <button type="button" @click="payOpen = false" class="text-gray-400 hover:text-red-500 transition">
                                                    <i data-lucide="x" class="w-5 h-5"></i>
                                                </button>
                                            </div>
                                            <div class="p-6 space-y-4 max-h-[70vh] overflow-y-auto">
                                                <div class="bg-red-50 text-red-800 p-4 rounded-xl border border-red-100 flex justify-between items-center text-xs">
                                                    <div>
                                                        <p class="font-bold text-red-600 mb-0.5">Total Commission Due</p>
                                                        <p class="font-extrabold text-base">LKR {{ number_format($remainingAmount, 2) }}</p>
                                                    </div>
                                                    <div class="text-right">
                                                        <p class="text-slate-500 font-medium">Recipient</p>
                                                        <p class="font-bold">ECBUS Administrator</p>
                                                    </div>
                                                </div>
                                                
                                                <div>
                                                    <label class="block text-xs font-bold text-gray-700 mb-1">Payment Amount (LKR)</label>
                                                    <input type="number" step="0.01" max="{{ $remainingAmount }}" name="amount" value="{{ $remainingAmount }}" required class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:border-primary-maroon focus:ring-primary-maroon outline-none transition">
                                                </div>
                                                
                                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                                    <div>
                                                        <label class="block text-xs font-bold text-gray-700 mb-1">Payment Date</label>
                                                        <input type="date" name="payment_date" required value="{{ date('Y-m-d') }}" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:border-primary-maroon focus:ring-primary-maroon outline-none transition">
                                                    </div>
                                                    <div>
                                                        <label class="block text-xs font-bold text-gray-700 mb-1">Transaction ID / Ref</label>
                                                        <input type="text" name="transaction_id" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:border-primary-maroon focus:ring-primary-maroon outline-none transition" placeholder="Optional">
                                                    </div>
                                                </div>
                                                
                                                <div>
                                                    <label class="block text-xs font-bold text-gray-700 mb-1">Reference Number</label>
                                                    <input type="text" name="reference_number" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:border-primary-maroon focus:ring-primary-maroon outline-none transition" placeholder="Bank ref / Cheque No">
                                                </div>

                                                <div>
                                                    <label class="block text-xs font-bold text-gray-700 mb-1">Upload Receipt (Required)</label>
                                                    <input type="file" name="receipt" required class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition">
                                                </div>
                                                
                                                <div>
                                                    <label class="block text-xs font-bold text-gray-700 mb-1">Notes</label>
                                                    <textarea name="notes" rows="2" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:border-primary-maroon focus:ring-primary-maroon outline-none transition"></textarea>
                                                </div>
                                            </div>
                                            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-end space-x-3">
                                                <button type="button" @click="payOpen = false" class="px-4 py-2.5 text-sm font-bold text-gray-600 hover:text-dark-text transition">Cancel</button>
                                                <button type="submit" class="bg-primary-maroon text-white font-bold rounded-lg px-6 py-2.5 hover:bg-dark-maroon transition shadow-md">Submit Payment Details</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
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
</div>

@endsection

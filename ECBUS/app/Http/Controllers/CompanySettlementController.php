<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\CompanySettlement;
use App\Models\CommissionRule;
use App\Models\SettlementPayment;

class CompanySettlementController extends Controller
{
    public function index()
    {
        $settlements = CompanySettlement::with('payments')->latest()->get();
        return view('company.settlements', compact('settlements'));
    }

    public function rules()
    {
        $rules = CommissionRule::where('is_active', true)->get();
        return view('company.commission-rules', compact('rules'));
    }

    /**
     * Submit payment to admin (with receipt upload).
     */
    public function submitPayment(Request $request, CompanySettlement $settlement)
    {
        // Security check: ensure settlement belongs to current user's company
        if (auth()->user()->company_id !== $settlement->company_id) {
            abort(403, 'Unauthorized action.');
        }

        $paidAmount = $settlement->payments->sum('amount');
        $remainingAmount = $settlement->net_payable - $paidAmount;

        $request->validate([
            'amount' => 'required|numeric|min:0.01|max:' . $remainingAmount,
            'payment_date' => 'required|date',
            'transaction_id' => 'nullable|string',
            'reference_number' => 'nullable|string',
            'notes' => 'nullable|string',
            'receipt' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048'
        ]);

        $receiptPath = null;
        if ($request->hasFile('receipt')) {
            $receiptPath = $request->file('receipt')->store('receipts', 'public');
        }

        SettlementPayment::create([
            'settlement_id' => $settlement->id,
            'amount' => $request->amount,
            'payment_date' => $request->payment_date,
            'transaction_id' => $request->transaction_id,
            'reference_number' => $request->reference_number,
            'notes' => $request->notes,
            'receipt_path' => $receiptPath,
        ]);

        // Calculate total paid so far
        $totalPaid = $settlement->payments()->sum('amount');

        // Update status based on payment
        if ($totalPaid >= $settlement->net_payable) {
            $settlement->update(['status' => 'paid']);
        } else {
            $settlement->update(['status' => 'partially_paid']);
        }

        return redirect()->back()->with('success', 'Payment of LKR ' . number_format($request->amount, 2) . ' submitted successfully. Admin will verify it shortly.');
    }
}

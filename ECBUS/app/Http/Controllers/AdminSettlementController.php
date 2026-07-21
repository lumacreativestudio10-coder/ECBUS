<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\CommissionRule;
use App\Models\CompanySettlement;
use App\Models\SettlementPayment;
use App\Models\BusCompany;

class AdminSettlementController extends Controller
{
    public function rules()
    {
        $rules = CommissionRule::all();
        return view('admin.commission-rules', compact('rules'));
    }

    public function storeRule(Request $request)
    {
        $request->validate([
            'booking_source' => 'required|string',
            'type' => 'required|in:fixed,percentage',
            'value' => 'required|numeric|min:0',
        ]);

        CommissionRule::create($request->all());

        return redirect()->back()->with('success', 'Commission rule created successfully.');
    }

    public function updateRule(Request $request, CommissionRule $rule)
    {
        $request->validate([
            'booking_source' => 'required|string',
            'type' => 'required|in:fixed,percentage',
            'value' => 'required|numeric|min:0',
        ]);

        $rule->update($request->all());

        return redirect()->back()->with('success', 'Commission rule updated successfully.');
    }

    public function destroyRule(CommissionRule $rule)
    {
        $rule->delete();
        return redirect()->back()->with('success', 'Commission rule deleted successfully.');
    }

    public function toggleRule(CommissionRule $rule)
    {
        $rule->update(['is_active' => !$rule->is_active]);
        return redirect()->back()->with('success', 'Commission rule status updated.');
    }

    public function settlements()
    {
        $settlements = CompanySettlement::with('company')->latest()->get();
        $companies = BusCompany::all();
        return view('admin.settlements', compact('settlements', 'companies'));
    }

    public function paySettlement(Request $request, CompanySettlement $settlement)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
            'transaction_id' => 'nullable|string',
            'reference_number' => 'nullable|string',
            'notes' => 'nullable|string',
            'receipt' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048'
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

        $settlement->update(['status' => 'paid']);

        return redirect()->back()->with('success', 'Settlement payment recorded successfully.');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\CompanySettlement;
use App\Models\CommissionRule;

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
}

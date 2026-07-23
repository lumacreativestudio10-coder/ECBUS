<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\CommissionRule;
use App\Models\CompanySettlement;
use App\Models\SettlementPayment;
use App\Models\BusCompany;
use App\Models\Booking;
use App\Models\BookingCommission;
use App\Models\Schedule;

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
        $settlements = CompanySettlement::with(['company', 'payments'])->latest()->get();
        $companies = BusCompany::all();
        return view('admin.settlements', compact('settlements', 'companies'));
    }

    /**
     * Generate settlement for a company for a given date range.
     * Calculates total revenue and commission from bookings within the period.
     */
    public function generateSettlement(Request $request)
    {
        $request->validate([
            'company_id' => 'required|exists:bus_companies,id',
            'period_start' => 'required|date',
            'period_end' => 'required|date|after_or_equal:period_start',
        ]);

        $companyId = $request->company_id;
        $periodStart = $request->period_start;
        $periodEnd = $request->period_end;

        // Check for overlapping settlement
        $existing = CompanySettlement::withoutGlobalScopes()
            ->where('company_id', $companyId)
            ->where(function($q) use ($periodStart, $periodEnd) {
                $q->whereBetween('period_start', [$periodStart, $periodEnd])
                  ->orWhereBetween('period_end', [$periodStart, $periodEnd])
                  ->orWhere(function($q2) use ($periodStart, $periodEnd) {
                      $q2->where('period_start', '<=', $periodStart)
                         ->where('period_end', '>=', $periodEnd);
                  });
            })->first();

        if ($existing) {
            return redirect()->back()->withErrors(['error' => 'A settlement already exists for this period. Overlapping dates found.']);
        }

        // Get all schedule IDs for this company's buses in this period
        $scheduleIds = Schedule::whereHas('bus', function($q) use ($companyId) {
            $q->where('bus_company_id', $companyId);
        })->whereBetween('date', [$periodStart, $periodEnd])
          ->pluck('id');

        // Get confirmed bookings for these schedules (bypass global scope)
        $totalRevenue = Booking::withoutGlobalScopes()
            ->whereIn('schedule_id', $scheduleIds)
            ->where('booking_status', '!=', 'cancelled')
            ->sum('total_amount');

        // Get total commission for these bookings
        $bookingIds = Booking::withoutGlobalScopes()
            ->whereIn('schedule_id', $scheduleIds)
            ->where('booking_status', '!=', 'cancelled')
            ->pluck('id');

        $totalCommission = BookingCommission::withoutGlobalScopes()
            ->whereIn('booking_id', $bookingIds)
            ->where('company_id', $companyId)
            ->sum('commission_amount');

        $netPayable = $totalRevenue - $totalCommission; // Net Payout to the Company (Revenue minus Commission)

        $settlement = CompanySettlement::create([
            'company_id' => $companyId,
            'period_start' => $periodStart,
            'period_end' => $periodEnd,
            'total_revenue' => $totalRevenue,
            'total_commission' => $totalCommission,
            'net_payable' => $netPayable,
            'status' => 'pending',
        ]);

        return redirect()->back()->with('success', "Settlement generated successfully! Total Revenue: LKR " . number_format($totalRevenue, 2) . " | Commission: LKR " . number_format($totalCommission, 2));
    }

    /**
     * Record a payment for a settlement (supports partial payments).
     */
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

        // Calculate total paid so far
        $totalPaid = $settlement->payments()->sum('amount');

        // Update status based on payment
        if ($totalPaid >= $settlement->net_payable) {
            $settlement->update(['status' => 'paid']);
        } else {
            $settlement->update(['status' => 'partially_paid']);
        }

        return redirect()->back()->with('success', 'Payment of LKR ' . number_format($request->amount, 2) . ' recorded successfully.');
    }

    /**
     * Trip-wise financial summary for a schedule.
     */
    public function tripFinancials(Schedule $schedule)
    {
        $schedule->load('bus.busCompany', 'route.fromLocation', 'route.toLocation');

        $bookings = Booking::withoutGlobalScopes()
            ->where('schedule_id', $schedule->id)
            ->where('booking_status', '!=', 'cancelled')
            ->get();

        $bookingIds = $bookings->pluck('id');

        $totalRevenue = $bookings->sum('total_amount');
        $totalPaid = $bookings->sum('paid_amount');
        $totalPending = $totalRevenue - $totalPaid;
        $totalPassengers = $bookings->sum('passenger_count');

        $totalCommission = BookingCommission::withoutGlobalScopes()
            ->whereIn('booking_id', $bookingIds)
            ->sum('commission_amount');

        $netRevenue = $totalRevenue - $totalCommission;

        // Group by booking source
        $bySource = $bookings->groupBy('booking_source')->map(function($group) {
            return [
                'count' => $group->count(),
                'revenue' => $group->sum('total_amount'),
                'passengers' => $group->sum('passenger_count'),
            ];
        });

        return view('admin.trip-financials', compact(
            'schedule', 'bookings', 'totalRevenue', 'totalPaid', 'totalPending',
            'totalPassengers', 'totalCommission', 'netRevenue', 'bySource'
        ));
    }
}

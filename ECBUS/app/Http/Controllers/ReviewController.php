<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string'
        ]);

        Review::create([
            'customer_name' => $request->customer_name,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'status' => 'pending' // Default to pending
        ]);

        return redirect()->back()->with('review_success', 'Thank you for your feedback! Your review has been submitted and is pending approval.');
    }
}

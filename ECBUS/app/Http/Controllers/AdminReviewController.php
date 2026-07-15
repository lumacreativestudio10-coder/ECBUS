<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;

class AdminReviewController extends Controller
{
    public function index()
    {
        $reviews = Review::latest()->get();
        return view('admin.reviews', compact('reviews'));
    }

    public function update(Request $request, Review $review)
    {
        $request->validate([
            'customer_name' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
            'status' => 'required|in:pending,published,rejected'
        ]);

        $review->update($request->all());

        return redirect()->back()->with('success', 'Review updated successfully!');
    }

    public function destroy(Review $review)
    {
        $review->delete(); // Soft delete
        return redirect()->back()->with('success', 'Review deleted successfully!');
    }
}

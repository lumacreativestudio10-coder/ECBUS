<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use Illuminate\Support\Facades\Storage;

class AdminReviewController extends Controller
{
    public function index(Request $request)
    {
        $query = Review::query();
        
        if ($request->has('search') && $request->search != '') {
            $query->where('description', 'like', '%' . $request->search . '%');
        }
        
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $reviews = $query->latest()->get();
        return view('admin.reviews', compact('reviews'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120', // 5MB limit
            'description' => 'nullable|string',
            'status' => 'required|in:pending,published,rejected'
        ]);

        $imagePath = $this->saveAsWebp($request->file('image'));

        Review::create([
            'image' => $imagePath,
            'description' => $request->description,
            'status' => $request->status
        ]);

        return redirect()->back()->with('success', 'Review added successfully!');
    }

    public function update(Request $request, Review $review)
    {
        $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'description' => 'nullable|string',
            'status' => 'required|in:pending,published,rejected'
        ]);

        $data = [
            'description' => $request->description,
            'status' => $request->status
        ];

        if ($request->hasFile('image')) {
            if ($review->image) {
                Storage::disk('public')->delete($review->image);
            }
            $data['image'] = $this->saveAsWebp($request->file('image'));
        }

        $review->update($data);

        return redirect()->back()->with('success', 'Review updated successfully!');
    }

    public function destroy(Review $review)
    {
        if ($review->image) {
            Storage::disk('public')->delete($review->image);
        }
        $review->forceDelete(); // Hard delete to remove file permanently
        return redirect()->back()->with('success', 'Review deleted successfully!');
    }

    private function saveAsWebp($file)
    {
        $filename = uniqid('review_') . '.webp';
        $directory = storage_path('app/public/reviews');
        
        if (!file_exists($directory)) {
            mkdir($directory, 0755, true);
        }

        $path = $directory . '/' . $filename;
        $mime = $file->getMimeType();

        if ($mime == 'image/webp') {
            $file->storeAs('public/reviews', $filename);
            return 'reviews/' . $filename;
        }

        $image = null;
        if ($mime == 'image/jpeg') {
            $image = @imagecreatefromjpeg($file->getRealPath());
        } elseif ($mime == 'image/png') {
            $image = @imagecreatefrompng($file->getRealPath());
            if ($image) {
                imagepalettetotruecolor($image);
                imagealphablending($image, true);
                imagesavealpha($image, true);
            }
        } elseif ($mime == 'image/gif') {
            $image = @imagecreatefromgif($file->getRealPath());
        }

        if ($image) {
            imagewebp($image, $path, 80);
            imagedestroy($image);
            return 'reviews/' . $filename;
        }

        // Fallback if GD fails
        $file->storeAs('public/reviews', $filename);
        return 'reviews/' . $filename;
    }
}

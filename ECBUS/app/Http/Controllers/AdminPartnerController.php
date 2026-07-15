<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Operator;

class AdminPartnerController extends Controller
{
    public function index()
    {
        $partners = Operator::latest()->get();
        return view('admin.partners', compact('partners'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive'
        ]);

        $data = $request->all();
        $data['logo'] = $this->handleLogoUpload($request);
        $data['status'] = $request->status === 'active' ? 1 : 0;

        Operator::create($data);

        return redirect()->back()->with('success', 'Partner added successfully!');
    }

    public function update(Request $request, Operator $partner)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive'
        ]);

        $data = $request->all();
        if ($request->hasFile('logo')) {
            $data['logo'] = $this->handleLogoUpload($request, $partner->logo);
        }
        $data['status'] = $request->status === 'active' ? 1 : 0;

        $partner->update($data);

        return redirect()->back()->with('success', 'Partner updated successfully!');
    }

    public function destroy(Operator $partner)
    {
        $partner->delete(); // Soft delete
        return redirect()->back()->with('success', 'Partner deleted successfully!');
    }

    private function handleLogoUpload(Request $request, $currentLogo = null)
    {
        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $extension = strtolower($file->getClientOriginalExtension());
            $image = null;

            if (in_array($extension, ['jpg', 'jpeg'])) {
                $image = @imagecreatefromjpeg($file->path());
            } elseif ($extension === 'png') {
                $image = @imagecreatefrompng($file->path());
            } elseif ($extension === 'webp') {
                $image = @imagecreatefromwebp($file->path());
            }

            if ($image) {
                $dir = storage_path('app/public/partners');
                if (!file_exists($dir)) {
                    mkdir($dir, 0755, true);
                }

                $filename = uniqid('logo_') . '.webp';
                $path = $dir . '/' . $filename;
                
                imagepalettetotruecolor($image);
                imagealphablending($image, true);
                imagesavealpha($image, true);

                imagewebp($image, $path, 80);
                imagedestroy($image);
                
                if ($currentLogo && \Storage::disk('public')->exists($currentLogo)) {
                    \Storage::disk('public')->delete($currentLogo);
                }

                return 'partners/' . $filename;
            } else {
                // fallback if gd fails
                return $file->store('partners', 'public');
            }
        }
        return $currentLogo;
    }
}

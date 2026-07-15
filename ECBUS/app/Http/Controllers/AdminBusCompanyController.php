<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\BusCompany;

class AdminBusCompanyController extends Controller
{
    public function index(Request $request)
    {
        $query = BusCompany::latest();

        if ($request->has('search') && !empty(trim($request->search))) {
            $searchTerms = explode(' ', trim($request->search));
            
            $query->where(function($q) use ($searchTerms) {
                foreach ($searchTerms as $term) {
                    $term = trim($term);
                    if (!empty($term)) {
                        $q->orWhere('company_name', 'like', "%{$term}%")
                          ->orWhere('company_code', 'like', "%{$term}%")
                          ->orWhere('contact_person', 'like', "%{$term}%")
                          ->orWhere('mobile_number', 'like', "%{$term}%");
                    }
                }
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Apply additional filters (like district) if they exist
        if ($request->filled('district')) {
            $query->where('district', $request->district);
        }

        // Get aggregate statistics
        $totalCompanies = BusCompany::count();
        $activeCompanies = BusCompany::where('status', 1)->count();
        $inactiveCompanies = BusCompany::where('status', 0)->count();
        
        // Dummy data for now
        $totalBuses = 0;
        $totalRoutes = 0;
        
        // Get districts for filter
        $districts = BusCompany::whereNotNull('district')->where('district', '!=', '')->distinct()->pluck('district');
        
        // Top companies dummy data
        $topCompanies = BusCompany::take(3)->get();

        $busCompanies = $query->paginate(5)->withQueryString();
        
        return view('admin.bus_companies', compact(
            'busCompanies', 
            'totalCompanies', 
            'activeCompanies', 
            'inactiveCompanies',
            'totalBuses',
            'totalRoutes',
            'districts',
            'topCompanies'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'company_name' => 'required|string|max:255',
            'company_code' => 'nullable|string|max:255|unique:bus_companies,company_code',
            'contact_person' => 'required|string|max:255',
            'mobile_number' => 'required|regex:/^[0-9]+$/|max:20',
            'whatsapp_number' => 'nullable|regex:/^[0-9]+$/|max:20',
            'telephone' => 'nullable|regex:/^[0-9]+$/|max:20',
            'commission_per_seat' => 'required|numeric|min:0',
            'status' => 'required|in:1,0',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $data = $request->all();
        $data['logo'] = $this->handleLogoUpload($request);

        BusCompany::create($data);

        return redirect()->back()->with('success', 'Bus Company added successfully!');
    }

    public function update(Request $request, BusCompany $busCompany)
    {
        $request->validate([
            'company_name' => 'required|string|max:255',
            'company_code' => 'nullable|string|max:255|unique:bus_companies,company_code,' . $busCompany->id,
            'contact_person' => 'required|string|max:255',
            'mobile_number' => 'required|regex:/^[0-9]+$/|max:20',
            'whatsapp_number' => 'nullable|regex:/^[0-9]+$/|max:20',
            'telephone' => 'nullable|regex:/^[0-9]+$/|max:20',
            'commission_per_seat' => 'required|numeric|min:0',
            'status' => 'required|in:1,0',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $data = $request->all();
        if ($request->hasFile('logo')) {
            $data['logo'] = $this->handleLogoUpload($request, $busCompany->logo);
        }

        $busCompany->update($data);

        return redirect()->back()->with('success', 'Bus Company updated successfully!');
    }

    public function destroy(BusCompany $busCompany)
    {
        $busCompany->delete();
        return redirect()->back()->with('success', 'Bus Company deleted successfully!');
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
                $dir = storage_path('app/public/bus_companies');
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

                return 'bus_companies/' . $filename;
            } else {
                return $file->store('bus_companies', 'public');
            }
        }
        return $currentLogo;
    }
}

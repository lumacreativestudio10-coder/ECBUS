<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string'
        ]);

        $validated['status'] = 'new';

        ContactMessage::create($validated);

        return back()->with('success', 'Thank you for reaching out! Your message has been sent successfully. We will get back to you shortly.');
    }
}

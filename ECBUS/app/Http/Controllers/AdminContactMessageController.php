<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\Request;

class AdminContactMessageController extends Controller
{
    public function index()
    {
        $messages = ContactMessage::orderBy('created_at', 'desc')->get();
        return view('admin.contact_messages', compact('messages'));
    }

    public function updateStatus(Request $request, ContactMessage $message)
    {
        $request->validate(['status' => 'required|in:new,read,replied']);
        $message->update(['status' => $request->status]);
        return back()->with('success', 'Message status updated.');
    }

    public function destroy(ContactMessage $message)
    {
        $message->delete();
        return back()->with('success', 'Message deleted.');
    }
}

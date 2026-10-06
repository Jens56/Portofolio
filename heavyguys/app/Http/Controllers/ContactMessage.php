<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;

class ContactMessage extends Controller
{
    public function __invoke(Request $request)
    {
        $validated = $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        Contact::create($validated);

        return redirect()->back()->with('success', 'Bedankt voor je bericht! We nemen contact met je op.');
    }
}

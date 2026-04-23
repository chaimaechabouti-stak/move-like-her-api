<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    /** POST /api/contact */
    public function store(Request $request)
    {
        $data = $request->validate([
            'nom'       => 'required|string|max:100',
            'email'     => 'required|email|max:150',
            'telephone' => 'nullable|string|max:20',
            'sujet'     => 'nullable|string|max:150',
            'message'   => 'required|string|min:10|max:2000',
        ]);

        $contact = Contact::create($data);

        return response()->json([
            'message' => 'Ton message a bien été envoyé. Nous te répondrons dans les plus brefs délais.',
            'id'      => $contact->id,
        ], 201);
    }
}

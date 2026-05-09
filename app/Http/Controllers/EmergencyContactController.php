<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EmergencyContact;
use Illuminate\Support\Facades\Auth;

class EmergencyContactController extends Controller
{
    public function edit()
    {
        $contact = EmergencyContact::firstOrNew(['user_id' => Auth::id()]);
        return view('pages.mahasiswa.emergency-edit', compact('contact'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'relationship'=> 'required|string|max:255',
            'phone'       => 'required|string|max:20',
        ]);

        EmergencyContact::updateOrCreate(
            ['user_id' => Auth::id()],
            $data
        );

        return redirect()->back()->with('success', 'Kontak darurat berhasil disimpan.');
    }
}

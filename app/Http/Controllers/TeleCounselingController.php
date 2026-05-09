<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TeleCounselingController extends Controller
{
    private $psychologists = [
        [
            'name'  => 'Dr. Aulia Rahma',
            'phone' => '+6281234567890',
            'photo' => 'images/logo-sulaharing.png', // Fallback to logo
        ],
        [
            'name'  => 'Dr. Budi Santoso',
            'phone' => '+6289876543210',
            'photo' => 'images/logo-sulaharing.png', // Fallback to logo
        ],
    ];

    public function index()
    {
        return view('pages.mahasiswa.tele-counseling', [
            'psychologists' => $this->psychologists,
        ]);
    }
}

<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserAlamat;

class AlamatController extends Controller
{
    public function create()
    {
        return view('user.alamat.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'label' => 'required|string|max:255',
            'alamat' => 'required|string|max:500',
            'latitude' => 'required',
            'longitude' => 'required',
        ]);

        UserAlamat::create([
            'user_id' => auth()->id(),
            'label' => $request->label,
            'alamat' => $request->alamat,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'is_default' => false,
        ]);

        return redirect()->route('user.keranjang.index')
            ->with('success', 'Alamat berhasil ditambahkan');
    }
}

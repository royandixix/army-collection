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
        'alamat' => 'required|string|max:500',
        'is_default' => 'nullable|boolean',
    ]);

    $isDefault = $request->has('is_default') ? true : false;

    if ($isDefault) {
        \App\Models\UserAlamat::where('user_id', auth()->id())->update(['is_default' => false]);
    }

    \App\Models\UserAlamat::updateOrCreate(
        ['user_id' => auth()->id(), 'alamat' => $request->alamat],
        ['is_default' => $isDefault]
    );

    return redirect()->back()->with('success', 'Alamat berhasil ditambahkan');
}

}

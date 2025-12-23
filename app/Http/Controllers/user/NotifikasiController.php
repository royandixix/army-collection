<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotifikasiController extends Controller
{
    public function bacaSemua(Request $request)
    {
        $user = $request->user();
        $user->unreadNotifications->markAsRead();

        return redirect()->back()->with('success', 'Semua notifikasi telah dibaca.');
    }
}

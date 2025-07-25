<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class SearchController extends Controller
{
    public function liveSearch(Request $request)
    {
        $query = $request->input('q');

        $results = User::where('name', 'like', "%{$query}%")
                        ->orWhere('email', 'like', "%{$query}%")
                        ->limit(10)
                        ->get();

        return view('admin.partials.search-results', compact('results'));
    }
}

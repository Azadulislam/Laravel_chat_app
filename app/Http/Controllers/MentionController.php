<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class MentionController extends Controller
{
    public function search(Request $request)
    {
        $q = $request->get('q', '');
        $users = User::where('username', 'like', "%{$q}%")
            ->limit(10)
            ->get(['id', 'username']);

        return response()->json($users);
    }
}

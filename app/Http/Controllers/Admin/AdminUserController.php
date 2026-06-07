<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\user as User;

class AdminUserController extends Controller
{
    public function index()
    {
        $users = User::query()
            ->where('role', 'user')
            ->orderBy('nama')
            ->paginate(20);

        return view('admin.users.index', compact('users'));
    }
}

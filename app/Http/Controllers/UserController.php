<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
{
    $users = User::latest()->get();

    return view('admin.users.user', compact('users'));
}

public function show($id)
{
    $user = User::findOrFail($id);

    return view('admin.users.show', compact('user'));
}
}

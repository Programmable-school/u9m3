<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Users;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return ['users' => $users];
    }
}

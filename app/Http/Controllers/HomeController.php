<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;

class HomeController extends Controller
{
    public function index()
    {
        return view('user.home');
    }

    public function test()
    {
        $user = User::all()->first();
        return view('test', ['user' => $user]);
    }
}

<?php

namespace App\Controllers;

class Dashboard extends BaseController
{
    public function index(): string
    {
        $user = auth()->user();

        return view('user/dashboard/index', [
            'user' => $user,
        ]);
    }
}

<?php

namespace App\Controllers;

class Dashboard extends BaseController
{
    public function index(): string
    {
        $user = auth()->user();

        return view('dashboard/index', [
            'user' => $user,
        ]);
    }
}

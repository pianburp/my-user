<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AdminFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $user = auth()->user();

        if ($user === null || ! method_exists($user, 'inGroup') || ! $user->inGroup('admin')) {
            return redirect()->to('/user/dashboard')->with('error', 'You are not authorized to access that page.');
        }

        return null;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        return;
    }
}

<?php

namespace App\Controllers;

class Profile extends BaseController
{
    public function index(): string
    {
        $user = auth()->user();

        return view('user/profile/index', [
            'user' => $user,
        ]);
    }

    public function update(): \CodeIgniter\HTTP\RedirectResponse
    {
        $user  = auth()->user();
        $rules = [
            'username' => "required|min_length[3]|max_length[30]|alpha_numeric_punct|is_unique[users.username,id,{$user->id}]",
        ];

        if (! $this->validate($rules)) {
            return redirect()->to('/user/profile')
                ->withInput()
                ->with('profile_errors', $this->validator->getErrors());
        }

        $username = $this->request->getPost('username');

        $user->username = $username;

        $users = auth()->getProvider();
        $users->save($user);

        return redirect()->to('/user/profile')->with('profile_success', 'Profile updated successfully.');
    }

    public function changePassword(): \CodeIgniter\HTTP\RedirectResponse
    {
        $user  = auth()->user();
        $rules = [
            'current_password' => 'required',
            'new_password'     => 'required|min_length[8]',
            'password_confirm' => 'required|matches[new_password]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->to('/user/profile')
                ->withInput()
                ->with('password_errors', $this->validator->getErrors());
        }

        // Verify current password
        $credentials = [
            'email'    => $user->email,
            'password' => $this->request->getPost('current_password'),
        ];

        $validPassword = auth()->check($credentials);

        if (! $validPassword->isOK()) {
            return redirect()->to('/user/profile')
                ->with('password_errors', ['current_password' => 'Current password is incorrect.']);
        }

        $users = auth()->getProvider();
        $user->fill(['password' => $this->request->getPost('new_password')]);
        $users->save($user);

        return redirect()->to('/user/profile')->with('password_success', 'Password changed successfully.');
    }
}

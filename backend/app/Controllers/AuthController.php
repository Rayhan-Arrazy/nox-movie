<?php

namespace App\Controllers;

use App\Models\UserModel;

class AuthController extends BaseController
{
    protected UserModel $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    /**
     * Show login form
     */
    public function login()
    {
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/');
        }
        return view('auth/login', ['title' => 'Login']);
    }

    /**
     * Process login
     */ 
    public function attemptLogin()
    {
        $rules = [
            'email' => 'required|valid_email',
            'password' => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $user = $this->userModel->findByEmail($email);

        if (!$user || !password_verify($password, $user['password'])) {
            return redirect()->back()->withInput()->with('error', 'Invalid email or password.');
        }

        // Set session
        session()->set([
            'userId' => $user['id'],
            'userName' => $user['name'],
            'userEmail' => $user['email'],
            'role' => $user['role'],
            'avatar' => $user['avatar'],
            'isLoggedIn' => true,
        ]);

        if ($user['role'] === 'admin') {
            return redirect()->to('/admin')->with('success', 'Welcome back, Admin!');
        }

        return redirect()->to('/')->with('success', 'Welcome back, ' . $user['name'] . '!');
    }

    /**
     * Show register form
     */
    public function register()
    {
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/');
        }
        return view('auth/register', ['title' => 'Register']);
    }

    /**
     * Process registration
     */
    public function attemptRegister()
    {
        $rules = [
            'name' => 'required|min_length[2]|max_length[100]',
            'email' => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[6]',
            'confirm_password' => 'required|matches[password]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->userModel->save([
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role' => 'client',
        ]);

        return redirect()->to('/login')->with('success', 'Account created successfully! Please login.');
    }

    /**
     * Logout
     */
    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login')->with('success', 'You have been logged out.');
    }
}

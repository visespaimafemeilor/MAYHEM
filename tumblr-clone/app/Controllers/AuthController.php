<?php

class AuthController extends Controller
{
    private User $userModel;

    public function __construct()
    {
        $this->userModel = $this->model('User');
    }

    public function login(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            $user = $this->userModel->findByEmail($email);

            if ($user && password_verify($password, $user['password_hash'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $this->redirect('/dashboard');
            }

            $this->view('auth/login', ['error' => 'Email sau parolă incorectă.']);
            return;
        }

        $this->view('auth/login');
    }

    public function register(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $confirm = $_POST['confirm_password'] ?? '';

            if ($password !== $confirm) {
                $this->view('auth/register', ['error' => 'Parolele nu coincid.']);
                return;
            }

            if ($this->userModel->findByEmail($email)) {
                $this->view('auth/register', ['error' => 'Email deja folosit.']);
                return;
            }

            if ($this->userModel->findByUsername($username)) {
                $this->view('auth/register', ['error' => 'Nume de utilizator deja folosit.']);
                return;
            }

            $hash = password_hash($password, PASSWORD_BCRYPT);
            $userId = $this->userModel->create($username, $email, $hash);

            $_SESSION['user_id'] = $userId;
            $_SESSION['username'] = $username;
            $this->redirect('/dashboard');
        }

        $this->view('auth/register');
    }

    public function logout(): void
    {
        session_destroy();
        $this->redirect('/login');
    }
}

<?php

class SettingsController extends Controller
{
    private User $userModel;

    public function __construct()
    {
        $this->userModel = $this->model('User');
    }

    public function index(): void
    {
        $this->requireLogin();
        $user = $this->userModel->findById($_SESSION['user_id']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bio'])) {
            $this->userModel->updateBio($_SESSION['user_id'], trim($_POST['bio']));
            $this->redirect('/settings');
        }

        $this->view('settings/index', ['user' => $user]);
    }

    public function updateAvatar(): void
    {
        $this->requireLogin();

        if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
            $ext = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
            $filename = 'avatar_' . $_SESSION['user_id'] . '.' . $ext;
            move_uploaded_file($_FILES['avatar']['tmp_name'], IMAGES_DIR . '/' . $filename);
            $this->userModel->updateAvatar($_SESSION['user_id'], 'assets/images/' . $filename);
        }

        $this->redirect('/settings');
    }

    public function updatePassword(): void
    {
        $this->requireLogin();
        $user = $this->userModel->findById($_SESSION['user_id']);
        $current = $_POST['current_password'] ?? '';
        $new = $_POST['new_password'] ?? '';
        $confirm = $_POST['confirm_password'] ?? '';

        if (!password_verify($current, $user['password_hash'])) {
            $_SESSION['error'] = 'Parola actuală este incorectă.';
        } elseif ($new !== $confirm) {
            $_SESSION['error'] = 'Parolele noi nu coincid.';
        } elseif (strlen($new) < 6) {
            $_SESSION['error'] = 'Parola trebuie să aibă cel puțin 6 caractere.';
        } else {
            $this->userModel->updatePassword($_SESSION['user_id'], password_hash($new, PASSWORD_BCRYPT));
            $_SESSION['success'] = 'Parola a fost schimbată.';
        }

        $this->redirect('/settings');
    }
}

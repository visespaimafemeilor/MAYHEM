<?php

class ProfileController extends Controller
{
    private User $userModel;
    private Post $postModel;

    public function __construct()
    {
        $this->userModel = $this->model('User');
        $this->postModel = $this->model('Post');
    }

    public function index(string $username = null): void
    {
        if (!$username) {
            $this->redirect('/dashboard');
        }

        $profileUser = $this->userModel->findByUsername($username);
        if (!$profileUser) {
            http_response_code(404);
            require __DIR__ . '/../Views/errors/404.php';
            return;
        }

        $currentUserId = $_SESSION['user_id'] ?? 0;
        $posts = $this->postModel->getByUser($profileUser['id'], $currentUserId);
        $isFollowing = $currentUserId ? $this->userModel->isFollowing($currentUserId, $profileUser['id']) : false;

        $this->view('profile/index', [
            'profileUser' => $profileUser,
            'posts' => $posts,
            'isFollowing' => $isFollowing,
        ]);
    }

    public function follow(): void
    {
        $this->requireLogin();
        $followedId = (int) ($_POST['user_id'] ?? 0);

        if ($followedId === $_SESSION['user_id']) {
            $this->json(['error' => 'Nu te poți urmări pe tine însuți.']);
            return;
        }

        $follow = $this->model('Follow');
        $result = $follow->toggle($_SESSION['user_id'], $followedId);
        $this->json($result);
    }
}

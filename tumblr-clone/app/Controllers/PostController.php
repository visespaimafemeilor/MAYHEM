<?php

class PostController extends Controller
{
    private Post $postModel;

    public function __construct()
    {
        $this->postModel = $this->model('Post');
    }

    public function create(): void
    {
        $this->requireLogin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $type = $_POST['type'] ?? 'text';
            $title = $_POST['title'] ?? null;
            $body = $_POST['body'] ?? null;
            $mediaUrl = null;

            if (in_array($type, ['image', 'link']) && isset($_FILES['media']) && $_FILES['media']['error'] === UPLOAD_ERR_OK) {
                $ext = pathinfo($_FILES['media']['name'], PATHINFO_EXTENSION);
                $filename = uniqid('media_') . '.' . $ext;
                $dest = UPLOAD_DIR . '/' . $filename;
                move_uploaded_file($_FILES['media']['tmp_name'], $dest);
                $mediaUrl = 'assets/uploads/' . $filename;
            }

            $this->postModel->create($_SESSION['user_id'], $type, $title, $body, $mediaUrl);
            $this->redirect('/dashboard');
        }

        $this->view('posts/create');
    }

    public function edit(): void
    {
        $this->requireLogin();
        $postId = (int) ($_GET['id'] ?? 0);

        $post = $this->postModel->findById($postId);
        if (!$post || !$this->postModel->belongsToUser($postId, $_SESSION['user_id'])) {
            $this->redirect('/dashboard');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $type = $_POST['type'] ?? $post['type'];
            $title = $_POST['title'] ?? null;
            $body = $_POST['body'] ?? null;
            $mediaUrl = $post['media_url'];

            if (isset($_FILES['media']) && $_FILES['media']['error'] === UPLOAD_ERR_OK) {
                $ext = pathinfo($_FILES['media']['name'], PATHINFO_EXTENSION);
                $filename = uniqid('media_') . '.' . $ext;
                move_uploaded_file($_FILES['media']['tmp_name'], UPLOAD_DIR . '/' . $filename);
                $mediaUrl = 'assets/uploads/' . $filename;
            }

            $this->postModel->update($postId, $type, $title, $body, $mediaUrl);
            $this->redirect('/profile/' . $_SESSION['username']);
        }

        $this->view('posts/edit', ['post' => $post]);
    }

    public function delete(): void
    {
        $this->requireLogin();
        $postId = (int) ($_GET['id'] ?? 0);

        if ($this->postModel->belongsToUser($postId, $_SESSION['user_id'])) {
            $this->postModel->delete($postId);
        }

        $this->redirect('/profile/' . $_SESSION['username']);
    }

    public function like(): void
    {
        $this->requireLogin();
        $postId = (int) ($_POST['post_id'] ?? 0);

        $like = $this->model('Like');
        $result = $like->toggle($_SESSION['user_id'], $postId);

        $this->json($result);
    }
}

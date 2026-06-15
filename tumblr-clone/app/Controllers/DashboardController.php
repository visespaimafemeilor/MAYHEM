<?php

class DashboardController extends Controller
{
    private Post $postModel;

    public function __construct()
    {
        $this->postModel = $this->model('Post');
    }

    public function index(): void
    {
        $this->requireLogin();

        $posts = $this->postModel->getFeed($_SESSION['user_id']);

        $this->view('dashboard/index', [
            'posts' => $posts,
        ]);
    }
}

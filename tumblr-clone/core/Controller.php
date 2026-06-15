<?php

class Controller
{
    protected function model(string $model): object
    {
        $file = __DIR__ . '/../app/Models/' . $model . '.php';
        require_once $file;
        return new $model();
    }

    protected function view(string $view, array $data = []): void
    {
        extract($data);
        $content = __DIR__ . '/../app/Views/' . $view . '.php';
        require __DIR__ . '/../app/Views/layout.php';
    }

    protected function redirect(string $url): void
    {
        header('Location: ' . BASE_URL . $url);
        exit;
    }

    protected function json(array $data): void
    {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    protected function isLoggedIn(): bool
    {
        return isset($_SESSION['user_id']);
    }

    protected function requireLogin(): void
    {
        if (!$this->isLoggedIn()) {
            $this->redirect('/login');
        }
    }
}

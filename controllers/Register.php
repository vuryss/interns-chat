<?php


class RegisterController
{
    public function form()
    {
        if (!empty($_SESSION['authenticated']) || $_SESSION['authenticated'] === true) {
            header('Location: index.php?action=chat');
            exit;
        }

        $view = new View();
        $view->htmlFile('register');

        return $view;
    }

    public function register()
    {
        if (!empty($_SESSION['authenticated']) || $_SESSION['authenticated'] === true) {
            header('Location: index.php?action=chat');
            exit;
        }

        $userModel = new UserModel();

        try {
            if ($userModel->register($_POST)) {
                header('Location: index.php?action=chat');
                exit;
            }
        } catch (Exception $e) {
            $_SESSION['errorMessage'] = $e->getMessage();
            header('Location: index.php?action=register');
            exit;
        }
    }
}

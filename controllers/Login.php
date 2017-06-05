<?php


class LoginController
{
    public function form()
    {
        if (!empty($_SESSION['authenticated']) || $_SESSION['authenticated'] === true) {
            header('Location: index.php?action=chat');
            exit;
        }

        $view = new View();
        $view->htmlFile('login');

        return $view;
    }

    public function login()
    {
        if (!empty($_SESSION['authenticated']) || $_SESSION['authenticated'] === true) {
            header('Location: index.php?action=chat');
            exit;
        }

        $userModel = new UserModel();

        try {
            if ($userModel->login($_POST)) {
                header('Location: index.php?action=chat');
                exit;
            }
        } catch (Exception $e) {
            $_SESSION['errorMessage'] = $e->getMessage();
            header('Location: index.php?action=login');
            exit;
        }
    }
}

<?php


class ChatController
{
    public function view()
    {
        if (empty($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
            header('Location: index.php?action=login');
            exit;
        }

        $userModel = new UserModel();
        $onlineUsers = $userModel->getOnline();

        $messagesModel = new MessagesModel();
        $allMessages = $messagesModel->getAllMessages();

        foreach ($allMessages as $key => $message) {
            $allMessages[$key]['date'] = date('Y-m-d H:i:s', $message['created']);
        }

        $view = new View();
        $view->htmlFile('chat', ['onlineUsers' => $onlineUsers, 'messages' => $allMessages]);

        return $view;
    }

    public function message()
    {
        if (empty($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
            header('Location: index.php?action=login');
            exit;
        }

        $messageModel = new MessagesModel();
        $view = new View();

        $data = file_get_contents('php://input');

        try {
            $data = json_decode($data, true);

            if (!$data) {
                throw new Exception('Not a valid request');
            }

            $messageModel->addMessage($_SESSION['userId'], $data);
        } catch (Exception $e) {
            $view->json(['status' => 'error', 'message' => $e->getMessage()]);
            return $view;
        }

        $view->json(['status' => 'success']);

        return $view;
    }

    public function update()
    {
        if (empty($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
            header('Location: index.php?action=login');
            exit;
        }

        $userModel = new UserModel();
        $onlineUsers = $userModel->getOnline();

        // Update last activity time
        $userModel->updateLastSeen();

        $view = new View();

        $messageModel = new MessagesModel();

        $data = file_get_contents('php://input');

        try {
            $data = json_decode($data, true);

            if (!$data) {
                throw new Exception('Not a valid request');
            }

            if (empty($data['id'])) {
                throw new Exception('Not a valid request, missing ID');
            }

            $newMessages = $messageModel->getMessagesSinceId($data['id']);

            foreach ($newMessages as $key => $message) {
                $newMessages[$key]['date'] = date('Y-m-d H:i:s', $message['created']);
            }

            $view->json(['status' => 'success', 'messages' => $newMessages, 'users' => $onlineUsers]);

            return $view;
        } catch (Exception $e) {
            $view->json(['status' => 'error', 'message' => $e->getMessage()]);
            return $view;
        }
    }
}

<?php


class UserModel
{
    public function register($data)
    {
        // validations
        if (
            empty($data['username'])
            || empty($data['password'])
            || empty($data['password-repeat'])
            || empty($data['email'])
        ) {
            throw new Exception('All fields are required');
        }

        if ($data['password'] !== $data['password-repeat']) {
            throw new Exception('Password do not match');
        }

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            throw new Exception('Email is not valid');
        }

        $db = DB::getInstance();

        if ($db->fetchOne("SELECT `id` FROM `users` WHERE `username` = ?", [$data['username']])) {
            throw new Exception('User already exists');
        }

        if ($db->fetchOne("SELECT `id` FROM `users` WHERE `email` = ?", [$data['email']])) {
            throw new Exception('Email already exists');
        }

        // Inserting record
        $db->execute("
            INSERT INTO
              `users` (
                `username`,
                `password`,
                `email`,
                `last_activity`,
                `is_online`
              )
            VALUES (?, ?, ?, ?, 1)
        ", [
            $data['username'],
            password_hash($data['password'], PASSWORD_DEFAULT),
            $data['email'],
            time()
        ]);

        $_SESSION['authenticated'] = true;
        $_SESSION['username'] = $data['username'];
        $_SESSION['userId'] = $db->getLastInsertId();

        return true;
    }

    public function login($data)
    {
        if (
            empty($data['username'])
            || empty($data['password'])
        ) {
            throw new Exception('All fields are required');
        }

        $db = DB::getInstance();

        $user = $db->fetchOne("SELECT * FROM `users` WHERE `username` = ?", [$data['username']]);

        if (!$user) {
            throw new Exception('User not found');
        }

        if (!password_verify($data['password'], $user['password'])) {
            throw new Exception('Password is incorrect');
        }

        $_SESSION['authenticated'] = true;
        $_SESSION['username'] = $user['username'];
        $_SESSION['userId'] = $user['id'];

        return true;
    }

    public function getOnline()
    {
        $db = DB::getInstance();

        $users = $db->fetchAll("SELECT * FROM `users` WHERE `is_online` = 1");

        return $users;
    }
}

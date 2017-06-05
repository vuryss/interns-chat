<?php


class MessagesModel
{
    public function addMessage($userId, $data)
    {
        if (empty($data['message'])) {
            throw new Exception('Empty message');
        }

        $db = DB::getInstance();

        $db->execute(
            "INSERT INTO `messages` (user_id, created, message) VALUES (?, ?, ?)",
            [$userId, time(), $data['message']]
        );

        return true;
    }

    public function getAllMessages()
    {
        $db = DB::getInstance();

        $messages = $db->fetchAll("
          SELECT 
            m_.`id`,
            m_.`created`,
            m_.`message`,
            u_.`username`
          FROM 
            `messages` AS m_
          INNER JOIN
            `users` AS u_ ON u_.`id` = m_.`user_id`
          ORDER BY 
            `created` ASC
        ");

        return $messages;
    }

    public function getMessagesSinceId($id)
    {
        $db = DB::getInstance();

        $messages = $db->fetchAll("
          SELECT 
            m_.`id`,
            m_.`created`,
            m_.`message`,
            u_.`username`
          FROM 
            `messages` AS m_
          INNER JOIN
            `users` AS u_ ON u_.`id` = m_.`user_id`
          WHERE 
            m_.`id` > ?
          ORDER BY 
            `created` ASC
        ", [$id]);

        return $messages;
    }
}
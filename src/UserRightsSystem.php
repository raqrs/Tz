<?php
require 'vendor/autoload.php';
use Dotenv\Dotenv;

class UserRightsSystem {
    private $mysqli;

    public function __construct() {
        $dotenv = Dotenv::createImmutable(__DIR__);
        $dotenv->load();

        $dbHost = $_ENV['DB_HOST'];
        $dbName = $_ENV['DB_NAME'];
        $dbUser = $_ENV['DB_USER'];
        $dbPassword = $_ENV['DB_PASSWORD'];

        $this->mysqli = new mysqli($dbHost, $dbUser, $dbPassword, $dbName);
        if ($this->mysqli->connect_error) {
            throw new Exception('Connect Error (' . $this->mysqli->connect_errno . ') ' . $this->mysqli->connect_error);
        }
    }

    private function query($sql, $params = []) {
        $stmt = $this->mysqli->prepare($sql);
        if ($params) {
            $stmt->bind_param(str_repeat('i', count($params)), ...$params);
        }
        if (!$stmt->execute()) {
            throw new Exception($stmt->error);
        }
        return $stmt->get_result();
    }

    private function validateUserId($userId) {
        if (!is_int($userId) || $userId <= 0) {
            throw new Exception('Invalid userId');
        }
        $result = $this->query("SELECT id FROM users WHERE id = ?", [$userId]);
        if ($result->num_rows === 0) {
            throw new Exception('User not found');
        }
    }

    private function validateGroupId($groupId) {
        if (!is_int($groupId) || $groupId <= 0) {
            throw new Exception('Invalid groupId');
        }
        $result = $this->query("SELECT id FROM groups WHERE id = ?", [$groupId]);
        if ($result->num_rows === 0) {
            throw new Exception('Group not found');
        }
    }

    private function validateRightId($rightId) {
        if (!is_int($rightId) || $rightId <= 0) {
            throw new Exception('Invalid rightId');
        }
        $result = $this->query("SELECT id FROM rights WHERE id = ?", [$rightId]);
        if ($result->num_rows === 0) {
            throw new Exception('Right not found');
        }
    }

    public function addUserToGroup($userId, $groupId) {
        $this->validateUserId($userId);
        $this->validateGroupId($groupId);
        $this->query("INSERT INTO user_groups (user_id, group_id) VALUES (?, ?)", [$userId, $groupId]);
        return ['success' => true];
    }

    public function removeUserFromGroup($userId, $groupId) {
        $this->validateUserId($userId);
        $this->validateGroupId($groupId);
        $this->query("DELETE FROM user_groups WHERE user_id = ? AND group_id = ?", [$userId, $groupId]);
        return ['success' => true];
    }

    public function listGroups() {
        $result = $this->query("SELECT * FROM groups");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getUserRights($userId) {
        $this->validateUserId($userId);

        // Получение всех прав
        $result = $this->query("SELECT name FROM rights");
        $rights = [];
        while ($row = $result->fetch_assoc()) {
            $rights[$row['name']] = false;
        }

        // Получение всех прав пользователя через группы
        $result = $this->query("
            SELECT r.name
            FROM rights r
            JOIN group_rights gr ON r.id = gr.right_id
            JOIN user_groups ug ON gr.group_id = ug.group_id
            WHERE ug.user_id = ?", [$userId]);

        while ($row = $result->fetch_assoc()) {
            $rights[$row['name']] = true;
        }

        // Применение временных блокировок прав
        $blockedRights = $this->query("
            SELECT r.name
            FROM rights r
            JOIN temporary_blocked_rights tb ON r.id = tb.right_id
            WHERE tb.user_id = ?", [$userId]);

        while ($row = $blockedRights->fetch_assoc()) {
            $rights[$row['name']] = false;
        }

        return $rights;
    }

    public function addRightToGroup($groupId, $rightId) {
        $this->validateGroupId($groupId);
        $this->validateRightId($rightId);
        $this->query("INSERT INTO group_rights (group_id, right_id) VALUES (?, ?)", [$groupId, $rightId]);
        return ['success' => true];
    }

    public function removeRightFromGroup($groupId, $rightId) {
        $this->validateGroupId($groupId);
        $this->validateRightId($rightId);
        $this->query("DELETE FROM group_rights WHERE group_id = ? AND right_id = ?", [$groupId, $rightId]);
        return ['success' => true];
    }
}


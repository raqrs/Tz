<?php
header('Content-Type: application/json');
require 'vendor/autoload.php';
require 'src/UserRightsSystem.php';

$method = $_SERVER['REQUEST_METHOD'];
$uri = explode('/', trim($_SERVER['REQUEST_URI'], '/'));

$system = new UserRightsSystem();

try {
    switch ($uri[0]) {
        case 'addUserToGroup':
            if ($method == 'POST') {
                $data = json_decode(file_get_contents('php://input'), true);
                echo json_encode($system->addUserToGroup($data['userId'], $data['groupId']));
            }
            break;
        case 'removeUserFromGroup':
            if ($method == 'POST') {
                $data = json_decode(file_get_contents('php://input'), true);
                echo json_encode($system->removeUserFromGroup($data['userId'], $data['groupId']));
            }
            break;
        case 'listGroups':
            if ($method == 'GET') {
                echo json_encode($system->listGroups());
            }
            break;
        case 'getUserRights':
            if ($method == 'GET') {
                $userId = intval($uri[1]);
                echo json_encode($system->getUserRights($userId));
            }
            break;
        case 'addRightToGroup':
            if ($method == 'POST') {
                $data = json_decode(file_get_contents('php://input'), true);
                echo json_encode($system->addRightToGroup($data['groupId'], $data['rightId']));
            }
            break;
        case 'removeRightFromGroup':
            if ($method == 'POST') {
                $data = json_decode(file_get_contents('php://input'), true);
                echo json_encode($system->removeRightFromGroup($data['groupId'], $data['rightId']));
            }
            break;
        default:
            http_response_code(404);
            echo json_encode(['error' => 'Not Found']);
            break;
    }
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['error' => $e->getMessage()]);
}
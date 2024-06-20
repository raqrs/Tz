<?php
use PHPUnit\Framework\TestCase;

class UserRightsSystemTest extends TestCase {
    private $system;

    protected function setUp() {
        $this->system = new UserRightsSystem();
    }

    public function testAddUserToGroup() {
        $response = $this->system->addUserToGroup(1, 1);
        $this->assertEquals(array('success' => true), $response);
    }

    public function testRemoveUserFromGroup() {
        $response = $this->system->removeUserFromGroup(1, 1);
        $this->assertEquals(array('success' => true), $response);
    }

    public function testListGroups() {
        $response = $this->system->listGroups();
        $this->assertInternalType('array', $response);
    }

    public function testGetUserRights() {
        $response = $this->system->getUserRights(1);
        $this->assertArrayHasKey('send_messages', $response);
        $this->assertArrayHasKey('service_api', $response);
        $this->assertArrayHasKey('debug', $response);
    }

    public function testAddRightToGroup() {
        $response = $this->system->addRightToGroup(1, 1);
        $this->assertEquals(array('success' => true), $response);
    }

    public function testRemoveRightFromGroup() {
        $response = $this->system->removeRightFromGroup(1, 1);
        $this->assertEquals(array('success' => true), $response);
    }
}
?>

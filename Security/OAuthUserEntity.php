<?php

namespace SpiritDev\Bundle\OAuth2ClientBundle\Security;

class OAuthUserEntity {

	protected $userId;
    protected $userUsername;
    protected $userEmail;
    protected $userRole;

    private $sessionErrorString = "session_error";

    public function setUserEntity($id, $username, $email, $role) {
        
        $this->serializeSet($id, $username, $email, $role);

        return $this->getUserEntity();
    }

    public function getUserEntity() {

        return $this->serializeGet();
    }

    public function isValid() {
        $issue = true;
        $error = $this->sessionErrorString;

        if($this->getId() == $error || 
            $this->getUsername() == $error || 
            $this->getEmail() == $error || 
            $this->getRole() == $error) {
            
            $issue = false;
        }
        return $issue;
    }

    public function deleteSessionVars() {
        if(isset($_SESSION['user_id'])) {
            unset($_SESSION['user_id']);
        }
        if(isset($_SESSION['user_username'])) {
            unset($_SESSION['user_username']);
        }
        if(isset($_SESSION['user_email'])) {
            unset($_SESSION['user_email']);
        }
        if(isset($_SESSION['user_role'])) {
            unset($_SESSION['user_role']);
        }
    }

    private function serializeSet($id, $username, $email, $role) {
        // set Session vars & local vars
        $this->setId($id);
        $this->setUsername($username);
        $this->setEmail($email);
        $this->setRole($role);       
    }

    private function serializeGet() {

        return array(
            "user_id" => $this->getId(),
            "user_username" => $this->getUsername(),
            "user_email" => $this->getEmail(),
            "user_role" => $this->getRole()
        );
    }

    private function setId($id) {
        $this->userId = $id;
        $_SESSION['user_id'] = $id;
    }
    
    private function getId() {
        if(isset($_SESSION['user_id'])) {
            return $_SESSION['user_id'];
        }
        else {
            return $this->sessionErrorString;
        }
    }

    private function setUsername($username) {
        $this->userUsername = $username;
        $_SESSION['user_username'] = $username;
    }
    
    public function getUsername() {
        if(isset($_SESSION['user_username'])) {
            return $_SESSION['user_username'];
        }
        else {
            return $this->sessionErrorString;
        }
    }

    private function setEmail($email) {
        $this->userEmail = $email;
        $_SESSION['user_email'] = $email;
    }
    
    private function getEmail() {
        if(isset($_SESSION['user_email'])) {
            return $_SESSION['user_email'];
        }
        else {
            return $this->sessionErrorString;
        }
    }

    private function setRole($role) {
        $this->userRole = $role;
        $_SESSION['user_role'] = $role;
    }
    
    private function getRole() {
        if(isset($_SESSION['user_role'])) {
            return $_SESSION['user_role'];
        }
        else {
            return $this->sessionErrorString;
        }
    }

}
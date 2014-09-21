<?php

/** ** ** ** ** ** ** ** ** ** ** ** ** ** ** ** ** ** ** ** ** ** ** ** ** **
 **             ___                                                         **
 **            /   \        _      _ _          ___                         **
 **           / /\  \ _ __ (_)_ _ (_) |_       |   \  ___    __             **
 **           \/ /  /| `_ \| | `_\| |  _| ___  | |\ \/ _ \  / /  __/        **
 **           /  / /\| |_) | | |  | | |  |___| | |/ /  __/\/ /  \__\        **
 **           \  \/ /| ,__/|_|_|  |_|_|        |___/ \___| _/   /           **
 **            \___/ |_|                                                    **
 **                                                 ____                    **
 **                    ____                       /\ ___/\                  **
 **                  /\ ___/\                     \ \___\ \                 **
 **                  \ \___\ \__________ __________\/____\/                 **
 **                   \/____\/__________|__________/\ ___/\                 **
 **                   /\___ /\                     \ \___\ \                **
 **                   \ \___\ \                     \/____\/                **
 **                    \/____\/                                             **
 **                                                                         **
 **          Jean Bordat                                                    **
 **          Since 2K10 until today                                         **
 **                                                                         **
 ** ** ** ** ** ** ** ** ** ** ** ** ** ** ** ** ** ** ** ** ** ** ** ** ** **/

namespace SpiritDev\Bundle\OAuth2ClientBundle\Security;

use SpiritDev\Bundle\OAuth2ClientBundle\Model\OAuthUserEntityInterface;

class OAuthUserEntity implements OAuthUserEntityInterface {

	protected $userId;
    protected $userUsername;
    protected $userEmail;
    protected $userRole;
    protected $userGuid;

    private $sessionErrorString = "session_error";

    /**
     * Function which sets all user values
     * @param Int    $id       Id of user
     * @param String $username User name
     * @param String $email    User email
     * @param Array  $role     User roles
     * @return User User entity
     *
     * @author Jean BORDAT <bordat.jean@gmail.com>
     * Date    2014-06-19
     * Updated by Jean Bordat <bordat.jean@gmail.com> the 2014-07-08
     */
    public function setUserEntity($id, $username, $email, $role) {
        
        $this->serializeSet($id, $username, $email, $role);

        return $this->getUserEntity();
    }

    /**
     * Getter for user object
     * @return User User entity
     *
     * @author Jean BORDAT <bordat.jean@gmail.com>
     * Date    2014-06-19
     * Updated by Jean Bordat <bordat.jean@gmail.com> the 2014-07-08
     */
    public function getUserEntity() {

        return $this->serializeGet();
    }

    /**
     * Utility function which will check data availability
     * @return boolean Valid or not
     *
     * @author Jean BORDAT <bordat.jean@gmail.com>
     * Date    2014-06-19
     * Updated by Jean Bordat <bordat.jean@gmail.com> the 2014-07-08
     */
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

    /**
     * Function which will delete user SESSION datas
     *
     * @author Jean BORDAT <bordat.jean@gmail.com>
     * Date    2014-06-19
     * Updated by Jean Bordat <bordat.jean@gmail.com> the 2014-07-08
     */
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

    /**
     * Function which will serialize sets
     * @param  Int    $id       User Id
     * @param  String $username User name
     * @param  String $email    User email
     * @param  Array  $role     User roles
     *
     * @author Jean BORDAT <bordat.jean@gmail.com>
     * Date    2014-06-19
     * Updated by Jean Bordat <bordat.jean@gmail.com> the 2014-07-08
     */
    private function serializeSet($id, $username, $email, $role, $guid) {
        // set Session vars & local vars
        $this->setId($id);
        $this->setUsername($username);
        $this->setEmail($email);
        $this->setRole($role);
    }

    /**
     * Function which will retrieve and format user datas
     * @return Array Container fo user informations
     *
     * @author Jean BORDAT <bordat.jean@gmail.com>
     * Date    2014-06-19
     * Updated by Jean Bordat <bordat.jean@gmail.com> the 2014-07-08
     */
    private function serializeGet() {

        return array(
            "user_id" => $this->getId(),
            "user_username" => $this->getUsername(),
            "user_email" => $this->getEmail(),
            "user_role" => $this->getRole()
        );
    }

    /**
     * Function will sets SESSION data
     * @param Int $id User id
     *
     * @author Jean BORDAT <bordat.jean@gmail.com>
     * Date    2014-06-19
     * Updated by Jean Bordat <bordat.jean@gmail.com> the 2014-07-08
     */
    private function setId($id) {
        $this->userId = $id;
        $_SESSION['user_id'] = $id;
    }
    
    /**
     * Function which return SESSION data
     * @return Int User id or error phrase
     *
     * @author Jean BORDAT <bordat.jean@gmail.com>
     * Date    2014-06-19
     * Updated by Jean Bordat <bordat.jean@gmail.com> the 2014-07-08
     */
    private function getId() {
        if(isset($_SESSION['user_id'])) {
            return $_SESSION['user_id'];
        }
        else {
            return $this->sessionErrorString;
        }
    }

    /**
     * Function will sets SESSION data
     * @param String $username User name
     *
     * @author Jean BORDAT <bordat.jean@gmail.com>
     * Date    2014-06-19
     * Updated by Jean Bordat <bordat.jean@gmail.com> the 2014-07-08
     */
    private function setUsername($username) {
        $this->userUsername = $username;
        $_SESSION['user_username'] = $username;
    }
    
    /**
     * Function which return SESSION data
     * @return String User name or error phrase
     *
     * @author Jean BORDAT <bordat.jean@gmail.com>
     * Date    2014-06-19
     * Updated by Jean Bordat <bordat.jean@gmail.com> the 2014-07-08
     */
    public function getUsername() {
        if(isset($_SESSION['user_username'])) {
            return $_SESSION['user_username'];
        }
        else {
            return $this->sessionErrorString;
        }
    }

    /**
     * Function will sets SESSION data
     * @param String $email User id
     *
     * @author Jean BORDAT <bordat.jean@gmail.com>
     * Date    2014-06-19
     * Updated by Jean Bordat <bordat.jean@gmail.com> the 2014-07-08
     */
    private function setEmail($email) {
        $this->userEmail = $email;
        $_SESSION['user_email'] = $email;
    }
    
    /**
     * Function which return SESSION data
     * @return String User email or error phrase
     *
     * @author Jean BORDAT <bordat.jean@gmail.com>
     * Date    2014-06-19
     * Updated by Jean Bordat <bordat.jean@gmail.com> the 2014-07-08
     */
    private function getEmail() {
        if(isset($_SESSION['user_email'])) {
            return $_SESSION['user_email'];
        }
        else {
            return $this->sessionErrorString;
        }
    }

    /**
     * Function will sets SESSION data
     * @param Array $id User roles
     *
     * @author Jean BORDAT <bordat.jean@gmail.com>
     * Date    2014-06-19
     * Updated by Jean Bordat <bordat.jean@gmail.com> the 2014-07-08
     */
    private function setRole($role) {
        $this->userRole = $role;
        $_SESSION['user_role'] = $role;
    }
    
    /**
     * Function which return SESSION data
     * @return Array User roles or error phrase
     *
     * @author Jean BORDAT <bordat.jean@gmail.com>
     * Date    2014-06-19
     * Updated by Jean Bordat <bordat.jean@gmail.com> the 2014-07-08
     */
    private function getRole() {
        if(isset($_SESSION['user_role'])) {
            return $_SESSION['user_role'];
        }
        else {
            return $this->sessionErrorString;
        }
    }

    /**
     * Function which returns SESSION data
     * @return String    User guid
     *  
     * @author Jean BORDAT <bordat.jean@gmail.com>
     * @date    21/09/2014
     * @time    17:14
     */
    public function getUserGuid() {
        return $this->userGuid;
    }

    /**
     * Function will sets SESSION data
     * @param Array User roles or error phrase
     *  
     * @author Jean BORDAT <bordat.jean@gmail.com>
     * @date    21/09/2014
     * @time    17:14
     */
    public function setUserGuid($userGuid) {
        $this->userGuid = $userGuid;
    }

}
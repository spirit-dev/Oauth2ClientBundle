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

use \DateTime;
use \DateInterval;

class OAuthUserGrants {

    protected $accessToken;
    protected $refreshToken;
    protected $scope;
    protected $expiresIn;
    protected $tokenType; 

    protected $dateIncome;
    protected $dateOutcome;

    protected $sessionErrorString = "session_error";

    /**
     * Initialisation of Class datas
     * @param String $accessToken  Access Token consummed by API
     * @param String $refreshToken Refresh Token consummed by API
     * @param String $scope        Determines OAuth2 needs
     * @param Date   $expiresIn    Refresh token date expiration
     * @param String $tokenType    Type of token
     *
     * @author Jean BORDAT <bordat.jean@gmail.com>
     * Date    2014-06-19
     * Updated by Jean Bordat <jean.bordat@steria.com> the 2014-07-08
     */
    public function setGrants($accessToken, $refreshToken, $scope, $expiresIn, $tokenType) {

        $this->accessToken = $accessToken;
        $this->refreshToken = $refreshToken;
        $this->scope = $scope;
        $this->expiresIn = $expiresIn;
        $this->tokenType = $tokenType;

        $this->setTimeout();

        $this->setSessionVars();

    }

    /**
     * Function checking if acess token is expired
     * @return boolean Expired or not
     *
     * @author Jean BORDAT <bordat.jean@gmail.com>
     * Date    2014-06-19
     * Updated by Jean Bordat <jean.bordat@steria.com> the 2014-07-08
     */
    public function hasExpired() {

        $this->getSessionVars();
        
        if ($this->dateOutcome == $this->sessionErrorString) {
            return true;
        }

        $date1 = new DateTime("now");
        $date2 = new DateTime($this->dateOutcome->format('Y-m-d H:i:s'));

        if ($date1 < $date2) {
            return true;
        }
        else {
            return false;
        }
    }

    /**
     * Getter for Access token
     * @return String Acecss token
     *
     * @author Jean BORDAT <bordat.jean@gmail.com>
     * Date    2014-06-19
     * Updated by Jean Bordat <jean.bordat@steria.com> the 2014-07-08
     */
    public function getAccessToken() {
        
        $this->getSessionVars();
        
        return $this->accessToken;
    }

    /**
     * Getter for Refresh token
     * @return String Refresh token
     *
     * @author Jean BORDAT <bordat.jean@gmail.com>
     * Date    2014-06-19
     * Updated by Jean Bordat <jean.bordat@steria.com> the 2014-07-08
     */
    public function getRefreshToken() {
        
        $this->getSessionVars();
        
        return $this->refreshToken;
    }

    /**
     * Getter for expiration acces token date
     * @return Date Expiration date
     *
     * @author Jean BORDAT <bordat.jean@gmail.com>
     * Date    2014-06-19
     * Updated by Jean Bordat <jean.bordat@steria.com> the 2014-07-08
     */
    public function getDateOutcome() {
        $this->getSessionVars();
        if ($this->dateOutcome != $this->sessionErrorString) {
            return $this->dateOutcome->format('Y-m-d H:i:s');
        }
        else {
            return $this->dateOutcome;
        }
    }

    /**
     * Function calculating expiration of access token
     *
     * @author Jean BORDAT <bordat.jean@gmail.com>
     * Date    2014-06-19
     * Updated by Jean Bordat <jean.bordat@steria.com> the 2014-07-08
     */
    private function setTimeout() {

        $this->dateIncome = new DateTime();
        $this->dateOutcome = $this->dateIncome->add(new DateInterval('PT'.$this->expiresIn.'S'));

    }

    /**
     * Function inserting in SESSION necessary datas
     *
     * @author Jean BORDAT <bordat.jean@gmail.com>
     * Date    2014-06-19
     * Updated by Jean Bordat <jean.bordat@steria.com> the 2014-07-08
     */
    private function setSessionVars() {
        $_SESSION['oaug_access_token'] = $this->accessToken;
        $_SESSION['oaug_refresh_token'] = $this->refreshToken;
        $_SESSION['oaug_scope'] = $this->scope;
        $_SESSION['oaug_expires_in'] = $this->expiresIn;
        $_SESSION['oaug_token_type'] = $this->tokenType;
        $_SESSION['oaug_date_income'] = $this->dateIncome;
        $_SESSION['oaug_date_outcome'] = $this->dateOutcome;
    }

    /**
     * Function retrieving SESSION datas
     *
     * @author Jean BORDAT <bordat.jean@gmail.com>
     * Date    2014-06-19
     * Updated by Jean Bordat <jean.bordat@steria.com> the 2014-07-08
     */
    private function getSessionVars() {
        if(!isset($_SESSION['oaug_access_token'])) {
            $this->accessToken = $this->sessionErrorString;
        }
        else {
            $this->accessToken = $_SESSION['oaug_access_token'];
        }

        if(!isset($_SESSION['oaug_refresh_token'])) {
            $this->refreshToken = $this->sessionErrorString;
        }
        else {
            $this->refreshToken = $_SESSION['oaug_refresh_token'];
        }

        if(!isset($_SESSION['oaug_scope'])) {
            $this->scope = $this->sessionErrorString;
        }
        else {
            $this->scope = $_SESSION['oaug_scope'];
        }

        if(!isset($_SESSION['oaug_expires_in'])) {
            $this->expiresIn = $this->sessionErrorString;
        }
        else {
            $this->expiresIn = $_SESSION['oaug_expires_in'];
        }

        if(!isset($_SESSION['oaug_token_type'])) {
            $this->tokenType = $this->sessionErrorString;
        }
        else {
            $this->tokenType = $_SESSION['oaug_token_type'];
        }

        if(!isset($_SESSION['oaug_date_income'])) {
            $this->dateIncome = $this->sessionErrorString;
        }
        else {
            $this->dateIncome = $_SESSION['oaug_date_income'];
        }

        if(!isset($_SESSION['oaug_date_outcome'])) {
            $this->dateOutcome = $this->sessionErrorString;
        }
        else {
            $this->dateOutcome = $_SESSION['oaug_date_outcome'];
        }

    }

    /**
     * Function deleting SESSION datas
     *
     * @author Jean BORDAT <bordat.jean@gmail.com>
     * Date    2014-06-19
     * Updated by Jean Bordat <jean.bordat@steria.com> the 2014-07-08
     */
    public function deleteSessionVars() {
        if(isset($_SESSION['oaug_access_token'])) {
            unset($_SESSION['oaug_access_token']);
        }
        
        if(isset($_SESSION['oaug_refresh_token'])) {
            unset($_SESSION['oaug_refresh_token']);
        }
        
        if(isset($_SESSION['oaug_scope'])) {
            unset($_SESSION['oaug_scope']);
        }
        
        if(isset($_SESSION['oaug_expires_in'])) {
            unset($_SESSION['oaug_expires_in']);
        }
        
        if(isset($_SESSION['oaug_token_type'])) {
            unset($_SESSION['oaug_token_type']);
        }
        
        if(isset($_SESSION['oaug_date_income'])) {
            unset($_SESSION['oaug_date_income']);
        }
        
        if(isset($_SESSION['oaug_date_outcome'])) {
            unset($_SESSION['oaug_date_outcome']);
        }
    }

    /**
     * Function checking SESSION datas avalability
     * @return boolean Valid or not
     *
     * @author Jean BORDAT <bordat.jean@gmail.com>
     * Date    2014-06-19
     * Updated by Jean Bordat <jean.bordat@steria.com> the 2014-07-08
     */
    public function isValid() {
        $issue = true;
        $this->getSessionVars();
        $error = $this->sessionErrorString;
        if($this->accessToken == $error ||
            $this->refreshToken == $error ||
            $this->scope == $error ||
            $this->expiresIn == $error ||
            $this->tokenType == $error ||
            $this->dateIncome == $error ||
            $this->dateOutcome == $error) {

            $issue = false;
        }

        return $issue;
    }
}
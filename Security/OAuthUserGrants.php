<?php

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

    public function setGrants($accessToken, $refreshToken, $scope, $expiresIn, $tokenType) {

        $this->accessToken = $accessToken;
        $this->refreshToken = $refreshToken;
        $this->scope = $scope;
        $this->expiresIn = $expiresIn;
        $this->tokenType = $tokenType;

        $this->setTimeout();

        $this->setSessionVars();

    }

    public function hasExpired() {

        $this->getSessionVars();
        
        if ($this->dateOutcome == $sessionErrorString) {
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

    public function getAccessToken() {
        
        $this->getSessionVars();
        
        return $this->accessToken;
    }

    public function getRefreshToken() {
        
        $this->getSessionVars();
        
        return $this->refreshToken;
    }

    public function getDateOutcome() {
        $this->getSessionVars();
        if ($this->dateOutcome != $sessionErrorString) {
            return $this->dateOutcome->format('Y-m-d H:i:s');
        }
        else {
            return $this->dateOutcome;
        }
    }

    private function setTimeout() {

        $this->dateIncome = new DateTime();
        $this->dateOutcome = $this->dateIncome->add(new DateInterval('PT'.$this->expiresIn.'S'));

    }

    private function setSessionVars() {
        $_SESSION['oaug_access_token'] = $this->accessToken;
        $_SESSION['oaug_refresh_token'] = $this->refreshToken;
        $_SESSION['oaug_scope'] = $this->scope;
        $_SESSION['oaug_expires_in'] = $this->expiresIn;
        $_SESSION['oaug_token_type'] = $this->tokenType;
        $_SESSION['oaug_date_income'] = $this->dateIncome;
        $_SESSION['oaug_date_outcome'] = $this->dateOutcome;
    }

    private function getSessionVars() {
        if(!isset($_SESSION['oaug_access_token'])) {
            $this->accessToken = $sessionErrorString;
        }
        else {
            $this->accessToken = $_SESSION['oaug_access_token'];
        }

        if(!isset($_SESSION['oaug_refresh_token'])) {
            $this->refreshToken = $sessionErrorString;
        }
        else {
            $this->refreshToken = $_SESSION['oaug_refresh_token'];
        }

        if(!isset($_SESSION['oaug_scope'])) {
            $this->scope = $sessionErrorString;
        }
        else {
            $this->scope = $_SESSION['oaug_scope'];
        }

        if(!isset($_SESSION['oaug_expires_in'])) {
            $this->expiresIn = $sessionErrorString;
        }
        else {
            $this->expiresIn = $_SESSION['oaug_expires_in'];
        }

        if(!isset($_SESSION['oaug_token_type'])) {
            $this->tokenType = $sessionErrorString;
        }
        else {
            $this->tokenType = $_SESSION['oaug_token_type'];
        }

        if(!isset($_SESSION['oaug_date_income'])) {
            $this->dateIncome = $sessionErrorString;
        }
        else {
            $this->dateIncome = $_SESSION['oaug_date_income'];
        }

        if(!isset($_SESSION['oaug_date_outcome'])) {
            $this->dateOutcome = $sessionErrorString;
        }
        else {
            $this->dateOutcome = $_SESSION['oaug_date_outcome'];
        }

    }

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
}
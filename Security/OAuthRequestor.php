<?php

namespace SpiritDev\Bundle\OAuth2ClientBundle\Security;

use Symfony\Component\HttpFoundation\Session\Session;

class OAuthRequestor {

    protected $tokenUri = null;
    protected $getUserUri = null;
    protected $grantTypePassword = "password";
    protected $grantTypeRefresh = "refresh_token";
    protected $clientId = null;
    protected $clientSecret = null;
    protected $redirectUri = null;

    protected $userGrants = null;
    protected $browser = null;
    protected $userEntity = null;

    public function __construct($tokenUri, $getUserUri, $clientId, $clientSecret, 
        $redirectUri, OAuthUserGrants $userGrants, $userEntity, $browser) {

        $this->tokenUri = $tokenUri;
        $this->getUserUri = $getUserUri;
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->redirectUri = $redirectUri;

        $this->userGrants = $userGrants;
        $this->browser = $browser;
        $this->userEntity = $userEntity;

    }

    public function setUserGrantsManager(OAuthUserGrants $userGrants) {

        $this->userGrants = $userGrants;
    }

    public function setBroswer($browser) {

        $this->browser = $browser;
    }

    public function setUserEntity($userEntity) {

        $this->userEntity = $userEntity;
    }

    public function getRedirectUri() {

        return $this->redirectUri;
    }

    public function getAccessToken() {

        return $this->userGrants->getAccessToken();
    }

    public function getUserGrants($usr, $psw) {

        $session = new Session();
        // $session->invalidate();
        try {
            $session->start();
        } catch (\Exception $e) {
            
        }
        // if($session->isStarted()) {
        //  echo "SESSION STARTED";
        //  $session->start();
        // }

        $req = $this->formatUserGrantUri($usr, $psw);

        $serverResponse = $this->browser->get($req);

        $response = json_decode($serverResponse->getContent(), true);       

        return $this->avoidResponse($response, $usr);
    }

    public function checkStatus() {

        $old_username = $this->userEntity->getUsername();

        if ($this->userGrants->hasExpired()) {
            
            // get actual token refresh
            $token_refresh = $this->userGrants->getRefreshToken();
            
            // update values via get request
            $this->getNewAccessToken($token_refresh);

            // return access token
            return array(
                "response_header" => "new refresh token",
                "response_type" => "token",
                "response_text" => "Token refreshed + User refresh",
                "response_data" => array(
                    "access_token" => $this->getAccessToken(),
                    "old_username" => $old_username,
                    "actual_username" => $this->userEntity->getUsername(),
                    "expires_at" => $this->getTokenDateOut()
                )
            );
        }

        // return access token
        return array(
            "response_header" => "",
            "response_type" => "token",
            "response_text" => "Token refreshed",
            "response_data" => array(
                "access_token" => $this->getAccessToken(),
                    "old_username" => $old_username,
                    "actual_username" => $this->userEntity->getUsername(),
                    "expires_at" => $this->getTokenDateOut()
            )
        );
    }

    public function getNewAccessToken($refresh_token) {

        $req = $this->formatRefreshTokenUri($refresh_token);

        $serverResponse = $this->browser->get($req);

        $response = json_decode($serverResponse->getContent(), true);

        $this->avoidResponse($response, $this->userEntity->getUsername());

        return 0;
    }

    public function getRemoteUser($usn) {

        $req = $this->formatGetUserUri($usn, $this->getAccessToken());

        $serverResponse = $this->browser->get($req);

        return json_decode($serverResponse->getContent(), true);
    }

    private function formatUserGrantUri($usr, $psw) {

        return $this->tokenUri."?grant_type=".$this->grantTypePassword."&client_id=".$this->clientId."&client_secret=".$this->clientSecret."&username=".$usr."&password=".$psw."&redirect_uri=".$this->redirectUri;
    }

    private function formatRefreshTokenUri($refresh_token) {

        return $this->tokenUri."?client_id=".$this->clientId."&client_secret=".$this->clientSecret."&grant_type=".$this->grantTypeRefresh."&refresh_token=".$refresh_token;
    }

    private function formatGetUserUri($usn, $accessToken) {

        return $this->getUserUri."?username=".$usn."&access_token=".$accessToken;
    }

    private function avoidResponse($response, $usr) {

        $mainReturn = 500;

        if ($response == null) {
            $mainReturn = 500;
        }
        elseif(array_key_exists('error', $response)) {
            $mainReturn = 206;
        }
        elseif (array_key_exists('access_token', $response) && 
                array_key_exists('refresh_token', $response) &&
                array_key_exists('scope', $response) &&
                array_key_exists('expires_in', $response) &&
                array_key_exists('token_type', $response)) {

            $subMainReturn = 500;

            // destroy user grants session vars
            $this->userGrants->deleteSessionVars();

            $this->userGrants->setGrants($response['access_token'], $response['refresh_token'], $response['scope'], $response['expires_in'], $response['token_type']);

            //destroy user entity session vars
            $this->userEntity->deleteSessionVars();

            // getUserInformations (id, username, email, role)
            $user = $this->getRemoteUser($usr);

            // $this->test = $user;

            if (array_key_exists('message', $user) && $user["message"] == "User is not identified") {
                $subMainReturn = 206;
            }
            else {
                // setLocalEntity(id, username, email, role)
                $this->userEntity->setUserEntity(
                    $user['id'],
                    $user['username'],
                    $user['email'],
                    $user['role'][0]
                );
                $subMainReturn = 200;
            }
            return $subMainReturn;
        }
        else {            
            $mainReturn = 500;
        }
        return $mainReturn;
    }

    public function getTokenDateOut() {

        return $this->userGrants->getDateOutcome();
    }

    public function deleteRemoteToken() {

        // destroy user grants session vars
        return $this->userGrants->deleteSessionVars();
    }
}
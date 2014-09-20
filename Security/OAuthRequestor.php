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

use Symfony\Component\HttpFoundation\Session\Session;

use SpiritDev\Bundle\OAuth2ClientBundle\Model\OAuthRequestorInterface;
use SpiritDev\Bundle\OAuth2ClientBundle\Util\UriFormaterUtil as UriFormater;

class OAuthRequestor implements OAuthRequestorInterface {

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

    /**
     * Constructor for OAuthRequestor
     * @param String          $tokenUri     URL to fetch OAuth2 tokens
     * @param String          $getUserUri   URL to fetch user informations
     * @param String          $clientId     Unique token provided by API
     * @param String          $clientSecret Secret pass to contact API
     * @param String          $redirectUri  URL redirection after OAuth grants
     * @param OAuthUserGrants $userGrants   Class storing user OAuth values
     * @param User            $userEntity   Class storing user datas
     * @param Buzz            $browser      Buzz utils for cUrl requests
     *
     * @author Jean BORDAT <bordat.jean@gmail.com>
     * Date    2014-06-19
     * Updated by Jean Bordat <jean.bordat@steria.com> the 2014-07-08
     */
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

    /**
     * Getter for redirect uri
     * @return String redirection uri after granting
     *
     * @author Jean BORDAT <bordat.jean@gmail.com>
     * Date    2014-06-19
     * Updated by Jean Bordat <jean.bordat@steria.com> the 2014-07-08
     */
    public function getRedirectUri() {

        return $this->redirectUri;
    }

    /**
     * Getter for access token
     * @return String Access token consumed by API
     *
     * @author Jean BORDAT <bordat.jean@gmail.com>
     * Date    2014-06-19
     * Updated by Jean Bordat <jean.bordat@steria.com> the 2014-07-08
     */
    public function getAccessToken() {

        return $this->userGrants->getAccessToken();
    }

    /**
     * Getter for user necessary datas
     * @param  Strnig $usr User name
     * @param  String $psw User password
     * @return Int      Granting http status
     *
     * @author Jean BORDAT <bordat.jean@gmail.com>
     * Date    2014-06-19
     * Updated by Jean Bordat <jean.bordat@steria.com> the 2014-07-08
     */
    public function getUserGrants($usr, $psw) {

        $session = new Session();

        try {
            $session->start();
        } catch (\Exception $e) {
            
        }

        $req = UriFormater::formatUserGrantUri($this->tokenUri,
            $this->grantTypePassword, $this->clientId, 
            $this->clientSecret, $this->redirectUri, $usr, $psw);

        $serverResponse = $this->browser->get($req);

        $response = json_decode($serverResponse->getContent(), true);       

        return $this->avoidResponse($response, $usr);
    }

    /**
     * Function wich checks OAuth2 validity
     * @return Array Container for UI requests
     *
     * @author Jean BORDAT <bordat.jean@gmail.com>
     * Date    2014-06-19
     * Updated by Jean Bordat <jean.bordat@steria.com> the 2014-07-08
     */
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

    /**
     * Function wich will call API to renew access token
     * @param  String $refresh_token Token consumed by API for access_token
     * @return Int                Zero
     *
     * @author Jean BORDAT <bordat.jean@gmail.com>
     * Date    2014-06-19
     * Updated by Jean Bordat <jean.bordat@steria.com> the 2014-07-08
     */
    public function getNewAccessToken($refresh_token) {

        $req = UriFormater::formatRefreshTokenUri($this->tokenUri,
            $this->clientId, $this->clientSecret, $this->grantTypeRefresh, 
            $refresh_token);

        $serverResponse = $this->browser->get($req);

        $response = json_decode($serverResponse->getContent(), true);

        $this->avoidResponse($response, $this->userEntity->getUsername());

        return 0;
    }

    /**
     * Function to retrieve user informations after grant success
     * @param  String $usn Username
     * @return Array      Container of user informations
     *
     * @author Jean BORDAT <bordat.jean@gmail.com>
     * Date    2014-06-19
     * Updated by Jean Bordat <jean.bordat@steria.com> the 2014-07-08
     */
    public function getRemoteUser($usn) {
        $req = UriFormater::formatGetUserUri($this->getUserUri, $usn,
            $this->getAccessToken());

        $serverResponse = $this->browser->get($req);

        return json_decode($serverResponse->getContent(), true);
    }

    /**
     * Function managing Requests responses and treates Session issues
     * @param  Array  $response Responses data
     * @param  String $usr      User name
     * @return Int              HTTP status return
     *
     * @author Jean BORDAT <bordat.jean@gmail.com>
     * Date    2014-06-19
     * Updated by Jean Bordat <jean.bordat@steria.com> the 2014-07-08
     */
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

            if ($user == null) {
                $subMainReturn = 410;
            }
            else {
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
            }
            return $subMainReturn;
        }
        else {            
            $mainReturn = 500;
        }
        return $mainReturn;
    }

    /**
     * Getter for token expiration date
     * @return [type] [description]
     *
     * @author Jean BORDAT <bordat.jean@gmail.com>
     * Date    2014-06-19
     * Updated by Jean Bordat <jean.bordat@steria.com> the 2014-07-08
     */
    public function getTokenDateOut() {

        return $this->userGrants->getDateOutcome();
    }

    /**
     * Function deleting SESSION var
     * @return OAuthUserGrants OAuthUserGrants treatment
     *
     * @author Jean BORDAT <bordat.jean@gmail.com>
     * Date    2014-06-19
     * Updated by Jean Bordat <jean.bordat@steria.com> the 2014-07-08
     */
    public function deleteRemoteToken() {

        // destroy user grants session vars
        return $this->userGrants->deleteSessionVars();
    }

    /**
     * Utility function to check SESSION validity
     * @return boolean valid or not valid
     *
     * @author Jean BORDAT <bordat.jean@gmail.com>
     * Date    2014-06-19
     * Updated by Jean Bordat <jean.bordat@steria.com> the 2014-07-08
     */
    public function isValid() {
        return $this->userGrants->isValid();
    }
}
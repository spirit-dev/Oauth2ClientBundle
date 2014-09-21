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

namespace SpiritDev\Bundle\OAuth2ClientBundle\Model;

interface OAuthRequestorInterface {

    /**
     * Getter for redirect uri
     * @return String redirection uri after granting
     *
     * @author Jean BORDAT <bordat.jean@gmail.com>
     * Date    2014-06-19
     * Updated by Jean Bordat <bordat.jean@gmail.com> the 2014-07-08
     */
    public function getRedirectUri();

    /**
     * Getter for access token
     * @return String Access token consumed by API
     *
     * @author Jean BORDAT <bordat.jean@gmail.com>
     * Date    2014-06-19
     * Updated by Jean Bordat <bordat.jean@gmail.com> the 2014-07-08
     */
    public function getAccessToken();

    /**
     * Getter for user necessary datas
     * @param  Strnig $usr User name
     * @param  String $psw User password
     * @return Int      Granting http status
     *
     * @author Jean BORDAT <bordat.jean@gmail.com>
     * Date    2014-06-19
     * Updated by Jean Bordat <bordat.jean@gmail.com> the 2014-07-08
     */
    public function getUserGrants($usr, $psw);

    /**
     * Function wich checks OAuth2 validity
     * @return Array Container for UI requests
     *
     * @author Jean BORDAT <bordat.jean@gmail.com>
     * Date    2014-06-19
     * Updated by Jean Bordat <bordat.jean@gmail.com> the 2014-07-08
     */
    public function checkStatus();

    /**
     * Function wich will call API to renew access token
     * @param  String $refresh_token Token consumed by API for access_token
     * @return Int                Zero
     *
     * @author Jean BORDAT <bordat.jean@gmail.com>
     * Date    2014-06-19
     * Updated by Jean Bordat <bordat.jean@gmail.com> the 2014-07-08
     */
    public function getNewAccessToken($refresh_token);

    /**
     * Function to retrieve user informations after grant success
     * @param  String $usn Username
     * @return Array      Container of user informations
     *
     * @author Jean BORDAT <bordat.jean@gmail.com>
     * Date    2014-06-19
     * Updated by Jean Bordat <bordat.jean@gmail.com> the 2014-07-08
     */
    public function getRemoteUser($usn);

    /**
     * Getter for token expiration date
     * @return [type] [description]
     *
     * @author Jean BORDAT <bordat.jean@gmail.com>
     * Date    2014-06-19
     * Updated by Jean Bordat <bordat.jean@gmail.com> the 2014-07-08
     */
    public function getTokenDateOut();

    /**
     * Function deleting SESSION var
     * @return OAuthUserGrants OAuthUserGrants treatment
     *
     * @author Jean BORDAT <bordat.jean@gmail.com>
     * Date    2014-06-19
     * Updated by Jean Bordat <bordat.jean@gmail.com> the 2014-07-08
     */
    public function deleteRemoteToken();

    /**
     * Utility function to check SESSION validity
     * @return boolean valid or not valid
     *
     * @author Jean BORDAT <bordat.jean@gmail.com>
     * Date    2014-06-19
     * Updated by Jean Bordat <bordat.jean@gmail.com> the 2014-07-08
     */
    public function isValid();
}
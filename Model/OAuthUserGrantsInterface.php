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

interface OAuthUserGrantsInterface {

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
    public function setGrants($accessToken, $refreshToken, $scope, $expiresIn, $tokenType);

    /**
     * Function checking if acess token is expired
     * @return boolean Expired or not
     *
     * @author Jean BORDAT <bordat.jean@gmail.com>
     * Date    2014-06-19
     * Updated by Jean Bordat <jean.bordat@steria.com> the 2014-07-08
     */
    public function hasExpired();

    /**
     * Getter for Access token
     * @return String Acecss token
     *
     * @author Jean BORDAT <bordat.jean@gmail.com>
     * Date    2014-06-19
     * Updated by Jean Bordat <jean.bordat@steria.com> the 2014-07-08
     */
    public function getAccessToken();

    /**
     * Getter for Refresh token
     * @return String Refresh token
     *
     * @author Jean BORDAT <bordat.jean@gmail.com>
     * Date    2014-06-19
     * Updated by Jean Bordat <jean.bordat@steria.com> the 2014-07-08
     */
    public function getRefreshToken();

    /**
     * Getter for expiration acces token date
     * @return Date Expiration date
     *
     * @author Jean BORDAT <bordat.jean@gmail.com>
     * Date    2014-06-19
     * Updated by Jean Bordat <jean.bordat@steria.com> the 2014-07-08
     */
    public function getDateOutcome();

    /**
     * Function deleting SESSION datas
     *
     * @author Jean BORDAT <bordat.jean@gmail.com>
     * Date    2014-06-19
     * Updated by Jean Bordat <jean.bordat@steria.com> the 2014-07-08
     */
    public function deleteSessionVars();

    /**
     * Function checking SESSION datas avalability
     * @return boolean Valid or not
     *
     * @author Jean BORDAT <bordat.jean@gmail.com>
     * Date    2014-06-19
     * Updated by Jean Bordat <jean.bordat@steria.com> the 2014-07-08
     */
    public function isValid();
}
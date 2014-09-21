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

interface OAuthUserEntityInterface {

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
    public function setUserEntity($id, $username, $email, $role, $guid);

    /**
     * Getter for user object
     * @return User User entity
     *
     * @author Jean BORDAT <bordat.jean@gmail.com>
     * Date    2014-06-19
     * Updated by Jean Bordat <bordat.jean@gmail.com> the 2014-07-08
     */
    public function getUserEntity();

    /**
     * Utility function which will check data availability
     * @return boolean Valid or not
     *
     * @author Jean BORDAT <bordat.jean@gmail.com>
     * Date    2014-06-19
     * Updated by Jean Bordat <bordat.jean@gmail.com> the 2014-07-08
     */
    public function isValid();

    /**
     * Function which will delete user SESSION datas
     *
     * @author Jean BORDAT <bordat.jean@gmail.com>
     * Date    2014-06-19
     * Updated by Jean Bordat <bordat.jean@gmail.com> the 2014-07-08
     */
    public function deleteSessionVars();
    
    /**
     * Function whch return SESSION data
     * @return String User name or error phrase
     *
     * @author Jean BORDAT <bordat.jean@gmail.com>
     * Date    2014-06-19
     * Updated by Jean Bordat <bordat.jean@gmail.com> the 2014-07-08
     */
    public function getUsername();

    /**
     * Function will sets SESSION data
     * @param String User guid or error phrase
     *
     * @author Jean BORDAT <bordat.jean@gmail.com>
     * @date    21/09/2014
     * @time    17:14
     */
    public function setGuid($guid);

    /**
     * Function which returns SESSION data
     * @return String    User guid
     *
     * @author Jean BORDAT <bordat.jean@gmail.com>
     * @date    21/09/2014
     * @time    17:14
     */
    public function getGuid();

}
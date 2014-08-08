<?php

namespace SpiritDev\Bundle\OAuth2ClientBundle\Util;

class UriFormaterUtil {

    /**
     * Utility function to construct Grant request
     * @param  String $usr User name
     * @param  String $psw User password
     * @return String      Uri formatted
     *
     * @author Jean BORDAT <bordat.jean@gmail.com>
     * Date    2014-06-19
     * Updated by Jean Bordat <jean.bordat@steria.com> the 2014-07-08
     */
    private function formatUserGrantUri($tokenUri, $grantTypePassword, 
    	$clientId, $clientSecret, $redirectUri, $usr, $psw) {

        return $tokenUri."?grant_type=".$grantTypePassword."&client_id=".
        	$clientId."&client_secret=".$clientSecret."&username=".$usr.
        	"&password=".$psw."&redirect_uri=".$redirectUri;
    }

    /**
     * Utility function to construct refresh token request
     * @param  String $refresh_token Refresh oken consummed by API
     * @return String                Uri formatted
     *
     * @author Jean BORDAT <bordat.jean@gmail.com>
     * Date    2014-06-19
     * Updated by Jean Bordat <jean.bordat@steria.com> the 2014-07-08
     */
    private function formatRefreshTokenUri($tokenUri, $clientId, $clientSecret,
    	$grantTypeRefresh, $refresh_token) {

        return $tokenUri."?client_id=".$clientId."&client_secret=".
        	$clientSecret."&grant_type=".$grantTypeRefresh.
        	"&refresh_token=".$refresh_token;
    }

    /**
     * Utility function to construct User retrieve request
     * @param  String $usn         User name
     * @param  String $accessToken Access token consummed by API
     * @return Strnig              Uri formatted
     *
     * @author Jean BORDAT <bordat.jean@gmail.com>
     * Date    2014-06-19
     * Updated by Jean Bordat <jean.bordat@steria.com> the 2014-07-08
     */
    private function formatGetUserUri($getUserUri, $usn, $accessToken) {

        return $getUserUri."?username=".$usn."&access_token=".$accessToken;
    }

}
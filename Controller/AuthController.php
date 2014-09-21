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

namespace SpiritDev\Bundle\OAuth2ClientBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use OAuth2;

class AuthController extends Controller {

    private $serviceUserEntity = 'spirit_dev_oauth2_client.auth_user_entity';
    private $serviceOAuthRequestor = 'spirit_dev_oauth2_client.oauthrequestor';
    private $serviceConfiguration = 'spirit_dev_oauth2_client.bundle_configuration';
    private $sessionErrorString = 'session_error';

    /**
     * @Route("/index", name="spirit_dev_oauth2_client_homepage")
     * 
     * Index action, will check & dislay index app page
     * @return View Twig index view
     *
     * @author Jean BORDAT <bordat.jean@gmail.com>
     * Date    2014-06-19
     * Updated by Jean Bordat <bordat.jean@gmail.com> the 2014-08-08
     */
    public function indexAction() {

//        $configuredTemplate = $this->container->getParameter('spirit_dev_o_auth2_client.token_uri.redirection_template');
        $conf = $this->container->get($this->serviceConfiguration);
        $test = $conf->getTemplateName();

        $ue = $this->container->get($this->serviceUserEntity);
        $oar = $this->container->get($this->serviceOAuthRequestor);

        if (!$ue->isValid() || !$oar->isValid()) {

            return $this->redirect($this->generateUrl('spirit_dev_oauth2_client_login'));
        }

        return $this->render($conf->getTemplateName(),
            array(
                'user' => $ue->getUserEntity(),
                'access_token' => $oar->getAccessToken(),
                'refresh_token_uri' => $this->container->getParameter('spirit_dev_o_auth2_client.refresh_token_uri')
            )
        );
    }
    
    /**
     * @Route("/login", name="spirit_dev_oauth2_client_login")
     * 
     * Login action, will display login page
     * @return View Twig login view
     *
     * @author Jean BORDAT <bordat.jean@gmail.com>
     * Date    2014-06-19
     * Updated by Jean Bordat <bordat.jean@gmail.com> the 2014-08-08
     */
    public function loginAction() {

        if($this->container->getParameter('spirit_dev_o_auth2_client.redirection_type') === "twig") {
            return $this->render('SpiritDevOAuth2ClientBundle:Security:login.html.twig');
        }
    }

    /**
     * @Route("/logout", name="spirit_dev_oauth2_client_logout")
     * 
     * Logout action, will delete SESSION var and display login view
     * @return View Twig login view
     *
     * @author Jean BORDAT <bordat.jean@gmail.com>
     * Date    2014-06-19
     * Updated by Jean Bordat <bordat.jean@gmail.com> the 2014-08-08
     */
    public function logoutAction() {
        $ue = $this->container->get($this->serviceUserEntity);
        $req = $ue->deleteSessionVars();
        return $this->redirect($this->generateUrl('spirit_dev_oauth2_client_login'));
    }

    /**
     * USE IT FOR A JAVASCRIPT USAGE (use with login.js)
     * @Route("/auth_test_ajax", name="spirit_dev_oauth2_client_auth_test_ajax")
     * 
     * Will check user&app grants
     * @param  Request $request Incoming datas
     * @return JSON           Json array of user grant
     *
     * @author Jean BORDAT <bordat.jean@gmail.com>
     * Date    2014-06-19
     * Updated by Jean Bordat <bordat.jean@gmail.com> the 2014-08-08
     */
    public function passwordGrantAjaxAction(Request $request) {

        $username = $request->get('username');
        $password = $request->get('password');

        $oar = $this->container->get($this->serviceOAuthRequestor);
        $ue = $this->container->get($this->serviceUserEntity);

        $req = $oar->getUserGrants($username, $password);

        if ($req == 200) {
            // REDIRECT RESPONSE
            return new JsonResponse(array(
                "response_header" => "authentification",
                "response_type" => "redirection",
                "response_text" => "User authentification accorded. Need to redirect",
                "response_data" => array(
                        "redirect_value" => $oar->getRedirectUri(),
                        "access_token" => $oar->getAccessToken(),
                        "user" => $ue->getUserEntity()
                    )
            ), 200);   
        }
        if ($req == 206) {

            return new JsonResponse(array(
                "response_header" => "authentification nok",
                "response_type" => "explanation",
                "response_text" => "User authentification failed."
            ), 206);
        }
        return new JsonResponse("$req error triggered by user malfunction!", $req);
    }
    
    /**
     * USE IT FOR A TWIG AUTOMATIC REDIRECTION
     * @Route("/auth_test", name="spirit_dev_oauth2_client_auth_test")
     * 
     * Will check user&app grants
     * @param  Request $request Incoming datas
     * @return View           Twig redirection depending of grant issue
     *
     * @author Jean BORDAT <bordat.jean@gmail.com>
     * Date    2014-06-19
     * Updated by Jean Bordat <bordat.jean@gmail.com> the 2014-08-08
     */
    public function passwordGrantAction(Request $request) {

        $username = $request->get('username');
        $password = $request->get('password');

        $oar = $this->container->get($this->serviceOAuthRequestor);
        $req = $oar->getUserGrants($username, $password);

        if ($req == 200) {
            // REDIRECT RESPONSE
            return $this->redirect($this->generateUrl('spirit_dev_oauth2_client_homepage'));
        }
        return $this->redirect($this->generateUrl('spirit_dev_oauth2_client_login'));
    }

    /**
     * @Route("/check_remote_token", name="spirit_dev_oauth2_client_check_remote_token")
     * 
     * Function will check access_token avalability
     * @return JSON Json response of acces_token renew issue
     *
     * @author Jean BORDAT <bordat.jean@gmail.com>
     * Date    2014-06-19
     * Updated by Jean Bordat <bordat.jean@gmail.com> the 2014-08-08
     */
    public function checkAccessTokenAction() {
        $oar = $this->container->get($this->serviceOAuthRequestor);
        $req = $oar->checkStatus();

        return new JsonResponse($req, 200);  
    }

    /**
     * @Route("/check_remote_user", name="spirit_dev_oauth2_client_check_remote_user")
     * 
     * Function will check user datas availability
     * @return JSON Json response container of user datas
     *
     * @author Jean BORDAT <bordat.jean@gmail.com>
     * Date    2014-06-19
     * Updated by Jean Bordat <bordat.jean@gmail.com> the 2014-08-08
     */
    public function checkUserAction() {
        $ue = $this->container->get($this->serviceUserEntity);
        $req = $ue->getUserEntity();

        return new JsonResponse($req, 200); 
    }
}

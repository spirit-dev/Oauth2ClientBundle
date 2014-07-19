<?php

namespace SpiritDev\Bundle\OAuth2ClientBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

// these import the "@Route" and "@Template" annotations
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use OAuth2;


class AuthController extends Controller
{
    /*
     * //////@Route("/authorize", name="spirit_dev_oauth2_client_auth")
     *
     * public function authAction(Request $request)
     * {
     *     $authorizeClient = $this->container->get('cb_client.authorize_client');
     *     if (!$request->query->get('code')) {
     *         return new RedirectResponse($authorizeClient->getAuthenticationUrl());
     *     }
     *     $authorizeClient->getAccessToken($request->query->get('code'));
     *     
     *     return new Response($authorizeClient->fetch('http://cubbyholeapi.com/api/v1/offer_scales/1'));
     * }
     */

    /**
     * @Route("/index", name="spirit_dev_oauth2_client_homepage")
     */
    public function indexAction() {
        
        $ue = $this->container->get('spirit_dev_oauth2_client.auth_user_entity');
        $oar = $this->container->get('spirit_dev_oauth2_client.oauthrequestor');
        // $ug = $this->container->get('cb_client.oauth_user_grants');
        // $ug->hasExpired();
        $userEnity = $ue->getUserEntity();

        if ($userEnity['user_id'] === 'session_error' || 
            $userEnity['user_username'] === 'session_error' || 
            $userEnity['user_email'] === 'session_error' || 
            $userEnity['user_role'] === 'session_error') {

            return $this->redirect($this->generateUrl('spirit_dev_oauth2_client_login'));

        }

        return $this->render('SpiritDevOAuth2ClientBundle:Default:index.html.twig', 
            array(
                'user' => $ue->getUserEntity(),
                'access_token' => $oar->getAccessToken(),
                'env' => $this->container->get('kernel')->getEnvironment()
            )
        );
    }
    
    /**
     * @Route("/login", name="spirit_dev_oauth2_client_login")
     */
    public function loginAction() {

        return $this->render('SpiritDevOAuth2ClientBundle:Security:login.html.twig');
    }

    /**
     * @Route("/logout", name="spirit_dev_oauth2_client_logout")
     */
    public function logoutAction() {

        return 0;
    }

    /**
     * @Route("/auth_test", name="spirit_dev_oauth2_client_auth_test")
     */
    public function passwordGrantAction(Request $request) {

        $username = $request->get('username');
        $password = $request->get('password');

        $oar = $this->container->get('spirit_dev_oauth2_client.oauthrequestor');
        $ue = $this->container->get('spirit_dev_oauth2_client.auth_user_entity');

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
        return new JsonResponse($req, 200);
    }

    /**
     * @Route("/check_remote_token", name="spirit_dev_oauth2_client_check_remote_token")
     */
    public function checkTokenAction() {

        $oar = $this->container->get('spirit_dev_oauth2_client.oauthrequestor');

        $req = $oar->checkStatus();

        return new JsonResponse($req, 200);  
    }

    /**
     * @Route("/check_remote_user", name="spirit_dev_oauth2_client_check_remote_user")
     */
    public function checkUserAction() {
        $ue = $this->container->get('spirit_dev_oauth2_client.auth_user_entity');
        // $req = $ue->setUserEntity("1", "Roger", "roger@paul.fr", "USER");
        $req = $ue->getUserEntity();
        
        // $oar = $this->container->get('spirit_dev_oauth2_client.oauthrequestor');
        // $req = $oar->getTokenDateOut();
        // $req = $oar->getRemoteUser("test");

        return new JsonResponse($req, 200); 
    }

    /**
     * @Route("/delete_remote_user", name="spirit_dev_oauth2_client_delete_remote_user")
     */
    public function deleteUserAction() {

        $ue = $this->container->get('spirit_dev_oauth2_client.auth_user_entity');
        $req = $ue->deleteSessionVars();
        return new JsonResponse($req, 200); 
    }
}

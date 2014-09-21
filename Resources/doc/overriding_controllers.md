Overriding Default SpiritDevOAuth2ClientBundle Controllers
============================================

The default controllers packaged with the SpiritDevOAuth2ClientBundle provide a lot of
functionality that is sufficient for general use cases. But, you might find
that you need to extend that functionality and add some logic that suits the
specific needs of your application.

**Note:**

> Overriding the controller requires to duplicate all the logic of the action.
> Most of the time, it is easier to use the events
> to implement the functionality. Replacing the whole controller should be
> considered as the last solution when nothing else is possible.

The first step to overriding a controller in the bundle is to create a child
bundle whose parent is SpiritDevOAuth2ClientBundle. The following code snippet creates a new
bundle named `AcmeUserBundle` that declares itself a child of SpiritDevOAuth2ClientBundle.

``` php
// src/Acme/OAuth2ClientBundle/AcmeOAuth2ClientBundle.php
<?php

namespace Acme\OAuth2ClientBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class AcmeOAuth2ClientBundle extends Bundle
{
    public function getParent()
    {
        return 'SpiritDevOAuth2ClientBundle';
    }
}
```

**Note:**

> The Symfony2 framework only allows a bundle to have one child. You cannot create
> another bundle that is also a child of SpiritDevOAuth2ClientBundle.


Now that you have created the new child bundle you can simply create a controller class
with the same name and in the same location as the one you want to override. This
example overrides the `AuthControlelr` by extending the SpiritDevOAuth2ClientBundle
`AuthControlelr` class and simply overriding the method that needs the extra
functionality.

The example below overrides the `indexAction` method. It uses the code from
the base controller and makes sucurity verifications before switching on defined views.

``` php
// src/Acme/OAuth2clientBundle/Controller/AuthControlelr.php
<?php

namespace Acme\OAuth2clientBundle\Controller;

use SpiritDev\Bundle\OAuth2ClientBundle\Controller\AuthController as BaseController;
use Symfony\Component\HttpFoundation\Response;

// these import the "@Route" and "@Template" annotations
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use OAuth2;


class AuthController extends BaseController {

    private $serviceUserEntity = 'spirit_dev_oauth2_client.auth_user_entity';
    private $serviceOAuthRequestor = 'spirit_dev_oauth2_client.oauthrequestor';
    private $sessionErrorString = 'session_error';

    /**
     * @Route("/index", name="spirit_dev_oauth2_client_homepage")
     */
    public function indexAction() {
        
        $ue = $this->container->get($this->serviceUserEntity);
        $oar = $this->container->get($this->serviceOAuthRequestor);
        $userEnity = $ue->getUserEntity();

        if ($userEnity['user_id'] === $sessionErrorString || 
            $userEnity['user_username'] === $sessionErrorString || 
            $userEnity['user_email'] === $sessionErrorString || 
            $userEnity['user_role'] === $sessionErrorString) {

            return $this->redirect($this->generateUrl('spirit_dev_oauth2_client_login'));

        }

        // The view returned is different from the original one
        return $this->render('CentralOAuth2clientBundle:Default:index.html.twig', 
            array(
                'user' => $ue->getUserEntity(),
                'access_token' => $oar->getAccessToken(),
                'env' => $this->container->get('kernel')->getEnvironment()
            )
        );
    }
```

**Note:**

> If you do not extend the SpiritDevOAuth2ClientBundle controller class that you want to override
> and instead extend ContainerAware or the Controller class provided by the FrameworkBundle
> then you must implement all of the methods of the SpiritDevOAuth2ClientBundle controller that
> you are overriding.

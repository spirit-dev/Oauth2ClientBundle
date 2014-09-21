## DOCUMENT BEING WRITTEN

Getting Started With SpiritDevO-Auth2ClientBundle
=========================================

## Introduction
OAuth2ClientBundle is an OAuth2 flow manager. Define your API grants, your rendering template and thats all.
It manages for you token flows from [OAuth2](http://oauth.net/2/) Protocol. It also provides a direct login to API side. 
Take a look to the following to get started with OAuth2ClientBundle ;)

## Installation

Installation is a quick 3 steps process:

1. Download SpiritDevOAuth2ClientBundle
2. Enable the Bundle
3. Configure the SpiritDevOAuth2ClientBundle


### Step 1: Install SpiritDevOAuth2ClientBundle

The preferred way to install this bundle is to rely on [Composer](http://getcomposer.org).
Just check on [Packagist](https://packagist.org/packages/spirit-dev/oauth2-client-bundle) the version you want to install (in the following example, we used "dev-master") and add it to your `composer.json`:

``` js
{
    "require": {
        // ...
        "spirit-dev/oauth2-client-bundle": "dev-master"
    }
}
```

### Step 2: Enable the bundle

Finally, enable the bundle in the kernel:

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
            new SpiritDev\Bundle\OAuth2ClientBundle\SpiritDevOAuth2ClientBundle(),
    );
}
```


### Step 3: Configure SpiritDevOAuth2ClientBundle

### Document beeing written. This part may change.

Import the routing.yml configuration file in app/config/routing.yml:

``` yaml
# app/config/routing.yml
spirit_dev_oauth2_client_o_auth:
    resource: "@SpiritDevOAuth2ClientBundle/Resources/config/routing.yml"
```

Add SpiritDevOAuth2ClientBundle settings in app/config/config.yml:

``` yaml
# app/config/config.yml
spirit_dev_o_auth2_client:
    api_oauth_settings:
        token_uri: "http://your.api.com/oauth/v2/token" // API url to get token
        get_user_uri: "http://your.api.com/api/v1/user" // API url to get user informations
        client_id: "ExampleOfClientIDProvidedByYourAPI.com" // ClientId Passphrase given by API
        client_secret: "ExampleOfClientSecretProvidedByYourAPI.com" // ClientSecret Passphrase given by API
        redirect_uri: "http://your.local.app/index" // Internal redirection url after login success
        refresh_token_uri: "http://dev.spiritapi.com/check_remote_token" // url to pass to view to reload access_token
    login_success_settings:
        redirection_type: "twig" // Switch between js and twig to generate redirection // next arriving feature 
        redirection_template: AcmeDemoBundle:Default:index.html.twig
```

## Simulating a token granting (comming soon)

## Next steps
Now that you have completed the basic installation and configuration of the FOSUserBundle, you are ready to learn about more advanced features and usages of the bundle.

The following documents are available:

* [Overriding Templates](https://github.com/spirit-dev/Oauth2ClientBundle/blob/master/Resources/doc/overriding_templates.md)
* [Overriding Controllers](https://github.com/spirit-dev/Oauth2ClientBundle/blob/master/Resources/doc/overriding_controllers.md)
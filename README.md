## DOCUMENT BEING WRITTEN

Getting Started With SpiritDevOAuth2ClientBundle
=========================================

## Prerequisites
This bundle is a client side OAuth managment. It is fully working with API OAuth2ManagerSide (comming soon)

## Installation

Installation is a quick 3 steps process:

1. Download SpiritDevOAuth2ClientBundle
2. Enable the Bundle
3. Configure the SpiritDevOAuth2ClientBundle (comming soon)


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

### Document beeing written. This part of the documentation will come soon

Import the routing.yml configuration file in app/config/routing.yml:

``` yaml
# app/config/routing.yml
spirit_dev_oauth2_client_o_auth:
    resource: "@SpiritDevOAuth2ClientBundle/Resources/config/routing.yml"
```

Add SpiritDevOAuth2ClientBundle settings in app/config/config.yml:

``` yaml
# app/config/config.yml
fos_oauth_server:
    db_driver: orm       # Driver availables: orm, mongodb, or propel
    client_class:        Acme\ApiBundle\Entity\Client
    access_token_class:  Acme\ApiBundle\Entity\AccessToken
    refresh_token_class: Acme\ApiBundle\Entity\RefreshToken
    auth_code_class:     Acme\ApiBundle\Entity\AuthCode
```

## Simulating a token granting (comming soon)
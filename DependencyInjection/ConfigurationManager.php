<?php
/**
 * Created by PhpStorm.
 * User: JuanitoB
 * Date: 21/09/2014
 * Time: 18:43
 */

namespace SpiritDev\Bundle\OAuth2ClientBundle\DependencyInjection;

class ConfigurationManager {

    private $templateName = null;

    public function __construct($template) {
        $this->templateName = $template;
    }

    /**
     * @return null
     */
    public function getTemplateName() {
        return $this->templateName;
    }

    /**
     * @param null $templateName
     */
    public function setTemplateName($templateName) {
        $this->templateName = $templateName;
    }
} 
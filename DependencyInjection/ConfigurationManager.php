<?php
/**
 * Created by PhpStorm.
 * User: JuanitoB
 * Date: 21/09/2014
 * Time: 18:43
 */

namespace SpiritDev\Bundle\OAuth2ClientBundle\DependencyInjection;

/**
 * Class ConfigurationManager
 * @package SpiritDev\Bundle\OAuth2ClientBundle\DependencyInjection
 *
 * @author Jean BORDAT <bordat.jean@gmail.com>
 * @date    21/09/2014
 * @time    19:11
 */
class ConfigurationManager {

    private $templateName = null;

    /**
     * @param $template Param defined in config.yml
     *
     *
     * @author Jean BORDAT <bordat.jean@gmail.com>
     * @date    21/09/2014
     * @time    19:10
     */
    public function __construct($template) {
        $this->templateName = $template;
    }

    /**
     * @return String template name
     */
    public function getTemplateName() {
        return $this->templateName;
    }

    /**
     * @param String $templateName
     */
    public function setTemplateName($templateName) {
        $this->templateName = $templateName;
    }
} 
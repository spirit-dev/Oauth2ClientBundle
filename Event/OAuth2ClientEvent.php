<?php

namespace SpiritDev\Bundle\OAuth2ClientBundle\Event;

use Symfony\Component\EventDispatcher\Event;

/**
 * Event class for SpiritDevOAuth2ClientBundle
 * @author Jean BORDAT <bordat.jean@gmail.com>
 * Date    2014-07-20
 */
class OAuth2ClientEvent extends Event {

	protected $data = array();
	protected $isDefaultPrevented = false;
	protected $isPropagationStopped = false;

	/**
	 * This function forces the bundle to preventDefaults is event triggered
	 * @author Jean BORDAT <bordat.jean@gmail.com>
	 * Date    2014-07-20
	 */
	public function preventDefault() {
		$this->isDefaultPrevented = true;
	}

	/**
	 * Function returning the status of preventDefault in OAuth2Client Event
	 * @return boolean
	 * @author Jean BORDAT <bordat.jean@gmail.com>
	 * Date    2014-07-20
	 */
	public function isDefaultPrevented() {
		return $this->isDefaultPrevented;
	}

	/**
	 * This function the bundle to stop propagation if event triggered
	 * @author Jean BORDAT <bordat.jean@gmail.com>
	 * Date    2014-07-20
	 */
	public function stopPropagation() {
		$this->isPropagationStopped = true;
	}

	/**
	 * Function returns the status of PropagationStop value for this bundle
	 * @return boolean
	 * @author Jean BORDAT <bordat.jean@gmail.com>
	 * Date    2014-07-20
	 */
	public function isPropagationStopped() {
		return $this->isPropagationStopped;
	}

	/**
	 * Function whitch sets the value of a defined var
	 * @param String $key Represents the name of var to set
	 * @param Mixed $val Represents the value to set with
	 * @author Jean BORDAT <bordat.jean@gmail.com>
	 * Date    2014-07-20
	 */
	public function set($key, $val) {
		$this->data[$key] = $val;
	}

	/**
	 * Function returns the defined value for the key parameter
	 * @param  String $key Represents the class variable to return
	 * @return Mixed      Value of the $key parameter
	 */
	public function get($key) {
		if (!array_key_exists($key, $this->data)) {
			return null;
		}
		return $this->data[$key];
	}

}
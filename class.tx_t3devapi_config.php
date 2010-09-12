<?php

/***************************************************************
 *
 * Copyright notice
 *
 * (c) 2010 Yohann CERDAN <ycerdan@onext.fr>
 * All rights reserved
 *
 * This script is part of the TYPO3 project. The TYPO3 project is
 * free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * The GNU General Public License can be found at
 * http://www.gnu.org/copyleft/gpl.html.
 *
 * This script is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

class tx_t3devapi_config {

	// Parent object
	protected $pObj = NULL;
	protected $conf = NULL;
	protected $cObj = NULL;

	/**
	 * Class constructor
	 */

	function __construct($pObj) {
		// Store parent object as a class variable
		$this->pObj = $pObj;
	}

	/**
	 * This function get all configurations from ts, flexform, getpost...
	 */

	function getArrayConfig($pObj,$debug=false) {
		$this->pObj  = $pObj;
		$this->conf  = $pObj->conf;
		$this->cObj  = $pObj->cObj;

		//TYPOSCRIPT = template with plugin.tx_xxxx_pi1.xxxx = xxxx
		$tsConfig = $this->conf;

		//FLEXFORM
		$this->pi_initPIflexForm(); // Init and get the flexform data of the plugin
		$flexConfig = array(); // Setup our storage array...
		$piFlexForm = array();
		$piFlexForm = $this->cObj->data['pi_flexform'];// Assign the flexform data to a local variable for easier access

		// Traverse the entire array based on the language and assign each configuration option to $flexConfig array...
		if(isset($piFlexForm['data'])) {
			foreach ( $piFlexForm['data'] as $sheet => $data ){
				foreach ( $data as $lang => $value ){
					foreach ( $value as $key => $val ){
						$flexConfig[$key] = $this->pi_getFFvalue($piFlexForm, $key, $sheet);
					}
				}
			}
		}

		//merge TYPOSCRIPT with FLEXFORM
		$arrayConfig = array_merge($tsConfig,$flexConfig);

		//add $piVars
		$arrayConfig = array_merge($arrayConfig,$this->piVars);

		//test contentId to know if this content is concerned by piVars
		($arrayConfig['contentId'] == $this->cObj->data['uid']) ? $arrayConfig['piVars']= 1 : $arrayConfig['piVars']= 0 ;

		// add "ext_conf_template.txt"
		if($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf'][$this->pObj->extKey]) {
			$ext_conf = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf'][$this->pObj->extKey]);
			$arrayConfig = array_merge($arrayConfig,$ext_conf);
		}

		// add the pi config "plugin.tx_xxxx_pi1 = xxxx" which is not imported in the $conf
		if(is_array($GLOBALS['TSFE']->tmpl->setup['plugin.'][$pObj->prefixId.'.'])) {
			$arrayConfig = array_merge($arrayConfig,$GLOBALS['TSFE']->tmpl->setup['plugin.'][$pObj->prefixId.'.']);
		}

		if($debug==true) {
			debug($arrayConfig);
		}

		return $arrayConfig;
	}

}

if (defined('TYPO3_MODE') && $GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/t3devapi/class.tx_t3devapi_config.php'])    {
	include_once($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/t3devapi/class.tx_t3devapi_config.php']);
}

?>
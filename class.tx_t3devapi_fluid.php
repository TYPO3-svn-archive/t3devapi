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

class tx_t3devapi_fluid {
	protected $template = NULL;
	
	/**
	 * Class constructor
	 */

	function __construct($template) {
		$this->template = $template;
	}

	/**
	 * Loads a template file and generate the content
	 */

	function fluidView($context = array()) {
		$renderer = t3lib_div::makeInstance('Tx_Fluid_View_TemplateView');
		$controllerContext = t3lib_div::makeInstance('Tx_Extbase_MVC_Controller_ControllerContext');
		$controllerContext->setRequest(t3lib_div::makeInstance('Tx_Extbase_MVC_Web_Request'));
		$renderer->setControllerContext($controllerContext); 
		$renderer->setPartialRootPath(t3lib_extMgm::extPath($this->extKey) . "res/partials/");
		$renderer->setTemplateRootPath(t3lib_extMgm::extPath($this->extKey) . "res/templates/");
		$renderer->setLayoutRootPath(t3lib_extMgm::extPath($this->extKey) . "res/layouts");
		$renderer->setTemplatePathAndFilename($this->template);
	 
		foreach ($context as $key => $value) {
			$renderer->assign($key,$value);
		}
	 
		return $renderer->render();
	}

	
	
}

if (defined('TYPO3_MODE') && $GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/t3devapi/class.tx_t3devapi_fluid.php'])    {
	include_once($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/t3devapi/class.tx_t3devapi_fluid.php']);
}

?>
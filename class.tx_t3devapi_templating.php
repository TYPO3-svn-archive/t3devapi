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

class tx_t3devapi_templating {
	
	// Template object for frontend functions
	protected $templateContent = NULL;

	// Parent object
	protected $pObj = NULL;

	/**
	 * Class constructor
	 */

	function __construct($pObj) {
		// Store parent object as a class variable
		$this->pObj = $pObj;
	}

	/**
	 * Loads a template file
	 */

	function initTemplate($templateFile,$debug=false) {
	
		$this->templateContent = $this->pObj->cObj->fileResource($templateFile);
		
		if ($debug==true) {
			if ($this->templateContent===NULL) {
				debug('Check the path template or the rights','Error');
			}
			debug($this->templateContent,'Content of '.$templateFile);
		}
		
		return true;
	}

	/**
	 * Template rendering for subdatas and principal datas
	 */

	function renderAllTemplate($templateMarkers,$templateSection) {
	
		// Check if the template is loaded
		if(!$this->templateContent) { 
			return false; 
		}

		// Check argument
		if(!is_array($templateMarkers)) { 
			return false; 
		}

		// Templating

		$content = '';
		
		if(is_array($templateMarkers[0])) { // Subdatas
			
			foreach($templateMarkers as $key=>$val){
				$subParts = $this->pObj->cObj->getSubpart($this->templateContent,$templateSection);
				$content .= $this->pObj->cObj->substituteMarkerArray($subParts,$val);
			}
			
			return $content;
			
		} else { // Principal datas
			
			$subParts = $this->pObj->cObj->getSubpart($this->templateContent,$templateSection);
			
			foreach ($templateMarkers as $subPart => $subContent) {
				if(preg_match_all('/(<!--).*?'.$subPart.'.*?(-->)/',$subParts,$matches)>=2) { // subpart
					$subParts_temp = $this->pObj->cObj->getSubpart($subParts,$subPart);
					$subParts = $this->pObj->cObj->substituteSubpart($subParts,$subPart,$subContent);
				}
			}
			
			$content = $this->pObj->cObj->substituteMarkerArray($subParts,$templateMarkers);
			
			return $content;
			
		}
	}
	
}

if (defined('TYPO3_MODE') && $GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/t3devapi/class.tx_t3devapi_templating.php'])    {
	include_once($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/t3devapi/class.tx_t3devapi_templating.php']);
}

?>
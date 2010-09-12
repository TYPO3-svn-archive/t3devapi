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

class tx_t3devapi_html {

	/**
	 *Genering the HTML code of label
	 */
	
	public function renderLabel($id, $value, $options = NULL){
		return "\t".'<label for="'.$id.'" '.$options.'>'.$value.'</label>'."\n";
	}

	/**
	 *Genering the HTML code oh <input>
	*/
	
	public function renderInput($type , $name , $value ='',$id ='',  $options = NULL){
		if (empty($name)) {
			return 'Le Type TEXTE necessite un parametre : name';
		}
		if (empty($type)) {
			return 'Le Type GENERIC necessite un parametre : type';
		}
		if (!empty($id)) {
			 $idRender = 'id="'.$id.'"';	
		}
		return "\t\t".'<input type="'.$type.'" name="'.$name.'" '.$idRender.' value="'.$value.'" '.$options.' />'."\n";
		
	}
	
	/**
	 *Genering the HTML code oh <textarea>
	 */
	
	public function renderTextarea ($name , $value ='', $options = NULL){
		if (empty($name)) {
			return 'Le Type TEXTAREA necessite un parametre : name';
		}
		return "\t\t".'<textarea name="'.$name.'" id="'.$name.'" '.$options.'>'.$value.'</textarea>'."\n";
	}
	
	/**
	 *Genering the HTML code oh <select>
	 */
	
	public function renderSelect($name , $content =array(), $value ='', $options = NULL){
		if (empty($name)) {
			return 'Le Type SELECT necessite un parametre : name';
		} 
	
		if ( count($content) < 1 ) {
			return 'Le Type SELECT necessite une liste de valeurs'; 	
		}
		
		$return = "\t\t".'<select name="'.$name.'" id="'.$name.'" '.$options.'>' ."\n";
		$fill_selected = false;

		foreach ($content as $key => $entry ){
			$select ='';
			// aucune valeur de selectionné indiqué
			if ( empty ( $value ) && ($fill_selected==false)) {
				$select = 'selected="selected"';
				$fill_selected = true;
			} 
			// une valeur est selectionné
			if (( $value == $key ) && ($fill_selected==false) ) {
				$select = 'selected="selected"';
				$fill_selected = true;
			} 
			$return .=  "\t\t\t".'<option value="'.$key.'" '.$select.'>'.$entry.'</option>'."\n";
		}

		$return .= "\t\t".'</select>';

		return $return;
		
	}
	
	public function renderMultipleSelect($name , $content = array(), $value = array(), $options = NULL){
		if (empty($name)) {
			return 'Le Type SELECT necessite un parametre : name';
		} 
	
		if ( count($content) < 1 ) {
			return 'Le Type SELECT necessite une liste de valeurs'; 	
		}
		
		$return = "\t\t".'<select name="'.$name.'[]" id="'.$name.'" '.$options.' multiple="true">' ."\n";

		foreach ($content as $key => $entry ){
			$select ='';

			// une valeur est selectionné
			if (in_array($key,$value)) {
				$select = 'selected="selected"';
				$fill_selected = true;
			} 
			$return .=  "\t\t\t".'<option value="'.$key.'" '.$select.'>'.$entry.'</option>'."\n";
		}

		$return .= "\t\t".'</select>';

		return $return;
		
	}
	
	/**
	 *Genering the HTML code oh <chechbox>
	 */
	
	public function renderCheckBox($name, $value='', $id ='',  $valuechecked='', $options = NULL ){
		if ( empty ( $name ) ) {
			return 'Le Type TEXTE necessite un parametre : name';
		}
		
		if ( !empty($id) ) {
			 $idRender = 'id="'.$id.'"';	
		}
		
		if ($valuechecked==$value) {
			$checked = 'checked="checked"';
		} else {
			$checked = '';
		}
		
		return "\t\t".'<input type="checkbox" name="'.$name.'" '.$idRender.' value="'.$value.'" '.$options.' '.$checked.'/>'."\n";
	}

	/**
	 *Genering the HTML code oh <radio>
	 */
	
	public function renderRadio($name , $content=array(), $value ='', $array_options = NULL) {
		if (empty($name)) { 
			return 'Le Type RADIO necessite un parametre : name';
		}

		if (count($content)<1) {
			return 'Le Type RADIO necessite une liste de valeurs'."\n"; 	
		}
		
		$return = "";
		$i=1;
		
		foreach ($content as $key => $entry ){
			if ( $value == $key ) {
				$checked = 'checked="checked"';
			} else {
				$checked = '';
			}
			
			$return .= '<input type="radio" name="'.$name.'" value="'.$key.'" id="'.$name.$i.'" '.$array_options[$key].' '.$checked.'/>'."\n";
			$return .= $this->renderLabel($name.$i,ltrim($entry),'id="label'.$name.$i.'"');
			$i++;
		}
		
		return $return;
		
	}
	
	/**
	 *Genering the HTML code oh <submit>
	 */
	
	public function renderSubmit($name , $value = 'Envoyer', $id='',  $options = NULL){
		return $this->renderInput('submit' , $name , $value ,$id,  $options);
	}


}

if (defined('TYPO3_MODE') && $GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/t3devapi/class.tx_t3devapi_html.php'])    {
	include_once($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/t3devapi/class.tx_t3devapi_html.php']);
}

?>
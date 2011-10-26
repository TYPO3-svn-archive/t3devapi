<?php

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2011 Yohann CERDAN <cerdanyohann@yahoo.fr>
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *  A copy is found in the textfile GPL.txt and important notices to the license
 *  from the author is found in LICENSE.txt distributed with these scripts.
 *
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * tx_t3devapi_html
 * Class to create some html element with some php objects
 * Example :
 *
 * $myImage = new tx_t3devapi_html('img', array('src' => 'http://www.google.fr/intl/en_fr/images/logo.gif'));
 * $myAnchor = new tx_t3devapi_html('a', array('href' => 'http://google.fr', 'title' => 'Google'), array($myImage));
 * $myDiv = new tx_t3devapi_html('div', '', array($myAnchor));
 * echo $myDiv->output();
 *
 * @author Yohann CERDAN <cerdanyohann@yahoo.fr>
 * @package TYPO3
 * @subpackage t3devapi
 */
class tx_t3devapi_html
{
	/**
	 *
	 * @var tag type
	 * @access protected
	 */
	protected $type;
	/**
	 *
	 * @var tag attributes
	 * @access protected
	 */
	protected $attributes;
	/**
	 *
	 * @var tag text
	 * @access protected
	 */
	protected $text = FALSE;
	/**
	 *
	 * @var tag closers
	 * @access protected
	 */
	protected $selfClosers = array('input', 'img', 'hr', 'br', 'meta', 'link');

	/**
	 * This is the class constructor.
	 * It allows to set up the tag type, attributes, childs and text
	 *
	 * @param string $type
	 * @param string $attribute
	 * @param string $objects
	 * @param string $text
	 */
	public function __construct($type, $attribute = '', $objects = '', $text = '') {
		// Set the type
		$this->type = strtolower($type);
		$this->attributes = array();
		// Set attributes
		if (is_array($attribute)) {
			$this->setAttributes($attribute);
		}
		// Inject HTML into parent
		if (is_array($objects)) {
			$this->inject($objects);
		}
		// Set tag text
		if ($text) {
			$this->setText($text);
		}
	}

	/**
	 * Returns the value of an attribute
	 *
	 * @param string $attribute
	 * @return array
	 */

	public function getAttributes($attribute) {
		return $this->attributes[$attribute];
	}

	/**
	 * Set the value of an attribute
	 *
	 * @param array $attributeArr
	 * @return void
	 */

	public function setAttributes($attributeArr) {
		$this->attributes = array_merge($this->attributes, $attributeArr);
	}

	/**
	 * Set the text between opening and closing tag
	 * Tag must be text only
	 *
	 * @param string $text
	 * @return void
	 */

	public function setText($text) {
		if (is_string($text)) {
			$this->text = $text;
		}
	}

	/**
	 * Remove an attribute
	 *
	 * @param string $attribute
	 * @return void
	 */

	public function remove($attribute) {
		if (isset($this->attributes[$attribute])) {
			unset($this->attributes[$attribute]);
		}
	}

	/**
	 * Clear all attributes
	 *
	 * @return void
	 */

	public function clear() {
		$this->attributes = array();
	}

	/**
	 * Insert an array of child nodes into parent.
	 * Format code with an indent of 2 whitespace for childs nodes
	 *
	 * @param array $objectArr
	 * @return void
	 */

	public function inject($objectArr) {
		foreach ($objectArr as $object) {
			if (get_class($object) == get_class($this)) {
				$this->attributes['text'] .= $object->build();
			}
		}
	}

	/**
	 * Print the html
	 *
	 * @return string
	 */

	public function output() {
		return $this->build();
	}

	/**
	 * Build the HTML node
	 *
	 * @return string
	 */

	protected function build() {
		// start
		$build = '<' . $this->type;
		// add attributes
		if (count($this->attributes)) {
			foreach ($this->attributes as $key => $value) {
				if ($key != 'text') {
					$build .= ' ' . $key . '="' . $value . '"';
				}
			}
		}
		// closing
		if (!in_array($this->type, $this->selfClosers)) {
			// Parent node cannot have text
			if (is_string($this->text)) {
				$build .= '>' . $this->text . '</' . $this->type . '>';
			} else {
				$build .= '>' . $this->attributes['text'] . '</' . $this->type . '>';
			}
		} else {
			$build .= ' />';
		}
		// return it
		return $build;
	}

	/**
	 * Render a label
	 *
	 * @param string $for
	 * @param string $content
	 * @return string
	 */

	public function renderLabel($for, $content) {
		$element = new tx_t3devapi_html('label', array('for' => $for), NULL, $content);
		return $element->output();
	}

	/**
	 * Render a generic input (used by all the input type)
	 *
	 * @param string $type
	 * @param string $name
	 * @param string $value
	 * @param array $attributes
	 * @return string
	 */

	public function renderInput($type, $name, $value = '', $attributes = array()) {
		if (!isset($attributes['name'])) {
			$attributes['name'] = $name;
		}
		if (!isset($attributes['id'])) {
			$attributes['id'] = self::cleanId($name);
		}
		$attributes['type'] = $type;
		$attributes['value'] = $value;
		$element = new tx_t3devapi_html('input', $attributes);
		return $element->output();
	}

	/**
	 * Render a input type text
	 *
	 * @param string $name
	 * @param string $value
	 * @param array $attributes
	 * @return string
	 */

	public function renderText($name, $value = '', $attributes = array()) {
		return self::renderInput('text', $name, $value, $attributes);
	}

	/**
	 * Render a input type hidden
	 *
	 * @param string $name
	 * @param string $value
	 * @param array $attributes
	 * @return string
	 */

	public function renderHidden($name, $value = '', $attributes = array()) {
		return self::renderInput('hidden', $name, $value, $attributes);
	}

	/**
	 * Render a input type button
	 *
	 * @param string $name
	 * @param string $value
	 * @param array $attributes
	 * @return string
	 */

	public function renderButton($name, $value = '', $attributes = array()) {
		return self::renderInput('button', $name, $value, $attributes);
	}

	/**
	 * Render a input type password
	 *
	 * @param string $name
	 * @param string $value
	 * @param array $attributes
	 * @return string
	 */

	public function renderPassword($name, $value = '', $attributes = array()) {
		return self::renderInput('password', $name, $value, $attributes);
	}

	/**
	 * Render a input type reset
	 *
	 * @param string $name
	 * @param string $value
	 * @param array $attributes
	 * @return string
	 */

	public function renderReset($name, $value = '', $attributes = array()) {
		return self::renderInput('reset', $name, $value, $attributes);
	}

	/**
	 * Render a input type submit
	 *
	 * @param string $name
	 * @param string $value
	 * @param array $attributes
	 * @return string
	 */

	public function renderSubmit($name, $value = '', $attributes = array()) {
		return self::renderInput('submit', $name, $value, $attributes);
	}

	/**
	 * Render a textarea
	 *
	 * @param string $name
	 * @param string $value
	 * @param array $attributes
	 * @return string
	 */

	public function renderTextArea($name, $value = '', $attributes = array()) {
		if (!isset($attributes['name'])) {
			$attributes['name'] = $name;
		}
		if (!isset($attributes['id'])) {
			$attributes['id'] = self::cleanId($name);
		}
		$element = new tx_t3devapi_html('textarea', $attributes, NULL, $value);
		return $element->output();
	}

	/**
	 * Render a checbox
	 *
	 * @param string $name
	 * @param string $content
	 * @param array $arrayOfValues
	 * @param array $attributes
	 * @return string
	 */

	public function renderCheckbox($name, $content, $arrayOfValues = array(), $attributes = array()) {
		if (!isset($attributes['name'])) {
			$attributes['name'] = $name;
		}
		if (!isset($attributes['id'])) {
			$attributes['id'] = self::cleanId($name);
		}
		$attributes['type'] = 'checkbox';
		$attributes['value'] = $content;
		if (in_array($content, $arrayOfValues)) {
			$attributes['checked'] = 'checked';
		}
		$myInput = new tx_t3devapi_html('input', $attributes);
		return $myInput->output();
	}

	/**
	 * Render a radio button
	 *
	 * @param string $name
	 * @param string $content
	 * @param array $arrayOfValues
	 * @param array $attributes
	 * @return string
	 */

	public function renderRadio($name, $content, $arrayOfValues = array(), $attributes = array()) {
		if (!isset($attributes['name'])) {
			$attributes['name'] = $name;
		}
		if (!isset($attributes['id'])) {
			$attributes['id'] = self::cleanId($name);
		}
		$attributes['type'] = 'radio';
		$attributes['value'] = $content;
		if (in_array($content, $arrayOfValues)) {
			$attributes['checked'] = 'checked';
		}
		$myInput = new tx_t3devapi_html('input', $attributes);
		return $myInput->output();
	}

	/**
	 * Render a select
	 *
	 * @param string $name
	 * @param array $content
	 * @param string $value
	 * @param array $attributes
	 * @return string
	 */

	public function renderSelect($name, $content = array(), $value = '', $attributes = array()) {
		$myOptions = array();
		$fillSelected = FALSE;
		foreach ($content as $key => $entry) {
			$optionAttributes = array();
			$optionAttributes['value'] = $key;
			if ($value == '' && ($fillSelected == FALSE)) {
				$optionAttributes['selected'] = 'selected';
				$fillSelected = TRUE;
			}
			if (($value == $key) && ($fillSelected == FALSE)) {
				$optionAttributes['selected'] = 'selected';
				$fillSelected = TRUE;
			}
			$myOptions[] = new tx_t3devapi_html('option', $optionAttributes, '', $entry);
		}
		if (!isset($attributes['name'])) {
			$attributes['name'] = $name;
		}
		if (!isset($attributes['id'])) {
			$attributes['id'] = self::cleanId($name);
		}
		$mySelect = new tx_t3devapi_html('select', $attributes, $myOptions);
		return $mySelect->output();
	}

	/**
	 * Render a multiple select
	 *
	 * @param string $name
	 * @param array $content
	 * @param array $arrayOfValues
	 * @param array $attributes
	 * @return string
	 */

	public function renderMultipleSelect($name, $content = array(), $arrayOfValues = array(), $attributes = array()) {
		$myOptions = array();
		foreach ($content as $key => $entry) {
			$optionAttributes = array();
			$optionAttributes['value'] = $key;
			if (is_array($arrayOfValues)) {
				if (in_array($key, $arrayOfValues)) {
					$optionAttributes['selected'] = 'selected';
				}
			}
			$myOptions[] = new tx_t3devapi_html('option', $optionAttributes, '', $entry);
		}
		if (!isset($attributes['name'])) {
			$attributes['name'] = $name;
		}
		if (!isset($attributes['id'])) {
			$attributes['id'] = self::cleanId($name);
		}
		$attributes['multiple'] = 'true';
		$mySelect = new tx_t3devapi_html('select', $attributes, $myOptions);
		return $mySelect->output();
	}

	/**
	 * Clean an ID string (ex: without [])
	 *
	 * @param string $text
	 * @return mixed
	 */

	public function cleanId($text) {
		return preg_replace('/(\[|\])*/', '', $text);
	}


}

tx_t3devapi_miscellaneous::XCLASS('ext/t3devapi/class.tx_t3devapi_htmlelement.php');

?>
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

require_once(t3lib_extMgm::extPath('t3devapi') . 'class.tx_t3devapi_miscellaneous.php');

class tx_t3devapi_database {

	// Parent object
	protected $pObj = NULL;
	protected $debug = false;
	protected $enableMarker = true;
	protected $markerPrefix = '';
	protected $tcaFormat = 0;
	
	protected $datasPrevNext = array();
	protected $markerPrevNext = array();

	protected $prevNextNbRecordsPerPage = 10; // Number of records per page
	protected $prevNextNbItems = 3; // Number of items to display, ex with 3 items : 1,2,3........11,12,13
	protected $prevNextNbItemsCurrent = 2; // Number of items to display between the current item, ex with 2 items : .......6 7 8 .......
	protected $prevNextEmptyItem = "..."; // Empty item

	/**
	 * Class constructor
	 */

	function __construct($pObj) {
		// Store parent object as a class variable
		$this->pObj = $pObj;
		$this->misc = new tx_t3devapi_miscellaneous($pObj);
	}

	function getDatasPrevNext () {
		return $this->datasPrevNext;
	}
	
	function getPrevNext () {
		return $this->markerPrevNext;
	}
	
	function setMarker ($enableMarker) {
		$this->enableMarker = $enableMarker;
	}
	
	function setMarkerPrefix ($markerPrefix) {
		$this->enableMarkerPrefix = $markerPrefix;
	}
	
	function setTCAFormat ($tcaFormat) {
		$this->tcaFormat = $tcaFormat;
	}
	
	function setprevNextNbRecordsPerPage ($prevNextNbRecordsPerPage) {
		$this->prevNextNbRecordsPerPage = $prevNextNbRecordsPerPage;
	}
	
	function setPrevNextNbItems ($prevNextNbItems) {
		$this->prevNextNbItems = $prevNextNbItems;
	}
	
	function setPrevNextEmptyItem ($prevNextEmptyItem) {
		$this->prevNextEmptyItem = $prevNextEmptyItem;
	}
	
	function setPrevNextNbItemsCurrent ($prevNextNbItemsCurrent) {
		$this->prevNextNbItemsCurrent = $prevNextNbItemsCurrent;
	}

	/**
	 * This function return an array result of a request
	 */

	function getDataArray ($select="*",$table="",$where="",$groupBy="",$orderBy="",$limit="") {
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery($select,$table,$where,$groupBy,$orderBy,$limit);

		if($this->debug==true) {
			debug(preg_replace("/\\\n\s*/"," ",$GLOBALS['TYPO3_DB']->SELECTquery($select,$table,$where,$groupBy,$orderBy,$limit)));
		}

		if($res) {

				$datas = array();
				while($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res) ){

					// Format the datas as in the tca description
				if($this->tcaFormat==1) {
						foreach($row as $key=>$val){
							// debug($row);
							$format_val = $this->getFieldTCA($table,$key,$val);
						if($format_val!='') {
								$row[$key] = $format_val;
							}
						}
					}

					// Add ### or not
				$temp = ($this->enableMarker==true) ? $this->misc->convertToMarkerArray($row,$this->enableMarkerPrefix) : $row ;
					array_push($datas,$temp);
				}

			$GLOBALS['TYPO3_DB']->sql_free_result($res);
			return $datas;
			
		} else {
			if($this->debug==true) {
				debug(preg_replace("/\\\n\s*/"," ",$GLOBALS['TYPO3_DB']->SELECTquery($select,$table,$where,$groupBy,$orderBy,$limit))." ---> ".$GLOBALS['TYPO3_DB']->sql_error());
			}
		}
	}

	/**
	 * This function return an array result of a request with a prevnext navigator
		Example of template : 
		<ul class="prevNext">###FIRSTPAGE### ###PREV### ###PREVNEXT### ###NEXT### ###LASTPAGE###</ul>

		plugin.tx_oxcsannuaire_pi1 {
			wrapCurrent = <li class="active">|</li>
			wrapNormal = <li>|</li>
			wrapFirstActive = <li class="firstAct">|</li>
			wrapFirstInactive = <li class="firstOff">|</li>
			wrapLastActive = <li class="lastAct">|</li>
			wrapLastInactive = <li class="lastOff">|</li>
			wrapPrevActive = <li class="prevAct">|</li>
			wrapPrevInactive = <li class="prevOff">|</li>
			wrapNextActive = <li class="nextAct">|</li>
			wrapNextInactive = <li class="nextOff">|</li>
			label_first = Premier
			label_last = Dernier
			label_prev = Précédent
			label_next = Suivant
		}

	 */

	function generatePrevNext ($select="*",$table="",$where="",$groupBy="",$orderBy="",$limit="") {
		// PrevNext Wrap html
		$arrayConfig = tx_t3devapi_config::getArrayConfig($this->pObj);

		$html_current = isset($arrayConfig['wrapCurrent']) ? explode("|",$arrayConfig['wrapCurrent']) : explode("|",'<li class="active">|</li>');
		$html_normal = isset($arrayConfig['wrapNormal']) ? explode("|",$arrayConfig['wrapNormal']) : explode("|",'<li>|</li>');
		$html_first_active = isset($arrayConfig['wrapFirstActive']) ? explode("|",$arrayConfig['wrapFirstActive']) : explode("|",'<li class="firstAct">|</li>');
		$html_first_inactive = isset($arrayConfig['wrapFirstInactive']) ? explode("|",$arrayConfig['wrapFirstInactive']) : explode("|",'<li class="firstOff">|</li>');
		$html_last_active = isset($arrayConfig['wrapLastActive']) ? explode("|",$arrayConfig['wrapLastActive']) : explode("|",'<li class="lastAct">|</li>');
		$html_last_inactive = isset($arrayConfig['wrapLastInactive']) ? explode("|",$arrayConfig['wrapLastInactive']) : explode("|",'<li class="lastOff">|</li>');
		$html_prev_active = isset($arrayConfig['wrapPrevActive']) ? explode("|",$arrayConfig['wrapPrevActive']) : explode("|",'<li class="prevAct">|</li>');
		$html_prev_inactive = isset($arrayConfig['wrapPrevInactive']) ? explode("|",$arrayConfig['wrapPrevInactive']) : explode("|",'<li class="prevOff">|</li>');
		$html_next_active = isset($arrayConfig['wrapNextActive']) ? explode("|",$arrayConfig['wrapNextActive']) : explode("|",'<li class="nextAct">|</li>');
		$html_next_inactive = isset($arrayConfig['wrapNextInactive']) ? explode("|",$arrayConfig['wrapNextInactive']) : explode("|",'<li class="nextOff">|</li>');
		$label_first = isset($arrayConfig['label_first']) ? $arrayConfig['label_first'] : 'First';
		$label_last  = isset($arrayConfig['label_last']) ? $arrayConfig['label_last'] : 'Last';
		$label_prev  = isset($arrayConfig['label_prev']) ? $arrayConfig['label_prev'] : 'Prev';
		$label_next  = isset($arrayConfig['label_next']) ? $arrayConfig['label_next'] : 'Next';

		// Records informations

		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery($select,$table,$where,$groupBy,$orderBy,$limit);

		if($res) {
			$nbrows = $GLOBALS['TYPO3_DB']->sql_num_rows($res);
		}

		$numPages = ceil($nbrows / $this->prevNextNbRecordsPerPage)-1;

		$GP_offset = t3lib_div::GPvar('offset');
		$offset = (isset($GP_offset)) ? $GP_offset : 0 ;

		$current_page = ($offset/$this->prevNextNbRecordsPerPage)+1;
		$limit = $offset.",".$this->prevNextNbRecordsPerPage;

		// Make and set the request
		$this->datasPrevNext = $this->getDataArray($select,$table,$where,$groupBy,$orderBy,$limit);
		$nb_records = count($this->datas);

		// PrevNext Navigator
		$i_prevNextEmptyItem = 0;

		$offsetParam = array();

		for($i=0;$i<=$numPages;$i++) {
			$offsetParam['offset'] = $i*$this->prevNextNbRecordsPerPage;
			if(($i<$this->prevNextNbItems)||($i>=$numPages+1-$this->prevNextNbItems)||($current_page+$this->prevNextNbItemsCurrent>$i+1&&$current_page-$this->prevNextNbItemsCurrent<$i+1)) {
				if($i+1==$current_page) {
					$this->markerPrevNext['###PREVNEXT###'] .= $html_current[0].($i+1).$html_current[1];
					$i_prevNextEmptyItem = 0;
				} else {
					$this->markerPrevNext['###PREVNEXT###'] .= $html_normal[0].'<a href="'.$this->misc->getURL($offsetParam,1).'" >'.($i+1).'</a>'.$html_normal[1];
				}
			} else {
				if($i_prevNextEmptyItem==0) {
					$this->markerPrevNext['###PREVNEXT###'] .= $html_normal[0].$this->prevNextEmptyItem.$html_normal[1];
					$i_prevNextEmptyItem=1;
				}
			}
			if($i==0) {
				$this->markerPrevNext['###FIRSTPAGE###'] = $html_first_active[0].'<a href="'.$this->misc->getURL($offsetParam,1).'" >'.$label_first.'</a>'.$html_first_active[1];
			}
			if($i==$numPages) {
				$this->markerPrevNext['###LASTPAGE###'] = $html_last_active[0].'<a href="'.$this->misc->getURL($offsetParam,1).'" >'.$label_last.'</a>'.$html_last_active[1];
			}
		}

		// Prev link
		if($offset-$this->prevNextNbRecordsPerPage>=0) {
			$offsetParam['offset'] = ($offset-$this->prevNextNbRecordsPerPage);
			$this->markerPrevNext['###PREV###'] = $html_prev_active[0].'<a href="'.$this->misc->getURL($offsetParam,1).'">'.$label_prev.'</a>'.$html_prev_active[1];
		} else {
			$this->markerPrevNext['###PREV###'] =  $html_prev_inactive[0].$label_prev.$html_prev_inactive[1];
			$this->markerPrevNext['###FIRSTPAGE###'] = $html_first_inactive[0].$label_first.$html_first_inactive[1];
		}

		// Next link
		if($offset+$this->prevNextNbRecordsPerPage<$nbrows) {
			$offsetParam['offset'] = ($offset+$this->prevNextNbRecordsPerPage);
			$this->markerPrevNext['###NEXT###'] = $html_next_active[0].'<a href="'.$this->misc->getURL($offsetParam,1).'">'.$label_next.'</a>'.$html_next_active[1];
		} else {
			$this->markerPrevNext['###NEXT###'] = $html_next_inactive[0].$label_next.$html_next_inactive[1];
			$this->markerPrevNext['###LASTPAGE###'] = $html_last_inactive[0].$label_last.$html_last_inactive[1];
		}

		// Extras infos
		$this->markerPrevNext['###NUMPAGES###'] = $numPages+1;
		$this->markerPrevNext['###BEGIN###'] = $offset+1;
		$this->markerPrevNext['###END###'] = $offset+$nb_records;
		$this->markerPrevNext['###NBROWS###'] = $nbrows;

		return $datas;
	}

	/**
	* Get the type corresponding to the TCA Array - not finish
	**/
	function getFieldTCA($table,$field,$val) {
		$table_tca = $this->misc->getTableTCA($table);

		//parse RTE fields
		if($table_tca['columns'][$field]['config']['wizards']['RTE']){
		}
		//format date fields
		if($table_tca['columns'][$field]['config']['eval']=='date' || $table_tca['columns'][$field]['config']['eval']=='datetime' || $field=='tstamp' || $field=='crdate'){
			return date('d/m/Y H:i',$val) ;
		}
		//mm_relation
		if($table_tca['columns'][$field]['config']['type']=='select' || $table_tca['columns'][$field]['config']['foreign_table']){
			if($table_tca['columns'][$field]['config']['foreign_table']) {
				$foreign_table = $table_tca['columns'][$field]['config']['foreign_table'];
				if(($val!="")&&($val!=0)) {
					$foreign_tca = $this->misc->getTableTCA($foreign_table);
					$val_array = explode(",",$val);
					$temp = array();
					foreach($val_array as $key=>$value) {
						$get_record = t3lib_BEfunc::getRecord($foreign_table,$value);
						$temp[] = $get_record[$foreign_tca['ctrl']['label']];
					}
					return implode(",",$temp);
				}
			} else if ($table_tca['columns'][$field]['config']['itemsProcFunc']) {
			} else {
				$items = $table_tca['columns'][$field]['config']['items'];
				if($items){
					//get the label
					foreach($items as $keyItem=>$valItem){
						if($valItem[1]==$val){
							return $GLOBALS['TSFE']->sL($valItem[0]);
						}
					}
				}
			}
		}
		return;
	}

}

if (defined('TYPO3_MODE') && $GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/t3devapi/class.tx_t3devapi_database.php'])    {
	include_once($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/t3devapi/class.tx_t3devapi_database.php']);
}

?>
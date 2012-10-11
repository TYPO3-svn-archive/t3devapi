<?php

########################################################################
# Extension Manager/Repository config file for ext "t3devapi".
#
# Auto generated 11-10-2012 11:10
#
# Manual updates:
# Only the data in the array - everything else is removed by next
# writing. "version" and "dependencies" must not be touched!
########################################################################

$EM_CONF[$_EXTKEY] = array(
	'title' => 'TYPO3 Developer API',
	'description' => 'A Powerful API for your (my ?) TYPO3 developments. No manual but the classes are well documented :-)',
	'category' => 'misc',
	'shy' => 0,
	'version' => '0.5.5',
	'dependencies' => '',
	'conflicts' => '',
	'priority' => '',
	'loadOrder' => '',
	'module' => '',
	'state' => 'stable',
	'uploadfolder' => 0,
	'createDirs' => '',
	'modify_tables' => '',
	'clearcacheonload' => 0,
	'lockType' => '',
	'author' => 'CERDAN Yohann',
	'author_email' => 'cerdanyohann@yahoo.fr',
	'author_company' => 'Site\'nGo',
	'CGLcompliance' => '',
	'CGLcompliance_note' => '',
	'constraints' => array(
		'depends' => array(
			'php' => '5.0.0-0.0.0',
			'typo3' => '4.0.0-4.6.99',
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
	'_md5_values_when_last_written' => 'a:25:{s:9:"ChangeLog";s:4:"ee7b";s:12:"ext_icon.gif";s:4:"3d85";s:17:"ext_localconf.php";s:4:"c73a";s:36:"Classes/class.tx_t3devapi_befunc.php";s:4:"f5c8";s:38:"Classes/class.tx_t3devapi_calendar.php";s:4:"e6c8";s:36:"Classes/class.tx_t3devapi_config.php";s:4:"ea02";s:38:"Classes/class.tx_t3devapi_database.php";s:4:"19dd";s:36:"Classes/class.tx_t3devapi_export.php";s:4:"18cd";s:43:"Classes/class.tx_t3devapi_fertehtmlarea.php";s:4:"d9e1";s:35:"Classes/class.tx_t3devapi_fluid.php";s:4:"d5f0";s:34:"Classes/class.tx_t3devapi_html.php";s:4:"4463";s:43:"Classes/class.tx_t3devapi_miscellaneous.php";s:4:"1c05";s:36:"Classes/class.tx_t3devapi_pibase.php";s:4:"b901";s:38:"Classes/class.tx_t3devapi_profiler.php";s:4:"dcab";s:40:"Classes/class.tx_t3devapi_tagbuilder.php";s:4:"1d49";s:40:"Classes/class.tx_t3devapi_templating.php";s:4:"e762";s:38:"Classes/class.tx_t3devapi_validate.php";s:4:"e061";s:33:"Classes/Utility/Compatibility.php";s:4:"2e71";s:24:"Classes/Utility/Page.php";s:4:"496b";s:49:"Classes/ViewHelpers/Widget/PaginateViewHelper.php";s:4:"6d20";s:60:"Classes/ViewHelpers/Widget/Controller/PaginateController.php";s:4:"d0a9";s:40:"Resources/Private/Language/locallang.xml";s:4:"4b8d";s:66:"Resources/Private/Templates/ViewHelpers/Widget/Paginate/Index.html";s:4:"e2e8";s:36:"Tests/Unit/tx_t3devapi_html_Test.php";s:4:"9dee";s:24:"tca/class.tx_tca_map.php";s:4:"7702";}',
);

?>
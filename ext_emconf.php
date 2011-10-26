<?php

########################################################################
# Extension Manager/Repository config file for ext "t3devapi".
#
# Auto generated 24-10-2011 14:39
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
	'version' => '0.4.16',
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
	'_md5_values_when_last_written' => 'a:14:{s:9:"ChangeLog";s:4:"39ca";s:28:"class.tx_t3devapi_befunc.php";s:4:"0897";s:30:"class.tx_t3devapi_calendar.php";s:4:"7161";s:28:"class.tx_t3devapi_config.php";s:4:"f7eb";s:30:"class.tx_t3devapi_database.php";s:4:"f1f8";s:28:"class.tx_t3devapi_export.php";s:4:"8563";s:27:"class.tx_t3devapi_fluid.php";s:4:"f1e7";s:26:"class.tx_t3devapi_html.php";s:4:"5336";s:35:"class.tx_t3devapi_miscellaneous.php";s:4:"7116";s:28:"class.tx_t3devapi_pibase.php";s:4:"7dd5";s:32:"class.tx_t3devapi_templating.php";s:4:"9dba";s:12:"ext_icon.gif";s:4:"3d85";s:17:"ext_localconf.php";s:4:"87f7";s:24:"tca/class.tx_Tca_Map.php";s:4:"7702";}',
	'suggests' => array(
	),
);

?>
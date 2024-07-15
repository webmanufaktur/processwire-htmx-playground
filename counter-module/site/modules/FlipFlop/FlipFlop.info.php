<?php

namespace ProcessWire;

/**
 * Module info file that tells ProcessWire about this module. 
 * 
 * If you prefer to keep everything in the main module file, you can move this 
 * to a static getModuleInfo() method in the Helloworld.module.php file, which 
 * would return the same array as below. However, an external file like this 
 * is often preferable because it enables ProcessWire to determine the module
 * requirements before attempting to load the .module.php file.
 * 
 * Note: When updating this info for an already-installed module, you’ll need
 * to do a Modules > Refresh before you see your updated info. 
 * 
 * Required properties: title, version, summary. All others are optional.
 * 
 */

$info = array(

	'title' => 'FlipFlop',
	'version' => '1.0.0',
	'summary' => 'Adds API hooks for flipped and flopped functionality',
	'author' => 'MegaMind',
	'href' => 'https://nerd.to/pw/modules/flipflop/',
	'singular' => true,
	'autoload' => true,
	'icon' => 'database',
	'requires' => array(
		'ProcessWire>=3.0.240',
		'PHP>=8.2.0',
	),
	'installs' => array(
		'LazyCron',
		'PagePaths',
		'ProcessForgotPassword',
		'ProcessPageClone',
		'InputfieldRepeater',
		'FieldtypeRepeater',
		'FieldtypeOptions',
		'RockFrontend',
		'RockMigrations',
		'FieldtypeRepeater',
		'FieldtypeToggle',
		'TextformatterMarkdownExtra',
		'TracyDebugger',
		'PageListCustomChildren'
	),


	// for more properties that you can include in your module info, see comments 
	// the file: /wire/core/Module.php
);

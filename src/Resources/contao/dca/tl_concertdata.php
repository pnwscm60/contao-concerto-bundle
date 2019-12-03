<?php
/*
** DCA für concerto: tl_concertdata
** © 2019 Markus Schenker, Phi Network
*/
/*
 * Table tl_concertdata
 */
$GLOBALS['TL_DCA']['tl_concertdata'] = array
(
	// Config
	'config' => array
	(
		'dataContainer'               => 'Table',
		'enableVersioning'            => true,
		'sql' => array
		(
			'keys' => array
			(
				'id' => 'primary'
			)
		),
	),
	// List
	'list' => array
	(
		'sorting' => array
		(
			'mode'                    => 1,
			'fields'                  => array('title'),
			'flag'                    => 8,
			'panelLayout'             => 'filter;sort,search,limit'
		),
		'label' => array
		(
			'fields'                  => array('title'),
			'format'                  => '%s',
		),
		'global_operations' => array
		(
			'all' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['MSC']['all'],
				'href'                => 'act=select',
				'class'               => 'header_edit_all',
				'attributes'          => 'onclick="Backend.getScrollOffset()" accesskey="e"'
			)
		),
		'operations' => array
		(
			'edit' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_concert']['edit'],
				'href'                => 'act=edit',
				'icon'                => 'edit.gif'
			),
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_concert']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.gif',
				'attributes'          => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"'
			),
			'show' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_concert']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.gif',
				'attributes'          => 'style="margin-right:3px"'
			),
		)
	),
	// Palettes
	'palettes' => array
	(
		'default'	=> 'title;director;solisti;werkids;ensembleid;'
	),
	// Fields
	'fields' => array
	(
		'id' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL auto_increment"
		),
		'tstamp' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),
		'ensembleid' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_concertdata']['ensembleid'],
			'inputType'               => 'text',
			'sql'                     => "int(3) unsigned NOT NULL default '0'"
		),
    'concertid' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_concertdata']['concertid'],
			'inputType'               => 'text',
			'sql'                     => "int(6) unsigned NOT NULL default '0'"
		),
		'datumzeit' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_concertdata']['datumzeit'],
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'unique'=>false,'tl_class'=>'w50'),
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),
		'ort' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_concertdata']['ort'],
			'inputType'               => 'text',
			'exclude'                 => true,
			'sorting'                 => true,
			'flag'                    => 11,
			'search'                  => true,
			'eval'                    => array('mandatory'=>true, 'unique'=>false, 'maxlength'=>255, 'tl_class'=>'w50'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
	)
);

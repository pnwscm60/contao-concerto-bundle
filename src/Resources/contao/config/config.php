<?php
/**
 * Back end modules

 * Front end modules
 */
/*
array_insert($GLOBALS['FE_MOD'], 4, array
(
	'concerto' => array
	(
		'werke'   => 'Pnwscm60\ConcertoBundle\ModuleWerke'
	)
));
*/
use Pnwscm60\ConcertoBundle\Module\ModuleWerke; 

$GLOBALS['FE_MOD']['concerto'] = [ 
    'werke' => ModuleWerke::class, 
];  


<?php
/**
 * Back end modules

 * Front end modules
 */

use Pnwscm60\ConcertoBundle\Module\ModuleWerke;
use Pnwscm60\ConcertoBundle\Module\ModuleEnsemble;

$GLOBALS['FE_MOD']['concerto'] = [ 
    'werke' => ModuleWerke::class,
	'ensemble' => ModuleWerke::class,
];  


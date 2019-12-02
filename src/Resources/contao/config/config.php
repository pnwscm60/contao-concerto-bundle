<?php
/**
 * Back end modules

 * Front end modules
 */

use Pnwscm60\ConcertoBundle\Module\ModuleWerke;
use Pnwscm60\ConcertoBundle\Module\ModuleEnsemble;
use Pnwscm60\ConcertoBundle\Module\ModuleConcert

$GLOBALS['FE_MOD']['concerto'] = [ 
    'werke' => ModuleWerke::class,
	'ensemble' => ModuleEnsemble::class,
	'concert' => ModuleConcert::class
];  


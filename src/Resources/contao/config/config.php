<?php
/**
 * Back end modules

 * Front end modules
 */
//$GLOBALS['FE_MOD']['Concerto']['werke'] = 'Pnwscm60\ContaoConcertoBundle\ModuleWerke';
array_insert($GLOBALS['FE_MOD'], 4, array
(
	'concerto' => array
	(
		'werke'   => 'Pnwscm60\Concerto\ModuleWerke'
	)
));
?>

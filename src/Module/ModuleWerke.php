<?php
namespace Pnwscm60\ConcertoBundle;
class ModuleWerke extends \Contao\Module
{
	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'mod_werke';
 
	public function generate()
    {
        if (TL_MODE == 'BE')
        {
            $objTemplate = new \BackendTemplate('be_wildcard');
            $objTemplate->wildcard = '### Werke ###';
            return $objTemplate->parse();
		}
        return parent::generate();
    }
	/**
	 * Compile the current element
	 */
	protected function compile()
	{
        //DATENBANK-VERBINDUNG
        $db = \Contao\System::getContainer()->get('database_connection'); 
  
        //memberid = frontendUser
        //$this->import('FrontendUser', 'User');
	//	$userid = $this->User->id;
        
       
        
        /*** WERKLISTE ***/
        echo "Werkliste";
           
       
    }
}
?>

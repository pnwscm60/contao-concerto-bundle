<?php
namespace Pnwscm60\ConcertoBundle\Module;
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
        $sql="SELECT tl_catalog.id as cid, komponist, title, besetzung FROM tl_catalog ORDER by komponist, title";
	$stat = $db->executeQuery($sql);
            while ($objCat = $stat->fetch())
		      {
		    	echo "T:".$objCat->title." K:".$objCat->komponist;
			$arrCat[] = array(
				'cid' => $objCat->cid,
				'title' => $objCat->title,
                		'komponist' => $objCat->komponist,
                		'besetzung' => $objCat->besetzung,
			);
		}
        $this->Template->allcat = $arrCat;
        $this->Template->todo = 'wlist';
    }
}

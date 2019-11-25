<?php
namespace Pnwscm60\ConcertoBundle;

class ModuleWerke extends Module
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
        $this->import('FrontendUser', 'User');
		$userid = $this->User->id;
        
        /*** EDITIERTE DATEN SPEICHERN ***/
        if($_REQUEST['trg']=='swerked'){
            $cid = $_REQUEST['cid'];
            $title = $_REQUEST['title'];
            $komponist = $_REQUEST['komponist'];
            $besetzung = $_REQUEST['besetzung'];
            $db->update('catalog', array('title' => $title, 'komponist' => $komponist, 'besetzung' => $besetzung), array('id' => $cid));
            $sedit = '1';
        }
        /*** ZU EDITIERENDE DATEN ABRUFEN ***/
        if($_REQUEST['trg']=='werked'){
            $catid = $_REQUEST['catid']; // key
            $sql="SELECT tl_catalog.id as cid, komponist, title, besetzung FROM tl_catalog WHERE tl_catalog.id = ?";
		    $stat = $db->prepare($sql);
            $stat->bindValue(1, $catid);
            $objCat = $stat->fetch();
            $this->Template->cid = $objCat->cid;
            $this->Template->title = $objCat->title;
            $this->Template->komponist = $objCat->komponist;
            $this->Template->besetzung = $objCat->besetzung;
            $this->Template->todo = 'edcat';
        }
        
        /*** WERKLISTE ***/
        if($_REQUEST['trg']=='catlist' || $sedit ==1 || $_REQUEST['trg']==''){
            $sql="SELECT tl_catalog.id as cid, komponist, title, besetzung FROM tl_catalog ORDER by komponist, title";
		    $stat = $db->executeQuery($sql);
            while ($objCat = $stat->fetchAll())
		      {
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
           
        /*** WERK LÖSCHEN ***/
            if($_REQUEST['trg']=='delcat'){
            /*    
            $cid = $_REQUEST['cid'];
            $sql='DELETE from tl_category WHERE id='.$cid.';'; 
		  $objResult = \Database::getInstance()->execute($sql);
            $this->Template->mess = "Kategorie wurde erfolgreich gelöscht";
            */
            echo '<script type="text/javascript"> 
            alert ( "Löschen des Werks führt zu Dateninkonsistenz. Bitte um Löschung an Admin melden." );
            location.href = "admin.html?trg=admin&todo=clist";
            </script>';
            exit;
        }
    }
}
?>

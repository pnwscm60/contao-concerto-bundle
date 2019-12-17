<?php
namespace Pnwscm60\ConcertoBundle\Module;
class ModuleEnsemble extends \Contao\Module
{
	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'mod_ensemble';
 
	public function generate()
    {
        if (TL_MODE == 'BE')
        {
            $objTemplate = new \BackendTemplate('be_wildcard');
            $objTemplate->wildcard = '### Ensemble ###';
            return $objTemplate->parse();
		}
        return parent::generate();
    }
	/**
	 * Compile the current element
	 */
	protected function compile()
	{
	/* Datenbank abrufen*/
	$this->import('Database');
	
	/* EDITIERTES WERK SPEICHERN*/
	if($_REQUEST['trg']=='swerked'){
            $cid = $_REQUEST['cid'];
            $title = $_REQUEST['title'];
            $komponist = $_REQUEST['komponist'];
			$komponistvn = $_REQUEST['komponistvn'];
            $besetzung = $_REQUEST['besetzung'];
			$typ = $_REQUEST['typ'];
			$epoche = $_REQUEST['epoche'];
            $set = "title = '$title', komponist = '$komponist', komponistvn = '$komponistvn', besetzung = '$besetzung', typ = $typ, epoche = $epoche";
            $this->Database->prepare("UPDATE tl_catalog SET $set WHERE id=$cid;")->execute();
		$sedit = '1';
        }
	/*** ZU EDITIERENDE DATEN ABRUFEN ***/
        if($_REQUEST['trg']=='ensed'){
            $ensid = $_REQUEST['eid']; // key
            $sql="SELECT tl_ensemble.id as eid, title, website, email, memberid FROM tl_ensemble WHERE tl_member.id = $ensid;";
			$result = $this->Database->prepare($sql)->execute();
            	$this->Template->eid = $result->eid;
            	$this->Template->title = $result->title;
				$this->Template->website = $result->website;
            	$this->Template->email = $result->email;
            	$this->Template->memberid = $result->memberid;
            	$this->Template->todo = 'edens';
        }
	if($_REQUEST['trg']=='newens'){	
        	$this->Template->todo = 'newens';
	}
    /*** ENSEMBLELISTE ***/    
	$sql ="SELECT tl_ensemble.id as eid, title, website, email FROM tl_ensemble ORDER by title";
        $result = $this->Database->prepare($sql)->execute();
        while($result->next())
        {
            $arrEns[] = array(
		'eid' => $result->eid,
		'title' => $result->title,
        'website' => $result->website,
        'email' => $result->email,
			);
        }
    //    
    $sqlm = "SELECT id,lastname,firstname from tl_member ORDER by lastname,firstname;";
        $mresult = $this->Database->prepare($sqlm)->execute();
        while($mresult->next())
        {
            $arrMem[] = array(
		'mid' => $mresult->id,
		'lastname' => $mresult->lastname,
        'firstname' => $mresult->firstname,
        'email' => $result->email,
			);
        }
    $this->Template->allmem = $arrMem;    
	$this->Template->allens = $arrEns;
    }
}

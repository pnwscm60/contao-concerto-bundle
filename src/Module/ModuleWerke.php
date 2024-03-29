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
        if($_REQUEST['trg']=='werked'){
            $catid = $_REQUEST['cid']; // key
            $sql="SELECT tl_catalog.id as cid, komponist, komponistvn, title, besetzung, typ, epoche FROM tl_catalog WHERE tl_catalog.id = $catid;";
			$result = $this->Database->prepare($sql)->execute();
            	$this->Template->cid = $result->cid;
            	$this->Template->title = $result->title;
				$this->Template->komponistvn = $result->komponistvn;
            	$this->Template->komponist = $result->komponist;
            	$this->Template->besetzung = $result->besetzung;
		$this->Template->typ = $result->typ;
		$this->Template->epoche = $result->epoche;
            	$this->Template->todo = 'edcat';
        }
	if($_REQUEST['trg']=='newwerk'){	
        	$this->Template->todo = 'newcat';
	}
	$sql ="SELECT tl_catalog.id as cid, komponist, komponistvn, title, besetzung FROM tl_catalog ORDER by komponist, title";
        $result = $this->Database->prepare($sql)->execute();
        while($result->next())
        {
            $arrCat[] = array(
		'cid' => $result->cid,
		'title' => $result->title,
                'komponist' => $result->komponist,
		'komponistvn' => $result->komponistvn,
                'besetzung' => $result->besetzung,
			);
        }
	$this->Template->allcat = $arrCat;
    }
}

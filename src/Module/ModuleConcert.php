<?php
namespace Pnwscm60\ConcertoBundle\Module;
class ModuleConcert extends \Contao\Module
{
	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'mod_concert';
 
	public function generate()
    {
        if (TL_MODE == 'BE')
        {
            $objTemplate = new \BackendTemplate('be_wildcard');
            $objTemplate->wildcard = '### Concert ###';
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
	/* NEUES KONZERT SPEICHERN*/
    if($_REQUEST['trg']=='swerked'){
          $set = "title = '$title', komponist = '$komponist', komponistvn = '$komponistvn', besetzung = '$besetzung', typ = $typ, epoche = $epoche";
            $this->Database->prepare("UPDATE tl_catalog SET $set WHERE id=$cid;")->execute();
		$sedit = '1';   
    }
        
	/*** EDITIERTES KONZERT SPEICHERN ***/
	if($_REQUEST['trg']=='savenewconc'){
            $coid = $_REQUEST['coid'];
            $title = $_REQUEST['cotitle'];
            $ensembleid = $_REQUEST['ensemble'];
			$director = $_REQUEST['codirector'];
            $solisti = $_REQUEST['cosolisti'];
		    // Parse Werke into one string
            $werkids = $_REQUEST['werkids_1'];
            for($i=2;$i<=25;$i++){
                if($_REQUEST['werkids_'.$i.'']){ //werk_2 ist immer
                    $werkids .= ",".$_REQUEST['werkids_'.$i.''];
                }
            }
            $sql='INSERT into tl_concert (tstamp,ensembleid, title, director, solisti, werkids) VALUES (NOW(),'.$ensembleid.',"'.$title.'","'.$director.'","'.$solisti.'","'.$werkids.'");';
            //echo $sql;    
            $result = $this->Database->prepare($sql)->execute();
            $this->Template->todo = 'klist';
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
	if($_REQUEST['trg']=='newconc'){	
        $this->Template->todo = 'newconc';
        //Daten Ensembles
        $sql="SELECT tl_ensemble.id as ensembleid, title as ensemblename FROM tl_ensemble ORDER by ensemblename";
        $result = $this->Database->prepare($sql)->execute();
            while($result->next())
            {
                $arrEnse[] = array(
		          'ensembleid' => $result->ensembleid,
		          'ensemblename' => $result->ensemblename,
			);
        }
	    $this->Template->ensembles = $arrEnse;
        //Daten Werklist
        $sql="SELECT tl_catalog.id as werkid, komponist as komp, title as werktitle FROM tl_catalog ORDER by komponist,werktitle";
        $result = $this->Database->prepare($sql)->execute();
            while($result->next())
            {
                $arrWerke[] = array(
		          'werkid' => $result->werkid,
                    'komp' => $result->komp,    
		          'werktitle' => $result->werktitle,
			);
        }
	   $this->Template->werklist = $arrWerke;
        
	}
    /*** KONZERTLISTE ERSTELLEN ***/    
	$sql ="SELECT tl_concert.id as coid, title FROM tl_concert ORDER by title";
        $result = $this->Database->prepare($sql)->execute();
        while($result->next())
        {
            $arrEns[] = array(
		'coid' => $result->coid,
		'title' => $result->title,

			);
        }
	$this->Template->allens = $arrEns;
    }
}

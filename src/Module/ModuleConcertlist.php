<?php
namespace Pnwscm60\ConcertoBundle\Module;
class ModuleConcertlist extends \Contao\Module
{
	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'mod_concertlist';
 
	public function generate()
    {
        if (TL_MODE == 'BE')
        {
            $objTemplate = new \BackendTemplate('be_wildcard');
            $objTemplate->wildcard = '### Concertlist ###';
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
        
	/*** NEUES KONZERT SPEICHERN ***/
	if($_REQUEST['trg']=='savenewconc'){
            $coid = $_REQUEST['coid'];
            $title = $_REQUEST['cotitle'];
            $ensembleid = $_REQUEST['ensemble'];
			$director = $_REQUEST['codirector'];
            $solisti = $_REQUEST['cosolisti'];
		    // Parse Werke into one string
            $werkids = $_REQUEST['werkids_1'];
            for($i=2;$i<=12;$i++){
                if($_REQUEST['werkids_'.$i]){
                    $werkids .= ",".$_REQUEST['werkids_'.$i.''];
                }
            }
            $sql='INSERT into tl_concert (tstamp,ensembleid, title, director, solisti, werkids) VALUES (NOW(),'.$ensembleid.',"'.$title.'","'.$director.'","'.$solisti.'","'.$werkids.'");';
            $result = $this->Database->prepare($sql)->execute();
            $concertid = $result->insertId;
            //echo "Zeit=".$_REQUEST['time1']." ";
            $td= explode('.',$_REQUEST['date1']);
            $tt= explode(':',$_REQUEST['time1']);

            if ($tt[1]=='00'){$tt[1]=0;}
            $tstmp = mktime($tt[0],$tt[1],0,$td[1],$td[0],$td[2]);
            $heute = time();
            $ort = $_REQUEST['ort1'];
            $ksaal = $_REQUEST['ksaal1'];    
            $sql='INSERT into tl_concertdata (tstamp, ensembleid,concertid,datumzeit,ort,ksaal) VALUES ('.$heute.','.$ensembleid.','.$concertid.','.$tstmp.',"'.$ort.'","'.$ksaal.'")';
            $result = $this->Database->prepare($sql)->execute();
            for($i=2;$i<=5;$i++){
                if($_REQUEST['date'.$i.'']!=''){
                    $td = explode('.',$_REQUEST['date'.$i.'']);
                    $tt = explode(':',$_REQUEST['time'.$i.'']);
                    if ($tt[1]=='00'){$tt[1]=0;}
                    $tstmp = mktime($tt[0],$tt[1],0,$td[1],$td[0],$td[2]);
                    $ort = $_REQUEST['ort'.$i.''];
                    $ksaal = $_REQUEST['ksaal'.$i.''];
                    
                $sql1='INSERT into tl_concertdata (tstamp, ensembleid,concertid,datumzeit,ort,ksaal) VALUES ('.$heute.','.$ensembleid.','.$concertid.','.$tstmp.',"'.$ort.'","'.$ksaal.'")';
                $result = $this->Database->prepare($sql1)->execute();
                }
            }
                        
            
            $this->Template->todo = 'klist';
    }
        
	/*** ZU EDITIERENDE DATEN ABRUFEN ***/ // Noch kopiert aus 
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
    /* NEUES KONZERT ANLEGEN */    
	if($_REQUEST['trg']=='newconc'){	
        $this->Template->todo = 'newconc';
        //DATEN ENSEMBLES
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
        //DATEN WERKLISTE
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
        //DATEN BEREITS EINGETRAGENE KONZERTE
        $sql = "SELECT tl_concert.id as coid, datumzeit, ort, ksaal, tl_concert.title as ktitle, tl_ensemble.title, director, solisti, werkids from tl_concert, tl_concertdata, tl_ensemble WHERE concertid = tl_concert.id AND tl_concert.ensembleid = tl_ensemble.id";
        $result = $this->Database->prepare($sql)->execute();
        while($result->next())
            {
                $sqlw = "SELECT komponist, komponistvn, title from tl_catalog WHERE id in (".$result->werkids.");";
                $wresult = $this->Database->prepare($sqlw)->execute();
                while($wresult->next())
                    {
                    $prog .= $wresult->komponist." ".$wresult->komponistvn.": ".$wresult->title.", ";     
                    }
                $datum = date('d.m.Y', $result->datumzeit);
                $zeit = date('H:i', $result->datumzeit);
                $arrKonz[] = array(
		          'ort' => $result->ort,
                  'ksaal' => $result->ksaal,    
		          'ktitle' => $result->ktitle,
                    'datum' => $datum,
                    'zeit'  => $zeit,
                    'tstmp' => $result->datumzeit,
			);
        }
        $this->Template->konzlist = $arrKonz; 
    }
        
    /*** KONZERTLISTE ERSTELLEN ***/    
	$sql = "SELECT tl_concert.id as coid, datumzeit, ort, ksaal, tl_concert.title as ktitle, tl_ensemble.title, director, solisti, werkids from tl_concert, tl_concertdata, tl_ensemble WHERE concertid = tl_concert.id AND tl_concert.ensembleid = tl_ensemble.id ORDER by datumzeit";
        $result = $this->Database->prepare($sql)->execute();
        while($result->next())
            {
                $sqlwe = "SELECT komponist, komponistvn, title from tl_catalog WHERE id in (".$result->werkids.");";
                $wresult = $this->Database->prepare($sqlwe)->execute();
                $prog='';
                while($wresult->next())
                    {
                    $prog .= $wresult->komponist." ".$wresult->komponistvn.": ".$wresult->title."&#10;";
                    }
                $datum = date('d.m.Y', $result->datumzeit);
                $zeit = date('H:i', $result->datumzeit);
                $arrKonz[] = array(
                    'ort' => $result->ort,
                    'ksaal' => $result->ksaal,    
                    'ktitle' => $result->ktitle,
                    'datum' => $datum,
                    'zeit'  => $zeit,
                    'tstmp' => $result->datumzeit,
                    'director' => $result->director,
                    'solisti' => $result->solisti,
                    'prog' => $prog,
			);
        }
    $this->Template->konzlist = $arrKonz; 
        
    }
}

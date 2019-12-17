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
        
	/* EDITIERTES KONZERT SPEICHERN*/
    if($_REQUEST['trg']=='saveedconc'){
    //Daten einlesen
        $coid = $_REQUEST['coid'];
        $title = $_REQUEST['cotitle'];
        $ensembleid = $_REQUEST['ensemble'];
        $director = $_REQUEST['codirector'];
        $solisti = $_REQUEST['cosolisti'];
        $heute = time();
        // Parse Werke into one string
            $werkids = "";
            for($i=1;$i<=12;$i++){
                if($_REQUEST['werkids_'.$i]&&$_REQUEST['wdel'.$i]!='delw'.$i){
                    $werkids .= $_REQUEST['werkids_'.$i.''].",";
                }
            }
            $werkids = substr($werkids, 0, -1);
        $sqlc='UPDATE tl_concert SET tstamp = '.$heute.',ensembleid = '.$ensembleid.', title = "'.$title.'", director = "'.$director.'", solisti = "'.$solisti.'", werkids = "'.$werkids.'" WHERE id = '.$coid.';';
        $result = $this->Database->prepare($sqlc)->execute();
        //Konzertdaten speichern > falls id vorhanden > UPDATE, sonst INSERT
         for($i=1;$i<=6;$i++){
                if($_REQUEST['date'.$i.'']!=''){
                    $td = explode('.',$_REQUEST['date'.$i.'']);
                    $tt = explode(':',$_REQUEST['time'.$i.'']);
                    if ($tt[1]=='00'){$tt[1]=0;}
                    $tstmp = mktime($tt[0],$tt[1],0,$td[1],$td[0],$td[2]);
                    $ort = $_REQUEST['ort'.$i.''];
                    $ksaal = $_REQUEST['ksaal'.$i.''];
                    if($_REQUEST['cdatid'.$i.'']==''){
                        $sqld = 'INSERT into tl_concertdata (tstamp, ensembleid,concertid,datumzeit,ort,ksaal) VALUES ('.$heute.','.$ensembleid.','.$coid.','.$tstmp.',"'.$ort.'","'.$ksaal.'")';
                        $result = $this->Database->prepare($sqld)->execute();
                    } else {
                        $sqld = 'UPDATE tl_concertdata SET tstamp = '.$heute.', ensembleid ='.$ensembleid.', concertid = '.$coid.', datumzeit = '.$tstmp.', ort = "'.$ort.'", ksaal = "'.$ksaal.'" WHERE id = '.$_REQUEST['cdatid'.$i.''];
                        $result = $this->Database->prepare($sqld)->execute();
                    }
                }
            //Do we have to delete something?
            if($_REQUEST['cdel'.$i.'']=="delc".$i){
                $sqldelc = 'DELETE FROM tl_concertdata WHERE id ='.$_REQUEST['cdatid'.$i.''];
                $result = $this->Database->prepare($sqldelc)->execute();
            }
         }
    }
        
	/*** NEUES KONZERT SPEICHERN ***/
	if($_REQUEST['trg']=='savenewconc'){
            $coid = $_REQUEST['coid'];
            $title = $_REQUEST['cotitle'];
            $ensembleid = $_REQUEST['ensemble'];
			$director = $_REQUEST['codirector'];
            $solisti = $_REQUEST['cosolisti'];
            $heute = time();
		    // Parse Werke into one string
            $werkids = $_REQUEST['werkids_1'];
            for($i=2;$i<=12;$i++){
                if($_REQUEST['werkids_'.$i]){
                    $werkids .= ",".$_REQUEST['werkids_'.$i.''];
                }
            }
            $sql='INSERT into tl_concert (tstamp,ensembleid, title, director, solisti, werkids) VALUES ('.$heute.','.$ensembleid.',"'.$title.'","'.$director.'","'.$solisti.'","'.$werkids.'");';
            $result = $this->Database->prepare($sql)->execute();
            $concertid = $result->insertId;
            $td= explode('.',$_REQUEST['date1']);
            $tt= explode(':',$_REQUEST['time1']);
            if ($tt[1]=='00'){$tt[1]=0;}
            $tstmp = mktime($tt[0],$tt[1],0,$td[1],$td[0],$td[2]);
            
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
        
	/*** ZU EDITIERENDE DATEN ABRUFEN ***/  
        if($_REQUEST['trg']=='concertedit'){
            $coid = $_REQUEST['coid']; // key
            $sql="SELECT tl_concert.id as coid, tl_concert.title as ktitle, ensembleid, director, solisti, werkids from tl_concert, tl_ensemble WHERE tl_concert.id = ".$coid." AND tl_concert.ensembleid = tl_ensemble.id";
            $sqld="SELECT tl_concertdata.id as cdid, datumzeit, ort, ksaal from tl_concertdata WHERE concertid = ".$coid.";";
            $result = $this->Database->prepare($sql)->execute();
            $ensembleid = $result->ensembleid;
            $this->Template->ensembleid = $result->ensembleid;
            $this->Template->ktitle = $result->ktitle;
            $this->Template->director = $result->director;
            $this->Template->solisti = $result->solisti;
            $this->Template->coid = $result->coid;
             //Bereitstellen Konzertdaten
            $dresult = $this->Database->prepare($sqld)->execute();
             while($dresult->next()){
                 $datum = date("d.m.Y", $dresult->datumzeit);
                 $zeit = date("H:i", $dresult->datumzeit);
                $arrKdat[] = array(
                    'cdatid' => $dresult->cdid,
                    'ort' => $dresult->ort,
                    'ksaal' => $dresult->ksaal,
                    'datum' => $datum,
                    'zeit' => $zeit,
                );
            }
            $this->Template->kdat = $arrKdat;
            
            // Bereitstellen Ensembles
            $sqle="SELECT tl_ensemble.id, title from tl_ensemble ORDER by title;";
            $eresult = $this->Database->prepare($sqle)->execute();
            while($eresult->next()){
                $arrEns[] = array(
                    'eid' => $eresult->id,
                    'etitle' => $eresult->title,
                );
            }
            $this->Template->kens = $arrEns;
            // Bereitstellen Werke > alle Werke
            $this->Template->prog = explode(",",$result->werkids);
            
            $sqlw="SELECT id, komponist, komponistvn, title from tl_catalog ORDER by komponist,title;";
            $wresult = $this->Database->prepare($sqlw)->execute();
            while($wresult->next()){
                $arrWerk[] = array(
                    'werkid' => $wresult->id,
                    'komp' => $wresult->komponist,
                    'werktitle' => $wresult->title,
                );
            }
            $this->Template->werklist = $arrWerk;
            // Konzertdaten
            
            $this->Template->todo = 'edconc';
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
                    'coid' => $result->coid,
			);
        }
    $this->Template->konzlist = $arrKonz; 
        
    }
}

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
	
        /*** KONZERTLISTE ERSTELLEN ***/    
	$sql = "SELECT tl_concert.id as coid, datumzeit, ort, ksaal, tl_concert.title as ktitle, tl_ensemble.title as ensemble, director, website, solisti, werkids from tl_concert, tl_concertdata, tl_ensemble WHERE concertid = tl_concert.id AND tl_concert.ensembleid = tl_ensemble.id ORDER by datumzeit";
        $result = $this->Database->prepare($sql)->execute();
        $c=0;
        while($result->next())
            {
                $sqlwe = "SELECT komponist, komponistvn, title from tl_catalog WHERE id in (".$result->werkids.");";
                $wresult = $this->Database->prepare($sqlwe)->execute();
                $prog='';
                while($wresult->next())
                    {
                    $prog .= $wresult->komponistvn." ".$wresult->komponist." – ".$wresult->title."</br>";
                    }
                $datum = date('d.m.Y', $result->datumzeit);
                $zeit = date('H:i', $result->datumzeit);
                if($c % 2 !=0){
                    $arrKonzleft[] = array(
                        'ort' => $result->ort,
                        'ksaal' => $result->ksaal,    
                        'ktitle' => $result->ktitle,
                        'ensemble' => $result->ensemble,
                        'website' => $result->website,
                        'datum' => $datum,
                        'zeit'  => $zeit,
                        'tstmp' => $result->datumzeit,
                        'director' => $result->director,
                        'solisti' => $result->solisti,
                        'prog' => $prog,
			         );        
                } else {
                    $arrKonzright[] = array(
                        'ort' => $result->ort,
                        'ksaal' => $result->ksaal,    
                        'ktitle' => $result->ktitle,
                        'ensemble' => $result->ensemble,
                        'website' => $result->website,
                        'datum' => $datum,
                        'zeit'  => $zeit,
                        'tstmp' => $result->datumzeit,
                        'director' => $result->director,
                        'solisti' => $result->solisti,
                        'prog' => $prog,
			         );
             }
        $c++;
                $arrKonz[] = array(
                        'ort' => $result->ort,
                        'ksaal' => $result->ksaal,    
                        'ktitle' => $result->ktitle,
                        'ensemble' => $result->ensemble,
                        'website' => $result->website,
                        'datum' => $datum,
                        'zeit'  => $zeit,
                        'tstmp' => $result->datumzeit,
                        'director' => $result->director,
                        'solisti' => $result->solisti,
                        'prog' => $prog,
			         );
        }
    $this->Template->konzleft = $arrKonzleft; 
    $this->Template->konzright = $arrKonzright;
    $this->Template->konz = $arrKonz;
    }
}

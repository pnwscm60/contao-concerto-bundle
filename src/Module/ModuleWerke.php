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
        $this->import('Database');
        $sql ="SELECT tl_catalog.id as cid, komponist, title, besetzung FROM tl_catalog ORDER by komponist, title";
        $result = $this->Database->prepare($sql)->execute();
        while($result->next())
        {
            $arrCat[] = array(
		'cid' => $result->cid,
		'title' => $result->title,
                'komponist' => $result->komponist,
                'besetzung' => $result->besetzung,
			);
        }
	$this->Template->allcat = $arrCat;
	$this->Template->todo = 'wlist';
    }
}

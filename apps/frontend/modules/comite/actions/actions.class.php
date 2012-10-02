<?php

require_once dirname(__FILE__).'/../lib/comiteGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/comiteGeneratorHelper.class.php';

/**
 * comite actions.
 *
 * @package    sf_sandbox
 * @subpackage comite
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class comiteActions extends autoComiteActions
{
  public function executeListMandat(sfWebRequest $request)
  {
    $this->oComite = $this->getRoute()->getObject();
    $request->setParameter('id_comite', $this->oComite->getId());
    $this->redirect('comite/mandat?id_comite='.$this->oComite->getId());
  }

  public function executeMandat(sfWebRequest $request)
  {
    $this->oComite = Doctrine::getTable('tbl_comite')->find($request->getParameter('id_comite'));
    $this->form = new tbl_mandatForm(array(), array('id_profil' => $this->oComite->getIdProfil()));
    if ($request->isMethod('post')) {
        $this->form->bind($request->getParameter($this->form->getName()),
                                  $request->getFiles($this->form->getName()));
      if ($this->form->isValid())
      {
        $this->form->save();
        $this->getUser()->setFlash('notice', 'Enregistré');
        $this->form = new tbl_mandatForm(array(), array('id_profil' => $this->oComite->getIdProfil()));
      }
    }
    $this->oProfil = $this->oComite->getTblProfil();
    $this->oMandats = $this->oProfil->getTblMandat();
  }

  public function executeDeleteMandat(sfWebRequest $request)
  {
    $nIdMandat = $request->getParameter('id');
    $oMandat = Doctrine::getTable('tbl_mandat')->find($nIdMandat);
    $oMandat->delete();

    $this->redirect('comite/mandat?id_comite='.$request->getParameter('id_comite'));
  }
}

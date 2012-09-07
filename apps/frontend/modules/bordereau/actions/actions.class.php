<?php

require_once dirname(__FILE__).'/../lib/bordereauGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/bordereauGeneratorHelper.class.php';

/**
 * bordereau actions.
 *
 * @package    sf_sandbox
 * @subpackage bordereau
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class bordereauActions extends autoBordereauActions
{
    public function executeListShow(sfWebRequest $request)
    {
        $this->oBordereau = $this->getRoute()->getObject();
        //Récupère la liste des paiements
        $this->oPayments = Doctrine::getTable('tbl_payment')->findBy('id_bordereau', $this->oBordereau->getId());
        $this->oAvoirs   = Doctrine::getTable('tbl_avoir')->findBy('id_bordereau', $this->oBordereau->getId());
    }
    public function executeBatchDelete(sfWebRequest $request)
    {
        $ids = $request->getParameter('ids');

        $sMessage = $this->setReset($ids);
        if ($sMessage != '') {
            $this->getUser()->setFlash('error', $sMessage);
        } else {
            $this->getUser()->setFlash('notice', 'Supprimé');
        }
        $this->redirect('@tbl_bordereau');
    }
    private function setReset($ids, $nType)
    {
        $nIdUser = $this->getUser()->getGuardUser()->getId();
        $records = Doctrine_Query::create()
          ->from('tbl_bordereau')
          ->whereIn('id', $ids)
          ->execute();

        $sMessage = "";

        foreach ($records as $record)
        {
            if ($record->getIsPayed() == false && $record->getIdUser() == $nIdUser)
            {
                $record->delete();
            } else {
                $sMessage .= $record->getId().' Impossible de supprimé (déjà payé ou n\'appartient pas à cet utilisateur)';
            }
        }

        return $sMessage;
    }
}

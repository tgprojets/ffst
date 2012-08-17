<?php

require_once dirname(__FILE__).'/../lib/avoirGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/avoirGeneratorHelper.class.php';

/**
 * avoir actions.
 *
 * @package    sf_sandbox
 * @subpackage avoir
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class avoirActions extends autoAvoirActions
{
    public function executeBatchRegulation_cheque(sfWebRequest $request)
    {
        $ids = $request->getParameter('ids');

        $oTypePaiement = Doctrine::getTable('tbl_typepayment')->findOneBy('slug', 'cheque');

        $this->setRegulation($ids, $oTypePaiement->getId());
    }
    public function executeBatchRegulation_cb(sfWebRequest $request)
    {
        $ids = $request->getParameter('ids');

        $oTypePaiement = Doctrine::getTable('tbl_typepayment')->findOneBy('slug', 'cb');

        $this->setRegulation($ids, $oTypePaiement->getId());
    }
    public function executeBatchRegulation_paypal(sfWebRequest $request)
    {
        $ids = $request->getParameter('ids');

        $oTypePaiement = Doctrine::getTable('tbl_typepayment')->findOneBy('slug', 'paypal');

        $this->setRegulation($ids, $oTypePaiement->getId());
    }
    public function executeBatchRegulation_espece(sfWebRequest $request)
    {
        $ids = $request->getParameter('ids');

        $oTypePaiement = Doctrine::getTable('tbl_typepayment')->findOneBy('slug', 'espece');

        $this->setRegulation($ids, $oTypePaiement->getId());
    }
    private function setRegulation($ids, $nType)
    {
        $records = Doctrine_Query::create()
          ->from('tbl_avoir')
          ->whereIn('id', $ids)
          ->execute();

        $sMessage = "";

        foreach ($records as $record)
        {
            if ($record->getIsBrouillon() == false && $record->getIsUsed() == false)
            {
                $record->setIsUsed(true)
                       ->setIdTypepayment($nType)
                       ->save();

            } else {
                $sMessage = 'Vous ne pouvez pas régulariser un paiement qui est déjà réglé ou encours de validation';
            }
        }

        $this->getUser()->setFlash('notice', 'Terminé.'.$sMessage);
        $this->redirect('@tbl_avoir');

    }

}

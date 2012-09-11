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
    public function executeListPayment(sfWebRequest $request)
    {
        if ($this->getUser()->isClub())
        {
            $this->oPayments = Doctrine::getTable('tbl_payment')->findPaymentClub($this->getUser()->getClub()->getId());
            $this->oAvoirs   = Doctrine::getTable('tbl_avoir')->findAvoirClub($this->getUser()->getClub()->getId());
            if ($request->isMethod('post'))
            {
                $oClub = $this->getUser()->getClub();
                $oTypePayment = Doctrine::getTable('tbl_typepayment')->findOneBy('slug', 'cheque');
                $oBordereau = Doctrine::getTable('tbl_bordereau')->getLastExist($oClub->getId(), $oTypePayment->getId(), true);
                if (!$oBordereau) {
                    $oBordereau          = $this->createBordereau($oClub->getId(), $oTypePayment->getId());
                }
                $nAmount = 0;
                $ids = $request->getParameter('ids_payment');
                $oPayments = Doctrine::getTable('tbl_payment')->findBySql('id in ?', array($ids));
                foreach($oPayments as $oPayment)
                {
                    if ($oPayment->getIsPayed() == false) {
                        $nAmount = $nAmount + $oPayment->getAmount();
                        $oPayment->setIdBordereau($oBordereau->getId())->save();
                    }
                }
                $idsAvoir = $request->getParameter('ids_avoir');
                $oAvoirs = Doctrine::getTable('tbl_avoir')->findBySql('id in ?', array($idsAvoir));
                foreach($oAvoirs as $oAvoir)
                {
                    if ($oAvoir->getIsUsed() == false) {
                        $nAmount = $nAmount - $oAvoir->getAmount();
                        $oAvoir->setIdBordereau($oBordereau->getId())->save();
                    }
                }
                $oBordereau->setAmount($nAmount)->save();
                $this->cleanBordereau($oClub->getId(), $oBordereau->getId());

                $this->getUser()->setFlash('notice', 'Les éléments ont été ajoutés au bordereau n° : '.$oBordereau->getNum());
            }
        } else {
            $this->redirect('@tbl_bordereau');
        }
    }

    private function cleanBordereau($nClub, $nBordereau)
    {
        $oBordereaux = Doctrine::getTable('tbl_bordereau')->getAllBordereau($nClub);
        foreach ($oBordereaux as $oBordereau)
        {
            if ($oBordereau != $nBordereau) {
                $oPayments = $oBordereau->getTblPayment();
                $oAvoirs    = $oBordereau->getTblAvoir();
                if ($oPayments->count() == 0 && $oAvoirs->count() == 0) {
                    $oBordereau->delete();
                } else {
                    $nAmount = 0;
                    foreach ($oPayments as $oPayment)
                    {
                        $nAmount = $nAmount + $oPayment->getAmount();
                    }
                    foreach ($oAvoirs as $oAvoir)
                    {
                        $nAmount = $nAmount - $oAvoir->getAmount();
                    }
                    $oBordereau->setAmount($nAmount)->save();
                }

            }
        }
    }
    private function createBordereau($nIdClub, $nIdType)
    {
        $nIdUser      = $this->getUser()->getGuardUser()->getId();

        $oBordereau     = new tbl_bordereau();
        $oBordereau->setLib('Paiement Licence par Chéque')
                         ->setIdUser($nIdUser)
                         ->setIdClub($nIdClub)
                         ->setIsManual(true)
                         ->setNum(Licence::getNumBordereau())
                         ->setIdTypepayment($nIdType)
                         ->save();
        return $oBordereau;
    }

    public function executeListShow(sfWebRequest $request)
    {
        $this->oBordereau = $this->getRoute()->getObject();
        //Récupère la liste des paiements
        $this->oPayments = Doctrine::getTable('tbl_payment')->findBy('id_bordereau', $this->oBordereau->getId());
        $this->oAvoirs   = Doctrine::getTable('tbl_avoir')->findBy('id_bordereau', $this->oBordereau->getId());
    }

    public function executeListPayed(sfWebRequest $request)
    {
        $this->oBordereau = $this->getRoute()->getObject();
        if ($this->oBordereau->getIsPayed()) {
            $this->getUser()->setFlash('error', 'Bordereau déjà réglé');
        } else {
            $this->getUser()->setFlash('notice', 'Bordereau réglé');
            $oPayments = Doctrine::getTable('tbl_payment')->findBy('id_bordereau', $this->oBordereau->getId());
            $oAvoirs   = Doctrine::getTable('tbl_avoir')->findBy('id_bordereau', $this->oBordereau->getId());
            foreach($oPayments as $oPayment)
            {
                $oPayment->setIsPayed(true)
                         ->setDatePayment(date('Y-m-d'))
                         ->setIdTypepayment($this->oBordereau->getIdTypepayment())
                         ->save();

                $this->valideLicence($oPayment);

            }
            foreach($oAvoirs as $oAvoir)
            {
                $oAvoir->setIsUsed(true)
                       ->setIdTypepayment($this->oBordereau->getIdTypepayment())
                       ->save();
            }
            $this->oBordereau->setIsPayed(true)->save();
        }
        $this->redirect('@tbl_bordereau');
    }

    private function valideLicence($oPaiement)
    {
        if ($oPaiement->getIdLicence() != null) {
            $oLicence = $oPaiement->getTblLicence();
            if ($oLicence->getDateValidation() == null) {
                $oLicence->setDateValidation(date("Y-m-d H:i:s"))->save();
            }
        }
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

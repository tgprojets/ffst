<?php

require_once dirname(__FILE__).'/../lib/regulationGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/regulationGeneratorHelper.class.php';

/**
 * regulation actions.
 *
 * @package    sf_sandbox
 * @subpackage regulation
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class regulationActions extends autoRegulationActions
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
          ->from('tbl_payment')
          ->whereIn('id', $ids)
          ->execute();

        $sMessage = "";
        $aClub       = array();
        $aRegulation = array();
        foreach ($records as $record)
        {
            if ($record->getIsBrouillon() == false && $record->getIsPayed() == false)
            {
                $record->setDatePayment(date("Y-m-d H:i:s"))
                       ->setIsPayed(true)
                       ->setIdTypepayment($nType)
                       ->save();

                $this->valideLicence($record);
                if ($record->getTblClub())
                {
                    $nIdClub = $record->getTblClub()->getId();
                    if (!in_array($nIdClub, $aClub))
                    {
                        $aClub[] = $nIdClub;
                    }
                    $aRegulation[$nIdClub][] = $record;
                }
            } else {
                $sMessage = 'Vous ne pouvez pas régulariser un paiement qui est déjà réglé ou encours de validation';
            }
        }
        //Envoi message
        foreach($aClub as $nClub) {
            $oClub = Doctrine::getTable('tbl_club')->find($nClub);
            $this->sendMailContact(sfConfig::get('app_mail_contact'),  $this->getUser()->getEmailToSend($oClub), 'Règlements', $oClub, $aRegulation[$nClub]);
        }
        $this->getUser()->setFlash('notice', 'Terminé.'.$sMessage);
        $this->redirect('@tbl_payment');

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

    public function sendMailContact($psFrom, $psTo, $psSujet, $oClub, $paRegulation=array()) {
        $sDate = strtotime("now");
        $message = Swift_Message::newInstance();
        $cid = $message->embed(Swift_Image::fromPath('images/logo_mail.png'));
        $message->setFrom($psFrom);
        $message->setTo($psTo);
        $message->setReturnPath(sfConfig::get('app_mail_returnpath'));
        $message->setSubject($psSujet);
        $message->setBody($this->getPartial('contactMail', $arguments = array('cid' => $cid, 'aRegulations' => $paRegulation, 'oClub' => $oClub), 'text/html' ));
        $message->setDate($sDate);
        $message->setContentType('text/html');
        $this->getMailer()->send($message);
    }
  protected function buildQuery()
  {
    $tableMethod = $this->configuration->getTableMethod();
    if (null === $this->filters)
    {
      $this->filters = $this->configuration->getFilterForm($this->getFilters());
    }

    $this->filters->setTableMethod($tableMethod);

    $oSaison = Licence::getSaison();
    if (count($this->getFilters()) == 0 && $oSaison) {
        $aSaison["list_yearlicence"] = $oSaison->getId();
        $query = $this->filters->buildQuery($aSaison);
    } else {
        $query = $this->filters->buildQuery($this->getFilters());
    }
    // }

    $this->addSortQuery($query);

    $event = $this->dispatcher->filter(new sfEvent($this, 'admin.build_query'), $query);
    $query = $event->getReturnValue();

    return $query;
  }
}

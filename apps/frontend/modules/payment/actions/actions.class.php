<?php

/**
 * payment actions.
 *
 * @package    sf_sandbox
 * @subpackage payment
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class paymentActions extends sfActions
{
 /**
  * Executes paypalDev action
  *
  * @param sfRequest $request A request object
  */
  public function executePaypalDev(sfWebRequest $request)
  {
    if ($request->isMethod('post')) {
        $nBordereau = $request->getParameter('item_number');
        $oBordereau = Doctrine::getTable('tbl_bordereau')->find($nBordereau);
        $oTypePayment = Doctrine::getTable('tbl_typepayment')->findOneBy('slug', 'paypal');
        $this->payed($oBordereau, $oTypePayment->getId());
        return $this->redirect('@tbl_licence');
    }
  }

  private function payed($oBordereau, $nIdType)
  {
    $oPaiements = $oBordereau->getTblPayment();
    foreach ($oPaiements as $oPaiement)
    {
        $this->valideLicence($oPaiement);
        $oPaiement->setDatePayment(date("Y-m-d H:i:s"))
                  ->setIsPayed(true)
                  ->setIdTypepayment($nIdType)
                  ->save();
    }
    $oBordereau->setIsPayed(true)->save();
  }

  private function valideLicence($oPaiement)
  {
    $oLicence = $oPaiement->getTblLicence();
    if ($oLicence) {
        $oLicence->setDateValidation(date("Y-m-d H:i:s"))->save();
    }
  }
}

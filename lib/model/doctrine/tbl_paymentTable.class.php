<?php

/**
 * tbl_paymentTable
 *
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class tbl_paymentTable extends Doctrine_Table
{
    /**
     * Returns an instance of this class.
     *
     * @return object tbl_paymentTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('tbl_payment');
    }

    public function findPaymentLicByClub($nIdClub)
    {
        $q = $this->createQuery('p');
        $q->leftJoin('p.tbl_licence l')
          ->andWhere('l.id_club = ?', $nIdClub)
          ->andWhere('p.is_payed = ?', false);

        return $q->execute();
    }
    public function findPaymentClub($nIdClub, $bSaisie=false)
    {
        $q = $this->createQuery('p');
        $q->andWhere('p.id_club = ?', $nIdClub)
          ->andWhere('p.is_payed = ?', false);
        if ($bSaisie) {
          $q->andWhere('p.is_brouillon = true');
        }
        return $q->execute();
    }

    public function getAmountLicByClub($nIdClub)
    {
        $q = Doctrine_Query::create()
          ->select('SUM(p.amount) AS AmountTotal')
          ->from('tbl_payment p')
          ->leftJoin('p.tbl_licence l')
          ->andWhere('l.id_club = ?', $nIdClub)
          ->andWhere('p.is_payed = ?', false);
        $result = $q->fetchOne();
        return $result['AmountTotal'];
    }

    public function getAmountClub($nIdClub)
    {
        $q = Doctrine_Query::create()
          ->select('SUM(p.amount) AS AmountTotal')
          ->from('tbl_payment p')
          ->andWhere('p.id_club = ?', $nIdClub)
          ->andWhere('p.is_payed = ?', false);
        $result = $q->fetchOne();
        return $result['AmountTotal'];
    }

    public function validSaisie($nIdClub, $nIdUser)
    {
        $q = $this->createQuery('p');
        $q->where('p.id_club = ?', $nIdClub)
          ->andWhere('p.id_user = ?', $nIdUser)
          ->andWhere('p.is_brouillon = true');

        $oPaiements = $q->execute();
        foreach ($oPaiements as $oPaiement)
        {
          $oPaiement->setIsBrouillon(false)->save();
        }
    }

    public function cancelSaisie($nIdClub, $nIdUser)
    {
        $q = $this->createQuery('p');
        $q->where('p.id_club = ?', $nIdClub)
          ->andWhere('p.id_user = ?', $nIdUser)
          ->andWhere('p.is_brouillon = true');

        $oPaiements = $q->execute();
        foreach ($oPaiements as $oPaiement)
        {
          $oPaiement->delete();
        }
    }
}
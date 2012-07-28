<?php

/**
 * tbl_avoirTable
 *
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class tbl_avoirTable extends Doctrine_Table
{
    /**
     * Returns an instance of this class.
     *
     * @return object tbl_avoirTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('tbl_avoir');
    }

    public function findAvoirLicByClub($nIdClub)
    {
        $q = $this->createQuery('a');
        $q->leftJoin('a.tbl_licence l')
          ->andWhere('l.id_club = ?', $nIdClub)
          ->andWhere('a.is_used = ?', false);

        return $q->execute();
    }
    public function findAvoirClub($nIdClub)
    {
        $q = $this->createQuery('a');
        $q->andWhere('a.id_club = ?', $nIdClub)
          ->andWhere('a.is_used = ?', false);

        return $q->execute();
    }
}
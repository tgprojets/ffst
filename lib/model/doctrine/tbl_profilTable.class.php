<?php

/**
 * tbl_profilTable
 *
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class tbl_profilTable extends Doctrine_Table
{
    /**
     * Returns an instance of this class.
     *
     * @return object tbl_profilTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('tbl_profil');
    }
    public function findByKeyword($keyword)
    {
        $sName = mb_strtoupper($keyword);
        $q = $this->createQuery('p')
              ->where('upper(p.first_name) LIKE ?', $sName.'%')
              ->orWhere('upper(p.last_name) LIKE ?', $sName.'%')
              ->limit(0, 20);
              ;

        return $q->execute();
    }

    public function checkTransfert($nIdProfil, $nIdLigue)
    {
        $q = Doctrine_Query::create()
          ->select('MAX(l.date_validation) AS Date, l.id, c.id, li.id as ligue')
          ->from('tbl_licence l')
          ->leftJoin('l.tbl_profil p')
          ->leftJoin('l.tbl_club c')
          ->leftJoin('c.tbl_ligue li')
          ->andWhere('p.id = ?', $nIdProfil)
          ->groupBy('l.id, l.id_club, c.id, li.id');

        $result = $q->fetchArray();
        if ($result[0]['ligue'] == $nIdLigue) {
          return true;
        }

        return false;
    }
}
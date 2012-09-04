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
        return $q->execute();
    }
}
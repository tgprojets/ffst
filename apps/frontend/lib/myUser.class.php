<?php

class myUser extends sfGuardSecurityUser
{
    public function isFirstRequest($boolean = null)
    {
      if (is_null($boolean))
      {
        return $this->getAttribute('first_request', true);
      }

      $this->setAttribute('first_request', $boolean);
    }
    public function isClub()
    {
        if ($this->getCredentials('account_club'))
        {
            $oSfGuardUser = $this->getGuardUser();
            $oClub = Doctrine::getTable('tbl_club')->findOneBy('id_user', $oSfGuardUser->getId());
            if ($oClub)
            {
                return true;
            }
            return false;
        }
        return false;
    }
    public function getClub()
    {
        if ($this->getCredentials('account_club'))
        {
            $oSfGuardUser = $this->getGuardUser();
            $oClub = Doctrine::getTable('tbl_club')->findOneBy('id_user', $oSfGuardUser->getId());
            if ($oClub)
            {
                return $oClub;
            }
            return null;
        }
        return null;
    }

    public function isLigue()
    {
        if ($this->getCredentials('account_ligue'))
        {
            $oSfGuardUser = $this->getGuardUser();
            $oLigue = Doctrine::getTable('tbl_ligue')->findOneBy('id_user', $oSfGuardUser->getId());
            if ($oLigue)
            {
                return true;
            }
            return false;
        }
        return false;
    }
    public function getLigue()
    {
        if ($this->getCredentials('account_ligue'))
        {
            $oSfGuardUser = $this->getGuardUser();
            $oLigue = Doctrine::getTable('tbl_ligue')->findOneBy('id_user', $oSfGuardUser->getId());
            if ($oLigue)
            {
                return $oLigue;
            }
            return null;
        }
        return null;
    }
}


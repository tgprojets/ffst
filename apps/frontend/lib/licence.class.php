<?php

class Licence {
  public static function getDateLicence()
  {
    if (date('d') >= 1 && date('m') >= 7) {
      $startDate = date('Y');
      $endDate   = date('Y')+1;
    } else {
      $startDate = date('Y')-1;
      $endDate   = date('Y');
    }
    $sDate = (string) $startDate.'/'.(string) $endDate;

    return $sDate;
  }

  public static function getDateMajor($sType, $sDate)
  {
    $oInitParam = new InitParam();
    $sValue = $oInitParam->getValueByKey('Date validation', 'date_'.$sType.'_'.$sDate);
    if ($sValue)
    {
      return $sValue;
    } else {
      return sfConfig::get('app_majordate_'.$sType.'_'.$sDate);
    }
    return null;
  }

  public static function getStartYearLicence()
  {
    if ((int) date('m') >= 7 && (int) date('d') >= 1) {
      return date('Y');
    } else {
      return date('Y', strtotime('-1 year'));
    }

  }

  public static function getEndYearLicence()
  {
    if ((int) date('m') >= 7 && (int) date('d') >= 1) {
      return date('Y', strtotime('+1 year'));
    } else {
      return date('Y');
    }
  }
}
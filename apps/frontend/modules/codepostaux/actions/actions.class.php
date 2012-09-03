<?php

require_once dirname(__FILE__).'/../lib/codepostauxGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/codepostauxGeneratorHelper.class.php';

/**
 * codepostaux actions.
 *
 * @package    sf_sandbox
 * @subpackage codepostaux
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class codepostauxActions extends autoCodepostauxActions
{
  /**
   * Récupère la liste des villes en ajax
   *
   * @param sfWebRequest $request
   * @return Json return Json array of matching City objects converted to string
   */
  public function executeGetCitys(sfWebRequest $request)
  {
    $keyword = $request->getParameter('q');

    $limit = $request->getParameter('limit');
    if (strlen($keyword) <= 2) {
      return $this->renderText(json_encode(array()));
    }
    $citys = Doctrine::getTable('tbl_codepostaux')->findByKeyword($keyword);
    $list = array();
    foreach($citys as $city)
    {
      $list[$city->getId()] = sprintf('%s (%s)', $city->getVille(), $city->getCodePostaux());
    }
    return $this->renderText(json_encode($list));
  }
}

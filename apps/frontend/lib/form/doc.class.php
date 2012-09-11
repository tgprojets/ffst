<?php

class DocForm extends sfForm {

    public function setup()
    {
        $this->widgetSchema['doc'] = new sfWidgetFormTextarea(array(),array('rows'=>20,'cols'=>80));
        $this->validatorSchema['doc'] = new sfValidatorString(array(
            'required'   => true,
        ));

        $sContenu = file_get_contents(sfConfig::get('sf_upload_dir').'/contenu.txt');

        $this->setDefault('doc', $sContenu);

        $this->widgetSchema->setNameFormat('doc[%s]');

        $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

        $this->widgetSchema->setFormFormatterName('list');

    }
}

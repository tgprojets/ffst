<?php

class AideForm extends sfForm {

    public function setup()
    {
        $this->widgetSchema['aide_pdf'] = new sfWidgetFormInputFile();
        $this->validatorSchema['aide_pdf'] = new sfValidatorFile(array(
            'required'   => true,
            'mime_types' => array('application/pdf'),
            'path'       => sfConfig::get('sf_upload_dir'),
        ));
        $sContenu = file_put_contents(sfConfig::get('sf_upload_dir').'/contenu.txt');
        $this->setDefault($sContenu);
        $this->widgetSchema->setNameFormat('aide[%s]');

        $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

        $this->widgetSchema->setFormFormatterName('list');

    }
}
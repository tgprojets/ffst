<td>
  <ul class="sf_admin_td_actions">
    <?php if (!$sf_user->isLigue() && $tbl_licence->getIsCancel() == false): ?>
      <?php if ($tbl_licence->getTblProfil()->getImage() != null && $tbl_licence->getDateValidation() != null && $sf_user->hasCredential(array(0 => array(0 => 'account_club', 1 => 'licence')))): ?>
        <li>
            <?php echo link_to('Imprimer', 'licence/ListImprimer?id='.$tbl_licence->getId(), array('class' => 'print_icon')) ?>
        </li>
      <?php endif; ?>
      <?php if ($sf_user->hasCredential(array(0 => array(0 => 'account_club', 1 => 'licence')))): ?>
        <?php echo $helper->linkToEdit($tbl_licence, array(  'params' =>   array(  ),  'class_suffix' => 'edit',  'label' => 'Edit',)) ?>
      <?php endif; ?>
        <li>
            <?php if ($sf_user->hasCredential(array(  0 => 'ValidLicence',)) && $tbl_licence->getDateValidation() == null && $tbl_licence->getIsBrouillon() == false): ?>
                <?php echo link_to(__('Valider', array(), 'messages'), 'licence/ListValidLicence?id='.$tbl_licence->getId(), array('class' => 'valide_icon', 'confirm' => 'Etes vous sur de valider la licence ?')) ?>
            <?php endif; ?>
        </li>
      <?php if ($sf_user->hasCredential(array(  0 => 'ValidLicence',)) && $tbl_licence->getIsBrouillon() == false): ?>
        <li>
          <?php echo link_to(__('Annuler', array(), 'messages'), 'licence/ListCancelLicence?id='.$tbl_licence->getId(), array('class' => 'cancel_icon', 'confirm' => 'Etes vous sur d\'annuler cette licence ?')) ?>
        </li>
      <?php endif; ?>
      <?php if ($sf_user->hasCredential(array(  0 => 'admin',))): ?>
          <?php //echo $helper->linkToDelete($tbl_licence, array(  'credentials' =>   array(    0 => 'admin',  ),  'params' =>   array(  ),  'confirm' => 'Are you sure?',  'class_suffix' => 'delete',  'label' => 'Delete',)) ?>
      <?php endif; ?>
    <?php endif; ?>
      <li>
        <?php echo link_to('Voir', 'licence/ListShow?id='.$tbl_licence->getId(), array('class' => 'zoom_icon')) ?>
      </li>
  </ul>
</td>

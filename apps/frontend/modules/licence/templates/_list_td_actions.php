<td>
  <?php if (!$sf_user->isLigue()): ?>
      <ul class="sf_admin_td_actions">
        <?php if ($tbl_licence->getDateValidation() != null): ?>
            <li>
                <?php echo link_to('Imprimer', 'licence/ListImprimer?id='.$tbl_licence->getId(), array()) ?>
            </li>
        <?php endif; ?>
        <?php if ($tbl_licence->getIsBrouillon() == false): ?>
            <?php echo $helper->linkToEdit($tbl_licence, array(  'params' =>   array(  ),  'class_suffix' => 'edit',  'label' => 'Edit',)) ?>
        <?php endif; ?>
        <li>
            <?php if ($sf_user->hasCredential(array(  0 => 'ValidLicence',)) && $tbl_licence->getDateValidation() == null && $tbl_licence->getIsBrouillon() == false): ?>
                <?php echo link_to(__('Valider', array(), 'messages'), 'licence/ListValidLicence?id='.$tbl_licence->getId(), array('confirm' => 'Etes vous sur de valider la licence ?')) ?>
            <?php endif; ?>
        </li>
        <?php if ($sf_user->hasCredential(array(  0 => 'admin',))): ?>
            <?php //echo $helper->linkToDelete($tbl_licence, array(  'credentials' =>   array(    0 => 'admin',  ),  'params' =>   array(  ),  'confirm' => 'Are you sure?',  'class_suffix' => 'delete',  'label' => 'Delete',)) ?>
        <?php endif; ?>
      </ul>
  <?php endif; ?>
</td>

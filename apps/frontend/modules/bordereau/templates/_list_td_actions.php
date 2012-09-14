<td>
  <ul class="sf_admin_td_actions">
    <li class="sf_admin_action_payed">
      <?php if ($sf_user->hasCredential(array(  0 => 'ValidLicence',)) && $tbl_bordereau->getIsPayed() == false): ?>
<?php echo link_to(__('Réception Paiement', array(), 'messages'), 'bordereau/ListPayed?id='.$tbl_bordereau->getId(), array(  'confirm' => 'Etes vous sur que le bordereau est réglé ?',)) ?>
<?php endif; ?>

    </li>
    <li class="sf_admin_action_show">
      <?php echo link_to(__('Voir', array(), 'messages'), 'bordereau/ListShow?id='.$tbl_bordereau->getId(), array(  'class' => 'zoom_icon',)) ?>
    </li>
  </ul>
</td>

<table>
<?php foreach($oLicences as $oLicence): ?>
    <tr>
        <td><?php echo $oLicence->getNum() ?></td>
        <td><?php echo $oLicence->getTblProfil() ?></td>
    </tr>
<?php endforeach; ?>
</table>
<ul>
  <li>
    <?php echo link_to('Retour à la liste', '@tbl_licence', array()) ?>
  </li>
  <li class="sf_admin_action_cancel_saisie">
    <?php echo link_to(__('Annuler la saisie', array(), 'messages'), 'licence/ListCancelSaisie', array()) ?>
  </li>
  <li class="sf_admin_action_valid_saisie">
    <?php echo link_to(__('Valider la saisie', array(), 'messages'), 'licence/ListValidSaisie', array()) ?>
  </li>
</ul>
<?php use_stylesheet('admin.css') ?>
<div id="sf_admin_container" style="color:#fff;">
    <h1><?php echo $oBordereau->getLib() ?></h1>
    <div class="sf_admin_content">
        <fieldset id="sf_fieldset_none">
            <h2>Profil</h2>
            <div class="sf_admin_form_row">
                <label for="">Numéro de bordereau</label>
                <div class="text_show"><?php echo $oBordereau->getNum() ?></div>
            </div>
            <div class="sf_admin_form_row">
                <label for="">Montant</label>
                <div class="text_show"><?php echo $oBordereau->getAmount() ?> €</div>
            </div>
            <div class="sf_admin_form_row">
                <label for="">Club</label>
                <div class="text_show"><?php echo $oBordereau->getTblClub() ?></div>
            </div>
        </fieldset>

    </div>
    <div class="sf_admin_list">
        <table cellspacing="0">
            <thead>
            <tr>
              <th class="sf_admin_text">Intitulé</th>
              <th class="sf_admin_text">Montant</th>
              <th class="sf_admin_text">Avoir</th>
            </tr>
            </thead>
            <?php foreach($oPayments as $oPayment): ?>
                <tr class="sf_admin_row">
                    <td class="sf_admin_text">
                        <?php echo $oPayment->getLib() ?>
                    </td>
                    <td class="sf_admin_text">
                        <?php echo $oPayment->getAmount() ?> €
                    </td>
                    <td class="sf_admin_text">
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php foreach($oAvoirs as $oAvoir): ?>
                <tr class="sf_admin_row">
                    <td class="sf_admin_text">
                        <?php echo $oAvoir->getLib() ?>
                    </td>
                    <td class="sf_admin_text">
                        <?php echo $oAvoir->getAmount() ?> €
                    </td>
                    <td class="sf_admin_text">
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
    <div class="sf_admin_form">
        <ul class="sf_admin_actions">
          <li class="sf_admin_action_list sb_bouton_a">
          <?php echo link_to('Retour à la liste', '@tbl_bordereau', array('class' => 'icone_back noname_button')) ?>
          </li>
        </ul>
    </div>
</div>

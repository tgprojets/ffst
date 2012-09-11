<?php use_stylesheet('admin.css') ?>
<div id="sf_admin_container" style="color:#fff;">
    <form action="/frontend_dev.php/bordereau/ListPayment" method="post">
    <div class="sf_admin_list">
        <?php if ($sf_user->hasFlash('notice')): ?>
          <div class="notice">
            <?php echo $sf_user->getFlash('notice') ?>
          </div>
        <?php endif; ?>
            <script type="text/javascript">
            /* <![CDATA[ */
            function checkAll()
            {
              var boxes = document.getElementsByTagName('input'); for(var index = 0; index < boxes.length; index++) { box = boxes[index]; if (box.type == 'checkbox' && box.className == 'sf_admin_batch_checkbox') box.checked = document.getElementById('sf_admin_list_batch_checkbox').checked } return true;
            }
            /* ]]> */
            </script>
            <table cellspacing="0">
                <thead>
                <tr>
                  <th class="sf_admin_text"><input id="sf_admin_list_batch_checkbox" type="checkbox" onclick="checkAll();"></th>
                  <th class="sf_admin_text">Lib</th>
                  <th class="sf_admin_text">Montant</th>
                  <th class="sf_admin_text">Bordereau</th>
                  <th class="sf_admin_text">Type de paiement</th>
                </tr>
                </thead>
                <?php foreach($oPayments as $oPayment): ?>
                    <tr class="sf_admin_row">
                        <td class="sf_admin_text">
                            <input type="checkbox" name="ids_payment[]" value="<?php echo $oPayment->getId() ?>" class="sf_admin_batch_checkbox">
                        </td>
                        <td class="sf_admin_text">
                            <?php echo $oPayment->getLib() ?>
                        </td>
                        <td class="sf_admin_text">
                            <?php echo $oPayment->getAmount() ?> €
                        </td>
                        <td class="sf_admin_text">
                            <?php echo $oPayment->getTblBordereau()->getNum() ?>
                        </td>
                        <td class="sf_admin_text">
                            <?php echo $oPayment->getTblBordereau()?$oPayment->getTblBordereau()->getTblTypepayment():'' ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
            <?php if ($oAvoirs->count() > 0): ?>
            <script type="text/javascript">
            /* <![CDATA[ */
            function checkAllAvoir()
            {
              var boxes = document.getElementsByTagName('input'); for(var index = 0; index < boxes.length; index++) { box = boxes[index]; if (box.type == 'checkbox' && box.className == 'sf_admin_batch_checkbox_avoir') box.checked = document.getElementById('sf_admin_list_batch_checkbox_avoir').checked } return true;
            }
            /* ]]> */
            </script>
            <table cellspacing="0">
                <thead>
                <tr>
                  <th class="sf_admin_text"><input id="sf_admin_list_batch_checkbox_avoir" type="checkbox" onclick="checkAllAvoir();"></th>
                  <th class="sf_admin_text">Lib</th>
                  <th class="sf_admin_text">Avoir</th>
                  <th class="sf_admin_text">Bordereau</th>
                </tr>
                </thead>
                <?php foreach($oAvoirs as $oAvoir): ?>
                    <tr class="sf_admin_row">
                        <td class="sf_admin_text">
                            <input type="checkbox" name="ids_avoir[]" value="<?php echo $oAvoir->getId() ?>" class="sf_admin_batch_checkbox_avoir">
                        </td>
                        <td class="sf_admin_text">
                            <?php echo $oAvoir->getLib() ?>
                        </td>
                        <td class="sf_admin_text">
                            <?php echo $oAvoir->getAmount() ?> €
                        </td>
                        <td class="sf_admin_text">
                            <?php echo $oPayment->getTblBordereau()->getNum() ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
            <?php endif; ?>
    </div>
    <div class="sf_admin_form">
        <ul class="sf_admin_actions">
          <li class="sf_admin_action_list sb_bouton_a">
          <?php echo link_to('Retour à la liste', '@tbl_bordereau', array('class' => 'icone_back noname_button')) ?>
          <input type="submit" value="Enregistrer bordereau" />
          </li>
        </ul>
    </div>
    </form>
</div>

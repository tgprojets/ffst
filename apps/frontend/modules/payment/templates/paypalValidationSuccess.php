<?php use_stylesheet('paypal.css') ?>
<div class="paypal_retour">
    <h2>Paiement PAYPAL validé</h2>
    <div class="message_paypal_success">
        Vous pouvez imprimer les licences que vous venez de payer en retournant à la liste des licences.
    </div>
</div>
<div>
    <div class="sb_bouton_a">
        <?php echo link_to('Retour à la liste', '@tbl_licence') ?>
    </div>
</div>

<form action="<?php echo url_for('main/testSendMail') ?>" method="POST" name="tgprojets_devis" enctype="multipart/form-data">
    <div><label>Mail from:</label><input type="text" name="mailfrom" value="contact@tgprojets.fr"/></div>
    <div><label>Mail to:</label><input type="text" name="mailto" value=""/></div>
    <div><input type="submit" value="envoyer"/>
</form>

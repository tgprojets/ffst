<?php use_stylesheet('admin.css') ?>
<div id="sf_admin_container" style="color:#fff;">
    <h1><?php echo $oProfil->getName() ?></h1>
    <div class="sf_admin_content">
        <fieldset id="sf_fieldset_none">
            <h2>Profil</h2>
            <div class="sf_admin_form_row">
                <label for="">Sexe</label>
                <div class="text_show"><?php echo $oProfil->getSexe() ?></div>
            </div>
            <div class="sf_admin_form_row">
                <label for="">Email</label>
                <div class="text_show"><?php echo $oProfil->getEmail() ?></div>
            </div>
            <div class="sf_admin_form_row">
                <label for="">Date anniversaire</label>
                <div class="text_show"><?php echo $oProfil->getBirthday() ?></div>
            </div>
            <div class="sf_admin_form_row">
                <label for="">Email</label>
                <div class="text_show"><?php echo $oProfil->getEmail() ?></div>
            </div>
        </fieldset>
        <fieldset id="sf_fieldset_none">
            <h2>Adresse et coordonnées</h2>
            <div class="sf_admin_form_row">
                <label for="">Ville</label>
                <div class="text_show"><?php echo $oAddress->getTblCodepostaux() ?></div>
            </div>
            <div class="sf_admin_form_row">
                <label for="">Localisation</label>
                <div class="text_show">
                    <?php echo $oAddress->getAddress1() ?>
                    <?php if ($oAddress->getAddress2() != ''): ?>
                        <?php echo $oAddress->getAddress2() ?>
                    <?php endif; ?>
                </div>
            </div>
            <div class="sf_admin_form_row">
                <label for="">Tel</label>
                <div class="text_show"><?php echo $oAddress->getTel() ?></div>
            </div>
            <div class="sf_admin_form_row">
                <label for="">Portable</label>
                <div class="text_show"><?php echo $oAddress->getGsm() ?></div>
            </div>
            <div class="sf_admin_form_row">
                <label for="">Fax</label>
                <div class="text_show"><?php echo $oAddress->getFax() ?></div>
            </div>
        </fieldset>
        <fieldset id="sf_fieldset_none">
            <h2>Licence</h2>
            <div class="sf_admin_form_row">
                <label for="">Club</label>
                <div class="text_show"><?php echo $oLicence->getTblClub() ?></div>
            </div>
            <div class="sf_admin_form_row">
                <label for="">Type de licence</label>
                <div class="text_show">
                    <?php echo $oLicence->getTblTypelicence() ?>
                </div>
            </div>
            <div class="sf_admin_form_row">
                <label for="">Catégorie</label>
                <div class="text_show"><?php echo $oLicence->getTblCategory()->getLib() ?></div>
            </div>
            <?php if ($oLicence->getInternational()): ?>
            <div class="sf_admin_form_row">
                <div class="text_show">Licence international</div>
            </div>
            <?php endif; ?>
            <?php if ($oLicence->getRaceNordique()): ?>
            <div class="sf_admin_form_row">
                <div class="text_show">Race nordique</div>
            </div>
            <?php endif; ?>
            <?php if ($oLicence->getIdFamilly()): ?>
            <div class="sf_admin_form_row">
                <label for="">Tarif réduit tuteur</label>
                <div class="text_show">
                    <?php echo $oFamilly->getName() ?>
                </div>
            </div>
            <?php endif; ?>
            <?php if ($oLicence->getCnil()): ?>
            <div class="sf_admin_form_row">
                <div class="text_show">Cnil</div>
            </div>
            <?php endif; ?>
            <?php if ($oLicence->getDateMedical() != null): ?>
            <div class="sf_admin_form_row">
                <label for="">Date du certificat</label>
                <div class="text_show"><?php echo $oLicence->getDateMedical() ?></div>
            </div>
            <?php endif; ?>
        </fieldset>

    </div>
    <div class="sf_admin_form">
        <ul class="sf_admin_actions">
          <li class="sf_admin_action_list sb_bouton_a">
          <?php echo link_to('Retour à la liste', '@tbl_licence', array('class' => 'icone_back noname_button')) ?>
          </li>
        </ul>
    </div>
</div>

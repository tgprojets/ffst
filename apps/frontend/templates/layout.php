<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
    <?php include_http_metas() ?>
    <?php include_metas() ?>
    <?php include_title() ?>
    <link rel="shortcut icon" href="/favicon.ico" />
    <?php include_stylesheets() ?>
    <?php include_javascripts() ?>
  </head>
  <body>
    <div id="conteneur">
      <div class="sb_bandeau">
          <div class="sb_logo">
            <?php $sLogo = $sf_user->getLogo() ?>
            <?php if ($sLogo != null): ?>
              <img src="<?php echo $sLogo ?>" alt="" />
            <?php else: ?>
              <img src="/images/logo.png" alt="" />
            <?php endif; ?>
          </div>
          <div class="sb_titre">
              <?php if ($sf_user->isAuthenticated()): ?>
              <div class="sb_bar_menu_connexion">
                  <div class="sb_deconnexion">
                    <?php echo 'Bonjour '.$sf_user->getGuardUser()->getFirstName().' '.$sf_user->getGuardUser()->getLastName() ?>
                  </div>
                  <?php echo link_to('&nbsp;', '@sf_guard_signout', array('class' => 'sb_deconnexion_btn')) ?>
                  <?php if (isset($gsMessage)): ?>
                    <div class="sb_notice"><?php echo $gsMessage ?></div>
                  <?php endif; ?>
              </div>
              <?php endif; ?>
            <h2>
              <?php echo $sf_user->getTitre() ?>
            </h2>
            <h1>GESTION DES LICENCES</h1>
          </div>
          <?php if ($sf_user->isAuthenticated()): ?>
            <div id="ffst_nav">
              <ul>
                  <li <?php echo $sf_params->get('module')=="main"?'class="sb_menu_select"':''?>><?php echo link_to('Accueil', '@homepage') ?></li>

                  <?php if ($sf_user->hasCredential('admin')): ?>
                  <li> <a href="#">Gestion utilisateur</a>
                    <ul>
                      <li <?php echo $sf_params->get('module')=="sfGuardUser"?'class="sb_menu_select"':''?>><?php echo link_to('Gestion utilisateur', '@sf_guard_user') ?></li>
                      <li <?php echo $sf_params->get('module')=="sfGuardGroup"?'class="sb_menu_select"':''?>><?php echo link_to('Gestion des groupes', '@sf_guard_group') ?></li>
                      <li <?php echo $sf_params->get('module')=="sfGuardPermission"?'class="sb_menu_select"':''?>><?php echo link_to('Gestion des permissions', '@sf_guard_permission') ?></li>
                    </ul>
                  </li>
                  <?php endif; ?>

                  <?php if ($sf_user->hasCredential('admin') || $sf_user->hasCredential('ligue') || $sf_user->hasCredential('club')): ?>
                    <li> <a href="#">Affiliation</a>
                      <ul>
                  <?php endif; ?>
                  <?php if ($sf_user->hasCredential('admin') || $sf_user->hasCredential('ligue')): ?>
                    <li <?php echo $sf_params->get('module')=="ligue"?'class="sb_menu_select"':''?>><?php echo link_to('Gestion Ligues', '@tbl_ligue') ?></li>
                  <?php endif; ?>
                  <?php if ($sf_user->hasCredential('admin') || $sf_user->hasCredential('club')): ?>
                    <li <?php echo $sf_params->get('module')=="club"?'class="sb_menu_select"':''?>><?php echo link_to('Gestion Clubs', '@tbl_club') ?></li>
                  <?php endif; ?>
                  <?php if ($sf_user->hasCredential('admin') || $sf_user->hasCredential('ligue') || $sf_user->hasCredential('club')): ?>
                      </ul>
                    </li>
                  <?php endif; ?>


                  <?php if ($sf_user->hasCredential('prixunit')): ?>
                    <li> <a href="#">Prix</a>
                      <ul>
                        <li <?php echo $sf_params->get('module')=="typelicence"?'class="sb_menu_select"':''?>><?php echo link_to('Type de licence', '@tbl_typelicence') ?></li>
                        <li <?php echo $sf_params->get('module')=="prix"?'class="sb_menu_select"':''?>><?php echo link_to('Article', '@tbl_prixunit') ?></li>
                      </ul>
                    </li>
                  <?php endif; ?>

                  <?php if ($sf_user->hasCredential('ValidLicence')): ?>
                    <li> <a href="#">Règlements</a>
                      <ul>
                        <li <?php echo $sf_params->get('module')=="payment"?'class="sb_menu_select"':''?>><?php echo link_to('Paiement', '@tbl_payment') ?></li>
                        <li <?php echo $sf_params->get('module')=="prix"?'class="sb_menu_select"':''?>><?php echo link_to('Avoir', '@tbl_avoir') ?></li>
                      </ul>
                    </li>
                  <?php endif; ?>
                  <?php if ($sf_user->hasCredential('licence') || $sf_user->hasCredential('account_club') || $sf_user->hasCredential('account_ligue')): ?>
                  <li> <a href="#">Gestion des licences</a>
                    <ul>
                      <li <?php echo $sf_params->get('module')=="licence"?'class="sb_menu_select"':''?>><?php echo link_to('Licence', '@tbl_licence') ?></li>
                      <li <?php echo $sf_params->get('module')=="licenceold"?'class="sb_menu_select"':''?>><?php echo link_to('Ancienne licence', 'licenceold/index') ?></li>
                    </ul>
                  </li>
                  <?php endif; ?>
                  <?php if ($sf_user->hasCredential('admin') || $sf_user->hasCredential('categorie')): ?>
                  <li> <a href="#">Divers</a>
                    <ul>
                      <li <?php echo $sf_params->get('module')=="category"?'class="sb_menu_select"':''?>><?php echo link_to('Catégorie', '@tbl_category') ?></li>
                      <?php if ($sf_user->hasCredential('admin')): ?>
                        <li <?php echo $sf_params->get('module')=="codepostaux"?'class="sb_menu_select"':''?>><?php echo link_to('Code postaux', '@tbl_codepostaux') ?></li>
                      <?php endif; ?>
                      <?php if ($sf_user->hasPermission('connexion_history')): ?>
                        <li <?php echo $sf_params->get('module')=="tracability"?'class="sb_menu_select"':''?>><?php echo link_to('Connexion historique', '@tbl_tracability') ?></li>
                      <?php endif; ?>
                      <?php if ($sf_user->hasCredential('admin')): ?>
                      <li <?php echo $sf_params->get('module')=="params"?'class="sb_menu_select"':''?>><?php echo link_to('Date majoration', 'params/majorDate') ?></li>
                      <?php endif; ?>
                    </ul>
                  </li>
                  <?php endif; ?>
              </ul>
            </div>
          <?php endif; ?>
      </div>
      <?php if ($sf_user->isAuthenticated()): ?>
      <div class="sb_bar">
          <div id="margepied"><!-- ne pas enlever cette marge et laisser en dernier --></div>
      </div>
      <div id="contenant">
               <?php echo $sf_content ?>
        <div id="margepied"><!-- ne pas enlever cette marge et laisser en dernier --></div>
      </div>
      <?php else: ?>
      <div id="contenant_center">
               <?php echo $sf_content ?>
        <div id="margepied"><!-- ne pas enlever cette marge et laisser en dernier --></div>
      </div>
      <?php endif; ?>
    </div>
    <div class="sb_baspage">
      <div class="info_mail_footer">
        contact :  <a href="mailto:ffst@free.fr">ffst@free.fr</a>
        Sites internet : <a href="http://www.ffstraineau.com/">www.ffstraineau.com</a>
      </div>
      <?php if ($sf_user->isClub() || $sf_user->isLigue()): ?>
        <div class="info_footer_address">
          <?php $oInfo    = $sf_user->getIndoFooter() ?>
          <?php $oAddress = $oInfo->getTblAddress() ?>
          <div><label>Organisation : </label><?php echo $oInfo->getOrganisation() ?></div>
          <div><label>N° et rue : </label><?php echo $oAddress->getAddress1() ?></div>
          <div><label>Lieu dit : </label><?php echo $oAddress->getAddress2() ?></div>
          <div><label>CP : </label><?php echo $oAddress->getTblCodepostaux()->getCodePostaux() ?></div>
          <div><label>Ville : </label><?php echo $oAddress->getTblCodepostaux()->getVille() ?></div>
        </div>
      <?php endif; ?>
    </div>
  </body>

</html>

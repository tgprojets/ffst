<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
    <?php include_http_metas() ?>
    <?php include_metas() ?>
    <?php include_title() ?>
    <link rel="shortcut icon" href="/favicon.ico" />
    <?php include_stylesheets() ?>
    <link rel="stylesheet" type="text/css" href="/css/print.css" media="print" />
    <?php include_javascripts() ?>
  </head>
  <body>
    <div id="conteneur">
      <?php include_component('main', 'CheckNavigateur') ?>
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
                    <br />
                    <?php echo link_to('Se déconnecter', '@sf_guard_signout', array('class' => 'sb_deconnexion_btn')) ?>
                  </div>
                  <?php if (isset($gsMessage)): ?>
                    <div class="sb_notice"><?php echo $gsMessage ?></div>
                  <?php endif; ?>
              </div>
              <?php endif; ?>
            <h2>
              <?php echo $sf_user->getTitre() ?>
            </h2>
            <h1>GESTION DES LICENCES</h1>
            <?php if (sfConfig::get('sf_environment') == 'sandbox'): ?>
              <h3>
                ATTENTION CECI EST L'ENVIRONNENT SANDBOX
              </h3>
            <?php endif; ?>
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
                        <li <?php echo $sf_params->get('module')=="bordereau"?'class="sb_menu_select"':''?>><?php echo link_to('Liste des bordereaux', '@tbl_bordereau') ?></li>
                      </ul>
                    </li>
                  <?php endif; ?>
                  <?php if ($sf_user->hasCredential('visuel') || $sf_user->hasCredential('stats') || $sf_user->hasCredential('licence') || $sf_user->hasCredential('account_club') || $sf_user->hasCredential('account_ligue')): ?>
                  <li> <a href="#">Gestion des licences</a>
                    <ul>
                      <li <?php echo $sf_params->get('module')=="licence"?'class="sb_menu_select"':''?>><?php echo link_to('Licence', '@tbl_licence') ?></li>
                      <li <?php echo $sf_params->get('module')=="licenceold"?'class="sb_menu_select"':''?>><?php echo link_to('Ancienne licence', 'licenceold/index') ?></li>
                    </ul>
                  </li>
                  <?php endif; ?>
                  <?php if ($sf_user->isClub() && $sf_user->hasCredential('account_club')): ?>
                  <li> <a href="#">Bordereaux</a>
                    <ul>
                      <li <?php echo $sf_params->get('module')=="bordereau"?'class="sb_menu_select"':''?>><?php echo link_to('Liste des bordereaux', '@tbl_bordereau') ?></li>
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
                      <li <?php echo $sf_params->get('module')=="main"?'class="sb_menu_select"':''?>><?php echo link_to('Modifier fichier Aide', 'main/aideForm') ?></li>
                      <li <?php echo $sf_params->get('module')=="main"?'class="sb_menu_select"':''?>><?php echo link_to('Modifier documents', 'main/docForm') ?></li>
                      <li <?php echo $sf_params->get('module')=="main"?'class="sb_menu_select"':''?>><?php echo link_to('Gestion saison', 'main/saison') ?></li>
                      <?php endif; ?>
                    </ul>
                  </li>
                  <?php endif; ?>
                  <li>
                    <?php echo link_to('Documents', 'main/doc') ?>
                  </li>
                  <li>
                    <a href="/uploads/aide.pdf">Aide</a>
                  </li>
              </ul>
            </div>
          <?php endif; ?>
      </div>
      <div id="contenant">
               <?php echo $sf_content ?>
        <div id="margepied"><!-- ne pas enlever cette marge et laisser en dernier --></div>
      </div>
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
          <div><?php echo $oInfo->getName() ?></div>
          <div><?php echo $oAddress->getAddress1() ?></div>
          <div><?php echo $oAddress->getAddress2() ?></div>
          <div><?php echo $oAddress->getTblCodepostaux()->getCodePostaux().' '.$oAddress->getTblCodepostaux()->getVille() ?></div>
        </div>
      <?php endif; ?>
    </div>
  </body>

</html>

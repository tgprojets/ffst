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
            <img src="/images/logo.png" alt="" />
          </div>
          <div class="sb_titre">
            <h1>FFST</h1>
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

              <div class="sb_bar_menu_connexion">
              </div>
            </div>
      </div>
      <div class="sb_bar">
          <?php if ($sf_user->isAuthenticated()): ?>
            <ul class="sb_menu_admin">
                <li <?php echo $sf_params->get('module')=="main"?'class="sb_menu_select"':''?>><?php echo link_to('Accueil', '@homepage') ?></li>
                <?php if ($sf_user->hasCredential('admin')): ?>
                <li> <h3>Gestion utilisateur</h3> </li>
                  <ul class="sb_ssmenu_admin">
                    <li <?php echo $sf_params->get('module')=="sfGuardUser"?'class="sb_menu_select"':''?>><?php echo link_to('Gestion utilisateur', '@sf_guard_user') ?></li>
                    <li <?php echo $sf_params->get('module')=="sfGuardGroup"?'class="sb_menu_select"':''?>><?php echo link_to('Gestion des groupes', '@sf_guard_group') ?></li>
                    <li <?php echo $sf_params->get('module')=="sfGuardPermission"?'class="sb_menu_select"':''?>><?php echo link_to('Gestion des permissions', '@sf_guard_permission') ?></li>
                  </ul>
                <?php endif; ?>
                <?php if ($sf_user->hasCredential('admin') || $sf_user->hasCredential('ligue') || $sf_user->hasCredential('club')): ?>
                  <li> <h3>Affiliation</h3> </li>
                <?php endif; ?>
                <?php if ($sf_user->hasCredential('admin') || $sf_user->hasCredential('ligue')): ?>
                <ul class="sb_ssmenu_admin">
                  <li <?php echo $sf_params->get('module')=="ligue"?'class="sb_menu_select"':''?>><?php echo link_to('Gestion Ligues', '@tbl_ligue') ?></li>
                </ul>
                <?php endif; ?>
                <?php if ($sf_user->hasCredential('admin') || $sf_user->hasCredential('club')): ?>
                <ul class="sb_ssmenu_admin">
                  <li <?php echo $sf_params->get('module')=="club"?'class="sb_menu_select"':''?>><?php echo link_to('Gestion Clubs', '@tbl_club') ?></li>
                </ul>
                <?php endif; ?>
                <?php if ($sf_user->hasPermission('connexion_history')): ?>
                  <li <?php echo $sf_params->get('module')=="tracability"?'class="sb_menu_select"':''?>><?php echo link_to('Connexion historique', '@tbl_tracability') ?></li>
                <?php endif; ?>
                <?php if ($sf_user->hasCredential('prixunit')): ?>
                  <li> <h3>Prix</h3> </li>
                  <ul class="sb_ssmenu_admin">
                    <li <?php echo $sf_params->get('module')=="typelicence"?'class="sb_menu_select"':''?>><?php echo link_to('Type de licence', '@tbl_typelicence') ?></li>
                    <li <?php echo $sf_params->get('module')=="prix"?'class="sb_menu_select"':''?>><?php echo link_to('Article', '@tbl_prixunit') ?></li>
                  </ul>
                <?php endif; ?>
                <?php if ($sf_user->hasCredential('admin') || $sf_user->hasCredential('categorie')): ?>
                <li> <h3>Divers</h3> </li>
                <ul class="sb_ssmenu_admin">
                  <li <?php echo $sf_params->get('module')=="category"?'class="sb_menu_select"':''?>><?php echo link_to('Catégorie', '@tbl_category') ?></li>
                </ul>
                <?php endif; ?>
            </ul>
          <?php endif; ?>
          <div id="margepied"><!-- ne pas enlever cette marge et laisser en dernier --></div>
      </div>
      <div id="contenant">
               <?php echo $sf_content ?>
        <div id="margepied"><!-- ne pas enlever cette marge et laisser en dernier --></div>
      </div>
    </div>
    <div class="sb_baspage">
      <?php //echo link_to('Copyright', 'main/copyright') ?> - Thomas GILBERT -
      <?php //echo link_to('Apropos de', 'main/aproposde') ?>
    </div>
  </body>

</html>

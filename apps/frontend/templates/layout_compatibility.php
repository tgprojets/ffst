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
    </div>
  </body>

</html>

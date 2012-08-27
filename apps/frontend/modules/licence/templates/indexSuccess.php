<?php use_helper('I18N', 'Date') ?>
<?php include_partial('licence/assets') ?>

<div id="sf_admin_container">
  <h1><?php echo __('Liste des licenciés', array(), 'messages') ?></h1>

  <?php include_partial('licence/flashes') ?>


  <div id="sf_admin_bar">
    <?php include_partial('licence/filters', array('form' => $filters, 'configuration' => $configuration)) ?>
  </div>
  <div id="sf_admin_header">
    <?php include_partial('licence/list_header', array('pager' => $pager)) ?>
  </div>

  <div id="sf_admin_content">
    <?php include_partial('licence/list', array('pager' => $pager, 'sort' => $sort, 'helper' => $helper)) ?>
    <ul class="sf_admin_actions">
      <?php include_partial('licence/list_batch_actions', array('helper' => $helper)) ?>
      <?php include_partial('licence/list_actions', array('helper' => $helper)) ?>
    </ul>
  </div>

  <div id="sf_admin_footer">
    <?php include_partial('licence/list_footer', array('pager' => $pager)) ?>
  </div>
</div>

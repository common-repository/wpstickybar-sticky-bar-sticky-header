  <?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://wpstickybar.com
 * @since      1.0.0
 *
 * @package    Wpstickybar
 * @subpackage Wpstickybar/admin/partials
 */
$settings_init_general = new Wpstickybar_Settings("wpstickybar");

$view = '';
if (isset($_GET['view'])) {
    $view = sanitize_key($_GET['view']);
}

$isAddNew = $view == 'addnew';
$bannerType = $view == 'addnew' ? 'new' : 'edit';
$defaultPromotionText = "50% OFF for all items";
$defaultButtonText = "Get it now →";
$defaultUrl = "https://example.com";
$activeCheckbox = $isAddNew ? true : false;

?>



  <div id="wpbody-content">

    <?php
      wp_reset_query();
      global $post;
      
      $banner_item = $settings_init_general->sql();

      global $wpdb;
      global $tableprefix;
      $tableprefix = $wpdb->prefix;
      $this->banner_item = $banner_item;

?>

      
    <div class="wrap">
      <div class="header-pulgin">
        <div>
          <h1 style="display: flex ;">
            <p style="display: block ; font-size: 27px;">Sticky Bar </p>
            <!-- <a class="row-title" href="<?php echo admin_url('/admin.php?page=wpstickybars&view=addnew'); ?>"><?php _e('Add New', 'WP Sticky Bar');?></a> -->
          </h1>
        </div>
      </div>
      <div id="wpstickybar-app">
        <noscript>You need to enable JavaScript to run this app.</noscript>
        <div id="stickybar-root"></div>
        <script>

          // Calculate admin bar's height
          var adminBarHeight = document.getElementById('wpadminbar').offsetHeight;

          window.wpReactGlobal = {
            bannerType: '<?php echo $bannerType ?>',
            redirectUrl: '<?php echo admin_url('admin.php?page=wpstickybars') ?>',
            siteUrl: '<?php echo site_url() ?>',
            adminBarHeight: adminBarHeight,
            <?php if (!$isAddNew) {
              echo 'bannerId: '. $_GET['id'];
            }
            ?>
          };
        </script>        
      </div>
      <br />

      
    </div>
  
  </div>




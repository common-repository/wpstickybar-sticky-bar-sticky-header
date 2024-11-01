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

?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<?php if (!did_action('wp_enqueue_media')) {
  wp_enqueue_media();
}
?>
<style>
  .delete-banner {
    /* display: block; */
    color: #000;
    /* background-color: #b6b6b6; */
    width: fit-content;
    padding: 8px 11px;
    text-decoration: none;
    /* border: 1px solid black; */
    border-radius: 5px;
  }

  .delete-banner:hover {
    color: #000;
  }

  .inactive-dot {
    height: 8px;
    width: 8px;
    background-color: #d63f00;
    border-radius: 50%;
    margin-right: 5px;
    display: inline-block;
  }

  .active-dot {
    height: 8px;
    width: 8px;
    background-color: #688c29;
    border-radius: 50%;
    margin-right: 5px;
    display: inline-block;
  }
</style>

<div id="wpbody-content">

  <div class="wrap">
    <div class="header-pulgin">
      <div>
        <h1 style="display: flex ;">
          <p style="display: block ; font-size: 15px;">Sticky Bar </p>
          <a class="row-title" href="<?php echo admin_url('/admin.php?page=wpstickybars&view=addnew'); ?>"><?php _e('Add New', 'WP Sticky Bar'); ?></a>
        </h1>
      </div>
    </div>
    <br />

    <form method="post" action="options.php">
      <div id="normal-sortables" class="meta-box-sortables ui-sortable">

        <?php
        global $wpdb;
        global $tableprefix;
        $tableprefix = $wpdb->prefix;
        $stickybar_banner = $tableprefix . 'stickybar_banner';
        $banner_list = $wpdb->get_results("SELECT * FROM " . $stickybar_banner . "");


        ?>

        <?php if (count($banner_list) == 0) : ?>
          <h3 style="text-align:center">
            No sticky bar founds. Please add a new one
          </h3>
          <div class="centered-div">
            <div class="gradient-btn btn-buy"><a href="<?php echo admin_url('/admin.php?page=wpstickybars&view=addnew'); ?>">ADD STICKY BAR</a>
              <div class="btn2"></div>
            </div>
          </div>
        <?php else : ?>
          
          <div id="itsec_get_started" class="postbox ">

            <table class="admin-listbanner">
              <thead class="head-listbanner">
                <tr>
                  <td class="id-banner">ID </td>
                  <td class="title-banner">Title</td>
                  <td width="15%" class="promotion-text-banner">Status</td>
                  <td width="15%"></td>
                </tr>
              </thead>
              <tbody class="body-listbanner">

                <?php foreach ($banner_list as $banner) {
                ?>
                  <tr class="banner<?php echo $banner->banner_id  ?>">
                    <td class="id-body"><?php echo $banner->banner_id ?></td>
                    <td class="title-banner-link">
                      <a href="admin.php?page=wpstickybars&view=edit&id=<?php echo $banner->banner_id ?>">
                        <?php echo $banner->title ?>
                      </a>
                      <span style="font-size:12px ; font-weight: 200 ;">
                        <?php echo $banner->promotion_text ?>
                      </span>
                    </td>
                    <td>
                      <?php
                      if ($banner->display_banner == 1) : ?>
                        <p><span class="active-dot"></span><span>Active</span></p>
                      <?php else : ?>
                        <p><span class="inactive-dot"></span><span>Inactive</span></p>
                      <?php endif; ?>
                    </td>
                    <td>
                      <a href="admin.php?page=wpstickybars&view=edit&id=<?php echo $banner->banner_id ?>" class="delete-banner">Edit</a>
                      |
                      <a href="#" class="delete-banner delete-banner<?php echo $banner->banner_id ?>" name="delete_banner">Delete</a>
                    </td>
                  </tr>
                  <script>
                    jQuery(document).ready(function($) {
                      // event click banner
                      $('.delete-banner<?php echo $banner->banner_id ?>').click(function() { // Khi click vào button thì sẽ gọi hàm ajax

                        if (confirm("Are you sure you want to delete this item?") == true) {
                          $.ajax({ // Hàm ajax
                            type: "post", //Phương thức truyền post hoặc get
                            dataType: "json", //Dạng dữ liệu trả về xml, json, script, or html
                            url: '<?php echo admin_url('admin-ajax.php'); ?>', // Nơi xử lý dữ liệu
                            data: {
                              action: "delete_banner", //Tên action, dữ liệu gởi lên cho server
                              banner_id: <?php echo $banner->banner_id ?>
                            },
                            success: function(textStatus) {
                              //Làm gì đó khi dữ liệu đã được xử lý
                              location.reload();
                            },
                            error: function(jqXHR, textStatus, errorThrown) {
                              //Làm gì đó khi có lỗi xảy ra
                              console.log('error');
                            }
                          });
                        } else {

                        }

                      });
                    })
                    var deleteBtn = document.querySelector('.delete-banner<?php echo $banner->banner_id ?>')
                    deleteBtn.addEventListener('click', () => {
                      document.querySelector('.banner<?php echo $banner->banner_id  ?>').style.display = 'none';
                    })
                  </script>
                <?php
                }
                ?>
              </tbody>
              <tfoot class="foot-listbanner">
                <tr>
                  <td class="id-banner">ID </td>
                  <td class="title-banner">Title</td>
                  <td class="promotion-text-banner">Status</td>
                  <td></td>
                </tr>
              </tfoot>

              <div class="clear"></div>

            </table>
          </div>
        <?php endif; ?>
    </form>

  </div>
  <div class="clear"></div>
</div>
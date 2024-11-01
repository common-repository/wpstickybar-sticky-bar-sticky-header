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
  $settings_init_general = new Wpstickybar_Settings( "wpstickybar") ;

  ?>

  <!-- This file should primarily consist of HTML with a little bit of PHP. -->

  <?php if (!did_action('wp_enqueue_media')) {
      wp_enqueue_media();
    }
    ?>
  <div id="wpbody-content">
    
    <style type="text/css">
      .tabcontent {
        /* padding: 6px 12px; */
        /* border: 1px solid #ccc; */
        border-top: none;
      }
      .form-table{
      display: flex;
      flex-wrap: wrap;
      }

      #statistics{
          width: 100%;
      }
    </style>
    <div class="wrap">
      <form method="post" action="">			
        <div id="normal-sortables" class="meta-box-sortables ui-sortable">
          <div id="itsec_get_started" class="postbox ">
            <div class="inside">          
                <div class="form-table">
          
                  <!-- statistics -->
                  <div id="statistics" class="tabcontent">
                    <h3 class="hndle"><span>Statistics All Time</span></h3>
                    <table class="statis-form">
                        <tr class="statis-title">
                            <td>Banner Title</td>
                            <td>Impression</td>
                            <td>Clicks</td>
                            <td>Conversion</td>
                        </tr>
                        <?php
                            global $wpdb;
                            global $tableprefix;
                            $tableprefix = $wpdb->prefix;
                            $stickybar_stats = $tableprefix . 'stickybar_stats';
                            $stickybar_banner = $tableprefix . 'stickybar_banner';
                            $rows = $wpdb->get_results("SELECT b.`title`, SUM(s.`impressions`) as `impressions`, SUM(s.`clicks`) as `clicks`
                                                        FROM ".$stickybar_stats." s
                                                        JOIN ".$stickybar_banner." b ON s.`banner_id` = b.`banner_id`
                                                        GROUP BY b.`title`");
                            foreach ($rows as $row) {
                                $conversion = $row->impressions > 0 ? ($row->clicks / $row->impressions) * 100 : 0;
                        ?>
                        <tr class="statis-content">
                            <td><a href="<?php echo admin_url('admin.php?page=wpstickybars&view=edit&id='.$row->banner_id) ?>"><?php echo $row->title ?></a></td>
                            <td><?php echo $row->impressions ?></td>
                            <td><?php echo $row->clicks ?></td>
                            <td><?php echo number_format($conversion, 2) ?>%</td>
                        </tr>
                        <?php
                            }
                        ?>
                    </table>
                  </div>

                  <div id="statistics" class="tabcontent">
                    <h3 class="hndle"><span>Statistics Last 7 Days</span></h3>
                    <table class="statis-form">
                        <tr class="statis-title">
                            <td>Banner Title</td>
                            <td>Date</td>
                            <td>Impression</td>
                            <td>Clicks</td>
                            <td>Conversion</td>
                        </tr>
                        <?php
                            global $wpdb;
                            global $tableprefix;
                            $tableprefix = $wpdb->prefix;
                            $stickybar_stats = $tableprefix . 'stickybar_stats';
                            $stickybar_banner = $tableprefix . 'stickybar_banner';
                            $start_date = date('Y-m-d', strtotime('-7 days')); // Get the start date (7 days ago)
                            $end_date = date('Y-m-d'); // Get the end date (today)
                            $start_timestamp = strtotime($start_date);
                            $end_timestamp = strtotime($end_date . ' 23:59:59');
                            $rows = $wpdb->get_results("SELECT b.`title`, s.`the_time`, SUM(s.`impressions`) as `impressions`, SUM(s.`clicks`) as `clicks`
                                                        FROM ".$stickybar_stats." s
                                                        JOIN ".$stickybar_banner." b ON s.`banner_id` = b.`banner_id`
                                                        WHERE s.`the_time` >= $start_timestamp AND s.`the_time` <= $end_timestamp
                                                        GROUP BY b.`title`, s.`the_time`
                                                        ORDER BY s.`the_time` DESC");
                            foreach ($rows as $row) {
                                $conversion = $row->impressions > 0 ? ($row->clicks / $row->impressions) * 100 : 0;
                        ?>
                        <tr class="statis-content">
                            <td><a href="<?php echo admin_url('admin.php?page=wpstickybars&view=edit&id='.$row->banner_id) ?>"><?php echo $row->title ?></a></td>
                            <td><?php echo date('Y-m-d', $row->the_time) ?></td>
                            <td><?php echo $row->impressions ?></td>
                            <td><?php echo $row->clicks ?></td>
                            <td><?php echo number_format($conversion, 2) ?>%</td>
                        </tr>
                        <?php
                            }
                        ?>
                    </table>
                  </div>
                        
                </div>
              <div class="clear"></div>
              
            </div>
          
          </div>
        </div>
      </form>
            
        
    </div>
    <div class="clear"></div>
  </div>


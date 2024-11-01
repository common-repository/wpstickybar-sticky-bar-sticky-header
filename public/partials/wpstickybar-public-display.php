<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       https://wpstickybar.com
 * @since      1.0.0
 *
 * @package    Wpstickybar
 * @subpackage Wpstickybar/public/partials
 */

?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<?php

wp_reset_query();
global $post;
global $wpdb;
global $tableprefix;
$tableprefix = $wpdb->prefix;
$stickybar_banner = $tableprefix . 'stickybar_banner';
$banner_list = $wpdb->get_results("SELECT * FROM " . $stickybar_banner . " WHERE `display_banner` = 1");

?>

<script>
  
  const showBanner = (bannerItem) => {

    // jQuery('#banner' + id).show();
    const id = bannerItem.banner_id;
    // Parse the other_options JSON string
    const otherOptions = JSON.parse(bannerItem.other_options);

    // Use the topPadding from otherOptions if it exists, otherwise default to 64
    var topPadding = otherOptions.topPadding ? parseInt(otherOptions.topPadding, 10) : 64;
    
    jQuery('html').css('padding-top', topPadding + 'px');

    jQuery.ajax({
      type: "post",
      dataType: "json",
      url: '<?php echo admin_url('admin-ajax.php'); ?>',
      data: {
        action: "stickybar_display",
        banner_id: id
      },
      success: function(textStatus) {
        // console.log('success')
      },
      error: function(jqXHR, textStatus, errorThrown) {
        console.log('error');
      }
    });

  } 

  function closeBanner(bannerItem) {
    document.querySelector('#banner' + bannerItem.banner_id).style.cssText = 'display:none !important';
    if (bannerItem.banner_position == 'position_top') {
      jQuery('html').css('padding-top', '0');
    }
  }

  // Function to handle the AJAX call
  function handleClick(bannerId) {
    jQuery.ajax({
      type: "post",
      dataType: "json",
      url: `<?php echo admin_url('admin-ajax.php'); ?>`,
      data: {
        action: "stickybar_click",
        banner_id: bannerId
      },
      success: function(textStatus) {
        // Do something when the data has been processed
      },
      error: function(jqXHR, textStatus, errorThrown) {
        // Do something when an error occurs
        console.log('error');
      }
    });

  };

  
  // Functions for date type conditions
  function relativeDate(bannerItem) {
    const {
      relative_day: dayStr = '0',
      relative_hour: hourStr = '0',
      relative_minutes: minuteStr = '0',
      relative_seconds: secondStr = '0',
    } = bannerItem;

    const day = Number(dayStr);
    const hour = Number(hourStr);
    const minute = Number(minuteStr);
    const second = Number(secondStr);

    const countdownSeconds = day * 86400 + hour * 3600 + minute * 60 + second;    
    const updateAtLocal = localStorage.getItem("countdownUpdateAt");

    const storedCountdownTime = localStorage.getItem("countdownTime");
    const now = new Date();
    const updateAt = now.getTime();

    if (storedCountdownTime === null || updateAtLocal !== updateAt) {
      localStorage.setItem("countdownUpdateAt", updateAt);
      localStorage.setItem("countdownTime", now);
    }

    const countdownTime = new Date(storedCountdownTime || now);
    const elapsedTime = Math.floor((now - countdownTime) / 1000);
    let remainingTime = countdownSeconds - elapsedTime;

    if (remainingTime > 0) {
      updateCountdown(remainingTime);
      showBanner(bannerItem);
    } else {
      resetCountdown();
      closeBanner(bannerItem);
      return;
    }

    let timer = setInterval(function () {
      remainingTime = updateCountdown(remainingTime);

      if (remainingTime <= 0) {
        clearInterval(timer);
        closeBanner(bannerItem);
      }
    }, 1000);

    function updateCountdown(remaining) {
      
      const day = Math.floor(remaining / 86400);
      const hour = Math.floor((remaining % 86400) / 3600);
      const minute = Math.floor((remaining % 3600) / 60);
      const second = remaining % 60;

      const units = [
      { type: "day", value: day },
      { type: "hour", value: hour },
      { type: "minute", value: minute },
      { type: "second", value: second },
      ];

      let htmlContent = "";
      for (const unit of units) {
        if (unit.value > 0 || htmlContent === "") {
          const unitText =
            unit.type === "day"
              ? "Days"
              : unit.type === "hour"
              ? "Hours"
              : unit.type === "minute"
              ? "Mins"
              : unit.type === "second"
              ? "Secs"
              : "Invalid Unit Type";
          const paddedValue = unit.value.toString().padStart(2, "0");
          htmlContent += `
            <div class="timer-unit timer-unit-block" data-unit-type="${unit.type}">
              <div class="timer-unit-number">${paddedValue}</div>
              <div class="timer-unit-text">${unitText}</div>
            </div>
          `;
        }
      }

      jQuery("#cf-timer-inner" + bannerItem.banner_id).html(htmlContent);

      return remainingTime - 1;
    }

    function resetCountdown() {
      updateRemainingTime(0);
    }
  }


  function staticDate(bannerItem) {
    var countDownDate = new Date(bannerItem.static_date).getTime();

    let now = new Date().getTime();
    var distance = countDownDate - now;
    let countdownFunc = () => {
      if (distance < 0) {
        clearInterval(timer);
        closeBanner(bannerItem);
      } else {
        
        var day = Math.floor(distance / (1000 * 60 * 60 * 24)),
          hour = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60)),
          minute = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60)),
          second = Math.floor((distance % (1000 * 60)) / 1000);

        const units = [
          { type: "day", value: day },
          { type: "hour", value: hour },
          { type: "minute", value: minute },
          { type: "second", value: second },
        ];

        let htmlContent = "";
        for (const unit of units) {
          if (unit.value > 0 || htmlContent === "") {
            const unitText =
            unit.type === "day"
              ? "Days"
              : unit.type === "hour"
              ? "Hours"
              : unit.type === "minute"
              ? "Mins"
              : unit.type === "second"
              ? "Secs"
              : "Invalid Unit Type";

            const paddedValue = unit.value.toString().padStart(2, "0");
            htmlContent += `
              <div class="timer-unit timer-unit-block" data-unit-type="${unit.type}">
                <div class="timer-unit-number">${paddedValue}</div>
                <div class="timer-unit-text">${unitText}</div>
              </div>
            `;
          }
        }

        jQuery("#cf-timer-inner" + bannerItem.banner_id).html(htmlContent);

        // jQuery('#banner' + bannerItem.banner_id).show();
        var barHeight = jQuery('#banner' + bannerItem.banner_id).outerHeight();
        jQuery('html').css('padding-top', barHeight + 'px');

      }
      distance = distance - 1000;
    }
    if (distance > 0) {
      showBanner(bannerItem);
      countdownFunc();
      if (countDownDate) {
        timer = setInterval(function() {
          countdownFunc();
        }, 1000);
      }
    } else {
      closeBanner(bannerItem);
    }
  }

  function handleBanner(bannerItem) {
    const { banner_id: bannerId, date_type: dateType, enable_timmer: enableTimer, on_mobile: onMobile } = bannerItem;

    // TODO Check hide mobile
    // if (onMobile == 1) {
    //   hideBanner(bannerItem);
    //   return;
    // }

    // Call the appropriate function based on the enable_timmer and date_type values
    if (enableTimer == "1") {
      if (dateType === "relative_date") {
        relativeDate(bannerItem);
      } else if (dateType === "static_date") {
        staticDate(bannerItem);
      } else {
        showBanner(bannerItem);
      }
    } else {
      showBanner(bannerItem);
    }
  }


</script>

<?php 
  foreach ($banner_list as $banner_item) {
    $otherOptions = json_decode($banner_item->other_options, true);

    // Get values from $otherOptions or set default values
    $width = isset($otherOptions['width']) ? $otherOptions['width'] : '1344px';
    $height = isset($otherOptions['height']) ? $otherOptions['height'] : 'auto';
    $layout = isset($otherOptions['layout']) ? $otherOptions['layout'] : '100';
    $isEnableButton = isset($otherOptions['isEnableButton']) ? $otherOptions['isEnableButton'] : false;
?>

  <!-- position setting -->
  <?php
  if ($banner_item->banner_position == 'position_bottom') {
    echo '<style>';
    echo '#banner'.$banner_item->banner_id.'{bottom: 0 !important; top: unset !important;}';
    echo '</style>';

  }

  ?>


  <div id="banner<?php echo $banner_item->banner_id ?>" class="preview-sticky-bar flex items-center justify-center" 
    style="
    height: <?php echo $height == 'auto' ? 'auto' : $height . 'px'; ?>;
    background: <?php
        $gradientBackground = 'linear-gradient(to right, rgb(47, 74, 245), rgb(169, 80, 229))'; // Default gradient
        if (isset($banner_item->other_options)) {          
            $otherOptions = json_decode($banner_item->other_options, true);
            if (isset($otherOptions['background_type']) && $otherOptions['background_type'] === 'gradient') {
              $gradientBackground = isset($otherOptions['gradient_background']) ? $otherOptions['gradient_background'] : $gradientBackground;
              echo $gradientBackground;
            } else if (isset($otherOptions['backgroundType']) && $otherOptions['backgroundType'] === 'gradient') {
              $gradientBackground = isset($otherOptions['gradient_background']) ? $otherOptions['gradient_background'] : $gradientBackground;
              echo $gradientBackground;
            } else if (isset($banner_item->background_color)) {              
              echo $banner_item->background_color;
            }
        } else if (isset($banner_item->background_color)) {          
          echo $banner_item->background_color;
        }
    ?>;
    
    --close-banner-background-color: #f2f5f9;
    --promotion-text-color: <?php echo $banner_item->text_color ?>;
    --call-action-color: <?php echo $banner_item->text_color ?>;
    --timer-unit-text-color: <?php echo $banner_item->text_time_color ?>;
    --timer-unit-number-color: <?php echo $banner_item->number_time_color ?>;
    --timer-unit-number-bg-color: <?php echo $banner_item->background_time_color ?>;
  ">
      <div class="close-banner">
        <svg fill="#676f84" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 30 30">
          <path d="M 7 4 C 6.744125 4 6.4879687 4.0974687 6.2929688 4.2929688 L 4.2929688 6.2929688 C 3.9019687 6.6839688 3.9019687 7.3170313 4.2929688 7.7070312 L 11.585938 15 L 4.2929688 22.292969 C 3.9019687 22.683969 3.9019687 23.317031 4.2929688 23.707031 L 6.2929688 25.707031 C 6.6839688 26.098031 7.3170313 26.098031 7.7070312 25.707031 L 15 18.414062 L 22.292969 25.707031 C 22.682969 26.098031 23.317031 26.098031 23.707031 25.707031 L 25.707031 23.707031 C 26.098031 23.316031 26.098031 22.682969 25.707031 22.292969 L 18.414062 15 L 25.707031 7.7070312 C 26.098031 7.3170312 26.098031 6.6829688 25.707031 6.2929688 L 23.707031 4.2929688 C 23.316031 3.9019687 22.682969 3.9019687 22.292969 4.2929688 L 15 11.585938 L 7.7070312 4.2929688 C 7.5115312 4.0974687 7.255875 4 7 4 z"></path>
        </svg>
      </div>
      <div class="header-wrapper flex items-center justify-center flex-wrap h-auto min-h-16" style="<?php echo 'width:'. $width;?>">
          <div class="promotion-text text-center pr-4 <?php echo $otherOptions['promotionFontSize'];?>">
            <?php echo $banner_item->promotion_text; ?>
          </div>

          <?php if ($banner_item->enable_timmer): ?>
            <div class="countdown-timer pt-4">
              <div id="cf-timer-inner<?php echo $banner_item->banner_id ?>" class="cf-timer-inner timer-card">
                <!-- show timer -->
              </div>
            </div>
          <?php endif; ?>

          <?php if ($isEnableButton): ?>
          <div class="cta-button">
              <a
                  href="<?php echo $banner_item->button_url; ?>"
                  target="_blank"
                  class="call-action-btn pl-4"
                  style="border: 1px solid rgb(255, 255, 255);"
              >
                <?php echo $banner_item->button_text; ?>
              </a>
          </div>
          <?php endif; ?>
      </div>
  </div>

  <?php

  ?>
  <?php 
    if ($banner_item->display_banner) {
      $hide_banner_style = '#banner' . $banner_item->banner_id . '{display: none;}';
      $hide_banner = false;
      $show_countdown = $banner_item->enable_timmer == 1;
      $otherOptions = json_decode($banner_item->other_options, true);
      
      // Check if hideOnPages is set and not empty
      if (isset($otherOptions['hideOnPages']) && !empty($otherOptions['hideOnPages'])) {
        $hideOnPages = explode(',', $otherOptions['hideOnPages']);
        $current_page_id = get_the_ID(); // Assuming this gets the current page ID
        if (in_array($current_page_id, $hideOnPages)) {
            $hide_banner = true;
        }
      }

      // if (wp_is_mobile() && $banner_item->on_mobile == 0) {
      //     $hide_banner = true;
      // }

      // if ($banner_item->on_mac == 0 && strstr($_SERVER['HTTP_USER_AGENT'], "Mac OS")) {
      //     $hide_banner = true;
      // }

      // if ($banner_item->on_page == 1 && get_post_type() == 'page') {
      //     $hide_banner = true;
      // }

      // if ($banner_item->on_post == 1 && get_post_type() == 'post') {
      //     $hide_banner = true;
      // }

      if ($hide_banner) {
          echo "<style>{$hide_banner_style}</style>";
      }

      echo '<style type="text/css">
              .countdown-timer' . $banner_item->banner_id . ' {
                  display: ' . ($show_countdown ? 'block' : 'none') . ' !important;
              }
            </style>';
    }
  ?>


  <script>
      
    // scope this function, so const closeBannerElement can be redeclare
    (function() {
      
      const closeBannerElement = document.querySelector("#banner<?php echo $banner_item->banner_id ?> .close-banner");
      closeBannerElement.addEventListener("click", () => closeBanner( (<?php echo json_encode($banner_item); ?> )));    
      
      // Event click banner
      jQuery(`.call-action`).click(function() {
        handleClick(<?php echo $banner_item->banner_id ?>);
      });

      //show banner after seconds_before option
      <?php if (!$hide_banner): ?>
      setTimeout(() => {
        handleBanner(<?php echo json_encode($banner_item); ?>);
      }, <?php echo $banner_item->seconds_before * 1000 ?>);
      <?php endif; ?>

    })();     

  </script>
<?php
}

?>
<?php


/**
 * Admin Part of Plugin, dashboard and options.
 *
 * @package    stickybar
 * @subpackage stickybar/admin
 */
class Wpstickybar_Settings extends Wpstickybar_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0 
	 * @access   private
	 * @var      string    $wpsticky    The ID of this plugin.
	 */
	private $wpsticky;

	private $banner_item;
	 
	public function sql(){

		if (!isset($_GET['id'])) return null;
		global $wpdb;
		global $tableprefix;
		$tableprefix = $wpdb->prefix ;
		$stickybar_banner = $tableprefix . 'stickybar_banner';
		$banner_id = $_GET['id'];
		// var_dump($wpdb);
		$banner_item = $wpdb ->get_row("SELECT * FROM ".$stickybar_banner." WHERE `banner_id` = ".$banner_id."");
		// var_dump($banner_list);
		// foreach($banner_list as $banner_item);
		// var_dump($banner_item);
		$this->banner_item = $banner_item;
		// var_dump("111", $this->banner_item);
		return $banner_item;
	}
	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @var      string    $wpsticky       The name of this plugin.
	 * @var      string    $version    The version of this plugin.
	 */
	public function __construct( $wpsticky ) {

		$this->id    = 'general';
		$this->label = __( 'General Settings', 'woocommerce' );
		$this->wpsticky = $wpsticky;
		$this->plugin_settings_tabs['haha'] = 'bbb';
	}

	// add_action('test','DBwp_stickybar_banner');

  /**
	 * Enable Bar Field
	 *
	 * @since 		1.0.0
	 * @return 		mixed 			The settings field
	 */
	public function disable_sticky_options_field($checked = false) {
		$item = $this->banner_item;	
		$activeCheckbox = $checked;

		if ($item && $item->display_banner == 1 ) $activeCheckbox = true;
		?>		
    <label class="disable-margin">
      <input type="checkbox" id="<?php echo $this->wpsticky; ?>_options[disable-stickybar]" name="disable-banner" value="1" <?php if($activeCheckbox) { echo 'checked'; } else { echo""; } ?> />
      <!-- <span class="slider"></span> -->
    </label>
    <!-- <p class="description">Disabling stickybar is also disabling front end loading of scripts css/js.</p> -->
    <?php
	} // disable_sticky_options_field()
	
  	/**
	 * Promotion text field
	 *
	 * @since 		1.0.0
	 * @return 		mixed 			The settings field
	 */
	public function promotion_text_input_field($defaultText = '') {
		$item = $this->banner_item;
		$option 	= $defaultText;		
		if  (!empty($item->promotion_text)) {
			$option = $item->promotion_text;
		}

		$settings = array(
			'textarea_name' => 'promotion_text',
			'textarea_rows' => 5,
			'wpautop' => false,
			'media_buttons' => false,
			'tinymce' => array(
					'toolbar1' => 'bold italic underline strikethrough',
					'toolbar2' => '',
					'toolbar3' => '',
					'toolbar4' => '',
					'statusbar' => false,
					'menubar' => false,
					'plugins' => 'paste',
					'paste_as_text' => true,
			),
			'quicktags' => array(
				'buttons' => 'strong,em,del',
			),
		);
	
		
		ob_start();
		wp_editor($option, 'promotion_text_editor', $settings);
		echo ob_get_clean();
	} // promotion_text_input_field()


  	/**
	 * Title text field
	 *
	 * @since 		1.0.0
	 * @return 		mixed 			The settings field
	 */
	public function title_input_field($defaultText = '') {
		$item = $this->banner_item;
		$option = 'banner ' . mt_rand(10000, 99999);
		if  (!empty($item->title)) {
			$option = $item->title;
		}

		?>
    <input type="text" name="title" class="regular-text" value="<?php echo $option?>" ></input>		
		<?php
	}
	
	// seconds_before_input_field
	/**
	 * Seconds before field
	 *
	 * @since 		1.0.0
	 * @return 		mixed 			The settings field
	 */
	public function seconds_before_input_field($defaultText = 3) {

		$item = $this->banner_item;
		$option = $defaultText;		
		if  (!empty($item->seconds_before)) {
			$option = $item->seconds_before;
		}
		?>
		<input type="number" name="seconds-before" class="regular-text" value="<?php echo $option?>" ></input>		
		<?php

	}

	/**
	 * Change text color field
	 *
	 * @since 		1.0.0
	 * @return 		mixed 			The settings field
	 */
	public function change_text_color_field() {
		$item = $this->banner_item;
		$option 	= '#fff';
		if (!empty($item->text_color)) {
			$option = $item->text_color;
		}

		?>
		<!-- import Lib -->
			<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
			<script src="https://cdn.jsdelivr.net/npm/@simonwep/pickr@1.8.0/dist/pickr.min.js"></script>
			<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
			<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@simonwep/pickr/dist/themes/monolith.min.css" />
		<!-- 'monolith' theme -->
		
		<!-- One of the following themes -->
			<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@simonwep/pickr/dist/themes/classic.min.css"/> <!-- 'classic' theme -->
			<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@simonwep/pickr/dist/themes/monolith.min.css"/> <!-- 'monolith' theme -->
			<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@simonwep/pickr/dist/themes/nano.min.css"/> <!-- 'nano' theme -->

		<!-- Modern or es5 bundle -->
			<script src="https://cdn.jsdelivr.net/npm/@simonwep/pickr/dist/pickr.min.js"></script>
			<script src="https://cdn.jsdelivr.net/npm/@simonwep/pickr/dist/pickr.es5.min.js"></script>
				<div class="conflig">
					<div id="btn-picker" name="<?php echo $this->wpsticky; ?>_options[Select Text color]" style=" background-color:  <?php echo $option; ?>"></div>
					<input type="text" id="<?php echo $this->wpsticky; ?>_options[Select Text color]" name="text-color" class="selected-color change-text-color"  value= "<?php echo $option ?>" placeholder="Selected color"></input>
				</div>
				<div class="color-picker"></div>
			
			<style>
				  #main {
            height: 400px;
        }
        
        /* .pickr {
            margin-top: -3%;
            margin-left: 30%;
        } */
				.save-color,.selected-color{
					height: 15px;
					border: 1px solid #000;
					border-radius: 3px;
					cursor: pointer;
					width: 100px;
					margin-bottom: 15px;
					padding: 0 10px !important;
					text-align: center;
					background-color: <?php echo sanitize_text_field( $option ); ?>;
				}
				.selected-color::placeholder{
					text-align: center;
				}
				.conflig{
					display: flex;
				}
				#btn-picker{
					width: 30px;
					height: 28px;
					background-color: <?php echo sanitize_text_field( $option ); ?>;
					border: 1px solid black;
					margin-right: 20px;
					cursor: pointer;
					border-radius: 3px;
				}
				.colorPicker{
					margin: 0;
				}
				.pcr-button{
					opacity: 0;
				}
				.pcr-app.visible{
					left: 50% !important;
					top:  50% !important;
				}
			</style>
		<script type="text/javascript">
		//  open background pick color
		var openTextPickColor = document.querySelector('.selected-color')
		var	openTextPickColorBtn = document.querySelector('#btn-picker')
		
		openTextPickColor.addEventListener('click',openTextPC)
		openTextPickColorBtn.addEventListener('click',openTextPC)

		function openTextPC(){
						var pickTextColor = document.querySelectorAll('.pcr-app')[0]
						console.log(pickTextColor)
						pickTextColor.classList.add("visible")
					}
					
        $(document).ready(function() {
            const pickr = Pickr.create({
				el: '.color-picker',
				theme: 'monolith', // or 'monolith', or 'nano'
				default: '#FFFFFF',
				swatches: [
					'rgba(244, 67, 54, 1)',
					'rgba(233, 30, 99, 1)',
					'rgba(156, 39, 176, 1)',
					'rgba(103, 58, 183, 1)',
					'rgba(63, 81, 181, 1)',
					'rgba(33, 150, 243, 1)',
					'rgba(255, 193, 7, 1)'
				],

				components: {

					// Main components
					preview: true,
					opacity: true,
					hue: true,

					// Input / output Options
					
				}
			});
		pickr.on('change',(...arg) =>{
			let color = arg[0].toHEXA()
			console.log('color',color)
			$('.selected-color').val(arg[0].toHEXA().toString())

			$('#btn-picker').css('backgroundColor',arg[0].toHEXA().toString())
			$('.promtion-text').css('color',arg[0].toHEXA().toString())
			$('.call-action-btn').css('color',arg[0].toHEXA().toString())
			$('.call-action-btn').css('border','1px solid' + arg[0].toHEXA().toString())
  			notification()

		})
        });
    </script>
		<?php

	}  // change_text_color()




	/**
	 * Change text color field
	 *
	 * @since 		1.0.0
	 * @return 		mixed 			The settings field
	 */
	public function change_background_color_field() {
		$item = $this->banner_item;
		$option 	= 'Select color';
		
		?>
			<div class="background-container">
			
				<div class="background-conflig">
					<div id="btn-background-picker" style=" background-color:  <?php echo ($item) ? $item->background_color : '#4d39e9'; ?>"></div>
					<input type="text" id="<?php echo $this->wpsticky; ?>_options[Select banner background color]" name="background-banner-color" class="selected-background-color"  value= "<?php echo ($item) ? $item->background_color : '#4d39e9'; ?>" placeholder="Selected color"></input>
				
				</div>
				<div class="background-colorjoe"></div>
			</div>
			<style>
				.selected-background-color{
					height: 15px;
					border: 1px solid #000;
					border-radius: 3px;
					cursor: pointer;
					width: 100px;
					margin-bottom: 15px;
					padding: 0 10px !important;
					text-align: center;
					background-color: <?php echo sanitize_text_field( $option ); ?>;
				}
				.selected-background-color::placeholder{
					text-align: center;
				}
				.background-conflig{
					display: flex;
				}
				#btn-background-picker{
					width: 30px;
					height: 28px;
					margin-right: 20px;
					border-radius:3px ;
					background-color: <?php echo sanitize_text_field( $option ); ?>;
					cursor: pointer;
					border: 1px solid black;
				}
			</style>
				<script type="text/javascript">
					//  open background pick color
					var openBackGroundPickColor = document.querySelector('.selected-background-color')
					var	openBackGroundPickColorBtn = document.querySelector('#btn-background-picker')
					openBackGroundPickColor.addEventListener('click', openBackgroundPCL)
					openBackGroundPickColorBtn.addEventListener('click', openBackgroundPCL)
					function openBackgroundPCL(){
						var pickBackgroundColor = document.querySelectorAll('.pcr-app')[1]
						console.log(pickBackgroundColor)
						pickBackgroundColor.classList.add("visible")
					}
					

					$(document).ready(function() {
						const pickr = Pickr.create({
							el: '.background-colorjoe',
							theme: 'monolith', // or 'monolith', or 'nano'
							default: '#8187C8',

							swatches: [
								'rgba(244, 67, 54, 1)',
								'rgba(233, 30, 99, 1)',
								'rgba(156, 39, 176, 1)',
								'rgba(103, 58, 183, 1)',
								'rgba(63, 81, 181, 1)',
								'rgba(33, 150, 243, 1)',
								'rgba(255, 193, 7, 1)'
							],

							components: {

								// Main components
								preview: true,
								opacity: true,
								hue: true,

								// Input / output Options
								
							}
						});
					pickr.on('change',(...arg) =>{
						let color = arg[0].toHEXA()
						// console.log('color',color)
						$('.selected-background-color').val(arg[0].toHEXA().toString())
						$('#btn-background-picker').css('backgroundColor',arg[0].toHEXA().toString())
						$('#background-type-input').val('solid');
						$('#preview').css('backgroundColor',arg[0].toHEXA().toString())
						notification()
					})
					});
				</script>
			
		<?php

	} // change_background_color()

/**
	 * Change text color field
	 *
	 * @since 		1.0.0
	 * @return 		mixed 			The settings field
	 */
	public function change_gradient_background_color_field() {
		$item = $this->banner_item;
		$option = 'Select color';
	
		$predefinedColors = [
			'linear-gradient(to right, rgb(47, 74, 245), rgb(169, 80, 229))',			
			'linear-gradient(to right, rgb(159, 189, 211), rgb(235, 230, 226))',
			'linear-gradient(to right, rgb(162, 128, 255), rgb(255, 157, 133))',
			'linear-gradient(to right, rgb(94, 136, 218), rgb(227, 240, 255))',
			'linear-gradient(to right, rgb(240, 226, 207), rgb(241, 194, 180))',
			'linear-gradient(to right, rgb(1, 190, 252), rgb(251, 216, 71))',
			'linear-gradient(to right, rgb(185, 251, 192), rgb(163, 196, 243))',
			'linear-gradient(to right, rgb(255, 212, 136), rgb(255, 246, 177))',
			'linear-gradient(to right, rgb(163, 255, 231), rgb(255, 144, 201))'			
		];
	
		$premiumColors = [
			'linear-gradient(to right, rgb(162, 128, 255), rgb(255, 157, 133))',
			'linear-gradient(to right, rgb(94, 136, 218), rgb(227, 240, 255))',
			'linear-gradient(to right, rgb(240, 226, 207), rgb(241, 194, 180))',
			'linear-gradient(to right, rgb(1, 190, 252), rgb(251, 216, 71))',
			'linear-gradient(to right, rgb(185, 251, 192), rgb(163, 196, 243))',
			'linear-gradient(to right, rgb(255, 212, 136), rgb(255, 246, 177))',
			'linear-gradient(to right, rgb(163, 255, 231), rgb(255, 144, 201))'
		];
	
		$selectedColor = ($item && isset($item->other_options)) ? (json_decode($item->other_options, true)['background_type'] === 'gradient' ? json_decode($item->other_options, true)['gradient_background'] : '') : '';
	
		?>
		<div class="background-container">
			<div class="background-conflig">
				<div class="predefined-colors">
					<?php foreach ($predefinedColors as $index => $gradient) { ?>
						<div class="gradient-color <?php echo ($selectedColor === $gradient) ? 'selected' : ''; ?>" style="background: <?php echo $gradient; ?>">
							<?php if (in_array($gradient, $premiumColors)) { ?>
								<div class="premium-lock" onclick="showPremiumAlert()"></div>
							<?php } ?>
						</div>
					<?php } ?>
				</div>
			</div>
			<style>
				.selected-background-color {
					height: 15px;
					border: 1px solid #000;
					border-radius: 3px;
					cursor: pointer;
					width: 100px;
					margin-bottom: 15px;
					padding: 0 10px !important;
					text-align: center;
					background-color: <?php echo sanitize_text_field($option); ?>;
				}
	
				.selected-background-color::placeholder {
					text-align: center;
				}
	
				.background-conflig {
					display: flex;
				}
	
				.predefined-colors {
					display: flex;
					flex-wrap: wrap;
				}
	
				.color-row {
					display: flex;
					flex-wrap: wrap;
				}
	
				.gradient-color {
					width: 30px;
					height: 28px;
					margin-right: 5px;
					margin-bottom: 5px;
					border-radius: 3px;
					cursor: pointer;
					background-repeat: no-repeat;
					background-size: cover;
					position: relative;
					display: flex;
    			align-items: center;
					justify-content: center;
				}
	
				.gradient-color.selected {
					border: 2px solid #000;
				}
	
				.premium-lock {
					position: absolute;
					top: 50%;
					left: 50%;
					transform: translate(-50%, -50%);
					position: absolute;					
					width: 12px;
					height: 12px;
					background-image: url(<?php echo plugin_dir_url( __FILE__ ) . '/lock-icon.png' ?> );
					background-size: cover;
					cursor: pointer;
				}
			</style>
			<script type="text/javascript">
				function showPremiumAlert() {
					alert('This is a premium feature. Please visit wpstickybar.com to purchase.');
					window.location.href = 'https://wpstickybar.com';
				}
	
				$(document).ready(function() {
					$('.gradient-color').click(function() {
						if ($(this).find('.premium-lock').length) {
							showPremiumAlert();
							return;
						}
	
						$('.gradient-color').removeClass('selected');
						$(this).addClass('selected');
						var selectedColor = $(this).css('background-image');
	
						// Save gradient options and background type to hidden input fields
						$('#gradient-options-input').val(selectedColor);
						$('#background-type-input').val('gradient');
	
						$('#btn-background-picker').css('background-color', selectedColor);
						$('#preview').css('background-color', selectedColor);
						notification();
					});
				});
			</script>
		</div>
		<?php
	}
	



	/**
	 * Change text timmer color field
	 *
	 * @since 		1.0.0
	 * @return 		mixed 			The settings field
	 */
	
	public function change_text_timmer_color_field() {
		$item = $this->banner_item;
		

		$options  	= get_option( $this->wpsticky . '_options' );
		$option 	= 'Select color';

		if ( ! empty( $options['Select text timer color'] ) ) {
			$option = $options['Select text timer color'];
		}
		?>
			<div class="text-timmer-container">
				<div class="text-timmer-conflig">
					<!-- <input type="text" id="<?php echo $this->wpsticky; ?>_options[change-text-color]" name="<?php echo $this->wpsticky; ?>_options[change-text-color]" class="selected-color"></input> -->
					<div id="btn-text-timmer-picker" style="background-color:<?php echo ($item) ? $item->text_time_color : "#000" ?>"></div>
						<input type="text" id="<?php echo $this->wpsticky; ?>_options[Select text timer color]" name = "text-timmer-color"class="selected-text-timmer-color"  value="<?php echo ($item) ? $item->text_time_color : "#000" ?>" ></input>
				</div>
				<div class="text-timmer-colorjoe"></div>
			</div>
			<style>
				.selected-text-timmer-color{
					height: 15px;
					border: 1px solid #000;
					border-radius: 3px;
					cursor: pointer;
					width: 100px;
					margin-bottom: 15px;
					padding: 0 10px !important;
					text-align: center;
					background-color: <?php echo sanitize_text_field( $option ); ?>;
				}
				.selected-text-timmer-color::placeholder{
					text-align: center;
				}
				.text-timmer-conflig{
					display: flex;
				}
				#btn-text-timmer-picker{
					width: 30px;
					height: 28px;
					margin-right: 20px;
					border-radius:3px ;
					cursor: pointer;
					/* line-height: 30px; */
					/* padding: 0 20px; */
					background-color: <?php echo sanitize_text_field( $option ); ?>;

					border: 1px solid black;
				}
			</style>
				<script type="text/javascript">
					//  open background pick color
					var openTextTimePickColor = document.querySelector('.selected-text-timmer-color')
					var	openTextTimePickColorBtn = document.querySelector('#btn-text-timmer-picker')
					openTextTimePickColorBtn.addEventListener('click',openTextTimePCL)
					openTextTimePickColor.addEventListener('click',openTextTimePCL)
					function openTextTimePCL(){
									var pickTextTimeColor = document.querySelectorAll('.pcr-app')[2]
									pickTextTimeColor.classList.add("visible")
								}
					$(document).ready(function() {
						const pickr = Pickr.create({
							el: '.text-timmer-colorjoe',
							theme: 'monolith', // or 'monolith', or 'nano'
							default: '#000000',

							swatches: [
								'rgba(244, 67, 54, 1)',
								'rgba(233, 30, 99, 1)',
								'rgba(156, 39, 176, 1)',
								'rgba(103, 58, 183, 1)',
								'rgba(63, 81, 181, 1)',
								'rgba(33, 150, 243, 1)',
								'rgba(255, 193, 7, 1)'
							],

							components: {

								// Main components
								preview: true,
								opacity: true,
								hue: true,

								// Input / output Options
								
							}
						});
					pickr.on('change',(...arg) =>{
						let color = arg[0].toHEXA()
						console.log('color',color)
						$('.selected-text-timmer-color').val(arg[0].toHEXA().toString())
						$('#btn-text-timmer-picker').css('backgroundColor',arg[0].toHEXA().toString())
						$('.cf-timer-unit-text').css('color',arg[0].toHEXA().toString())
						notification()

					})
					});
				</script>
			
		<?php

	} // change_text_timmer_color()



		/**
	 * Change text timmer color field
	 *
	 * @since 		1.0.0
	 * @return 		mixed 			The settings field
	 */
	public function change_number_timmer_color_field() {
		$item = $this->banner_item;
		

		$options  	= get_option( $this->wpsticky . '_options' );
		$option 	= 'Select color';

		if ( ! empty( $options['Select number timmer color'] ) ) {
			$option = $options['Select number timmer color'];
		}
		?>
			<div class="number-timmer-container">
			
				<div class="number-timmer-conflig">
					
					<!-- <div class="conflig-title">select</div> -->
					<!-- <input type="submit" class="select-color-text" ></input> -->

					<!-- <input type="text" id="<?php echo $this->wpsticky; ?>_options[change-text-color]" name="<?php echo $this->wpsticky; ?>_options[change-text-color]" class="selected-color"></input> -->
					<div id="btn-number-timmer-picker" style="background-color:  <?php echo ($item) ? $item->number_time_color : "#fff" ?>"></div>
					<input type="text" id="<?php echo $this->wpsticky; ?>_options[Select number timmer color" name="number-timmer-color" class="selected-number-timmer-color" value="<?php echo ($item) ? $item->number_time_color : "#fff" ?>" placeholder="Selected color"></input>
				</div>
				<div class="number-timmer-colorjoe"></div>
			</div>
			<style>
				.selected-number-timmer-color{
					height: 15px;
					border: 1px solid #000;
					border-radius: 3px;
					cursor: pointer;
					width: 100px;
					margin-bottom: 15px;
					padding: 0 10px !important;
					text-align: center;
					background-color: <?php echo sanitize_text_field( $option ); ?>;
				}
				.selected-number-timmer-color::placeholder{
					text-align: center;
				}
				.number-timmer-conflig{
					display: flex;
				}
				#btn-number-timmer-picker{
					width: 30px;
					height: 28px;
					margin-right: 20px;
					border-radius:3px ;
					/* line-height: 30px; */
					/* padding: 0 20px; */
					background-color: <?php echo sanitize_text_field( $option ); ?>;
					cursor: pointer;
					border: 1px solid black;
				}
			</style>
				<script type="text/javascript">
					//  open number pick color
					var openNumberTimePickColor = document.querySelector('.selected-number-timmer-color')
					var	openNumberTimePickColorBtn = document.querySelector('#btn-number-timmer-picker')
					openNumberTimePickColorBtn.addEventListener('click', openNumberTimePCL)
					openNumberTimePickColor.addEventListener('click', openNumberTimePCL)
					function openNumberTimePCL(){
									var pickNumberTimeColor = document.querySelectorAll('.pcr-app')[3]
									pickNumberTimeColor.classList.add("visible")
								}
					$(document).ready(function() {
						const pickr = Pickr.create({
							el: '.number-timmer-colorjoe',
							theme: 'monolith', // or 'monolith', or 'nano'
							default: '#000000',
							swatches: [
								'rgba(244, 67, 54, 1)',
								'rgba(233, 30, 99, 1)',
								'rgba(156, 39, 176, 1)',
								'rgba(103, 58, 183, 1)',
								'rgba(63, 81, 181, 1)',
								'rgba(33, 150, 243, 1)',
								'rgba(255, 193, 7, 1)'
							],

							components: {

								// Main components
								preview: true,
								opacity: true,
								hue: true,

								// Input / output Options
								
							}
						});
					pickr.on('change',(...arg) =>{
						let color = arg[0].toHEXA()
						console.log('color',color)
						$('.selected-number-timmer-color').val(arg[0].toHEXA().toString())
						$('#btn-number-timmer-picker').css('backgroundColor',arg[0].toHEXA().toString())
						$('.cf-timer-unit-number').css('color',arg[0].toHEXA().toString() )
						notification()
					})
					});
				</script>
			
		<?php

	} // change_background_timmer_color()


		/**
	 * Change background timmer color field
	 *
	 * @since 		1.0.0
	 * @return 		mixed 			The settings field
	 */
	public function change_background_timmer_color_field() {
		$item = $this->banner_item;
		$options  	= get_option( $this->wpsticky . '_options' );
		$option 	= 'Select color';

		if ( ! empty( $options['Select background timmer color'] ) ) {
			$option = $options['Select background timmer color'];
		}
		?>
			<div class="background-timmer-container">
			
				<div class="background-timmer-conflig">
					
					<!-- <div class="conflig-title">select</div> -->
					<!-- <input type="submit" class="select-color-text" ></input> -->

					<!-- <input type="text" id="<?php echo $this->wpsticky; ?>_options[change-text-color]" name="<?php echo $this->wpsticky; ?>_options[change-text-color]" class="selected-color"></input> -->
					<div id="btn-background-timmer-picker" style=" background-color:  <?php echo ($item) ? $item->background_time_color : "#000" ; ?>"></div>
					<input type="text" id="<?php echo $this->wpsticky; ?>_options[Select background timmer color]" name="background-timmer-color" class="selected-background-timmer-color"  value="<?php echo ($item) ? $item->background_time_color : "#000" ; ?>" ></input>
				</div>
				<div class="background-timmer-colorjoe"></div>
			</div>
			<style>
				.selected-background-timmer-color{
					height: 15px;
					border: 1px solid #000;
					border-radius: 3px;
					cursor: pointer;
					width: 100px;
					margin-bottom: 15px;
					padding: 0 10px !important;
					text-align: center;
					background-color: <?php echo sanitize_text_field( $option ); ?>;
				}
				.selected-background-timmer-color::placeholder{
					text-align: center;
				}
				.background-timmer-conflig{
					display: flex;
				}
				#btn-background-timmer-picker{
					width: 30px;
					height: 28px;
					margin-right: 20px;
					border-radius:3px ;
					/* line-height: 30px; */
					/* padding: 0 20px; */
					cursor: pointer;
					background-color: <?php echo sanitize_text_field( $option ); ?>;

					border: 1px solid black;
				}
			</style>
				<script type="text/javascript">
					//  open background pick color
					var openBackgroundTimePickColor = document.querySelector('.selected-background-timmer-color')
					var	openBackgroundTimePickColorBtn = document.querySelector('#btn-background-timmer-picker')
					openBackgroundTimePickColorBtn.addEventListener('click',openBackgroundTimePCL)
					openBackgroundTimePickColor.addEventListener('click',openBackgroundTimePCL)
					function openBackgroundTimePCL(){
									var pickBackgroundTimeColor = document.querySelectorAll('.pcr-app')[4]
									pickBackgroundTimeColor.classList.add("visible")
								}
					$(document).ready(function() {
						const pickr = Pickr.create({
							el: '.background-timmer-colorjoe',
							theme: 'monolith', // or 'monolith', or 'nano'
							default: '#FFFF',
							swatches: [
								'rgba(244, 67, 54, 1)',
								'rgba(233, 30, 99, 1)',
								'rgba(156, 39, 176, 1)',
								'rgba(103, 58, 183, 1)',
								'rgba(63, 81, 181, 1)',
								'rgba(33, 150, 243, 1)',
								'rgba(255, 193, 7, 1)'
							],

							components: {

								// Main components
								preview: true,
								opacity: true,
								hue: true,

								// Input / output Options
								
							}
						});
					pickr.on('change',(...arg) =>{
						let color = arg[0].toHEXA()
						console.log('color',color)
						$('.selected-background-timmer-color').val(arg[0].toHEXA().toString())
						$('#btn-background-timmer-picker').css('backgroundColor',arg[0].toHEXA().toString())
						$('.cf-timer-unit-number').css('backgroundColor',arg[0].toHEXA().toString() )
						notification()
					})
					});
				</script>
			
		<?php

	} // change_background_timmer_color()


  	/**
	 * Button text field
	 *
	 * @since 		1.0.0
	 * @return 		mixed 			The settings field
	 */
	public function cta_text_input_field($defaultText = '') {
		$item = $this->banner_item;

		$option 	= $defaultText;		
		if  (!empty($item->button_text)) {
			$option = $item->button_text;
		}		

		?>
    <input id="<?php echo $this->wpsticky; ?>_options[cta-text]" name="button-text" type="text" value="<?php echo $option; ?>" class="regular-text code change-action-btn">
		<?php
	} // cta_text_input_field()

  	/**
	 * CTA link field
	 *
	 * @since 		1.0.0
	 * @return 		mixed 			The settings field
	 */
	public function cta_url_input_field($defaultUrl = '') {
		$item = $this->banner_item;

		$option 	= $defaultUrl;		
		if  (!empty($item->button_url)) {
			$option = $item->button_url;
		}		

		?>
    <input id="<?php echo $this->wpsticky; ?>_options[cta-url]" name="button-url" type="text" value="<?php echo $option; ?>" class="regular-text code">
		<?php
	} // cta_link_input_field()

	/**
	 * Date types
	 *
	 * @since 		1.0.0
	 * @return 		mixed 			The settings field
	 */
	public function date_types() {
		$item = $this->banner_item;
		$option 	= '';		
		if  (!empty($item->date_type)) {
			$option = $item->date_type;
		}
		
		?>
		<select id="<?php echo $this->wpsticky; ?>_options[date-types]" name="date-type" class="disable-date-type" >
			<option value="">Select Date Type</option>
			<option value="static_date" <?php if($item && $item->date_type == 'static_date' ){echo' selected = "selected"';} ?> >Static Date</option>
			<option value="relative_date" <?php if($item && $item->date_type == 'relative_date' ){echo' selected = "selected"';} ?> >Relative Date</option>			
		</select>
		<p class="description">Static date: the sticky bar will displays until reach the static date</p>
    <p class="description">Relative date: the sticky bar will displays until the hours/minutes/seconds set by relative date is set</p>
		
		<?php

	} // date_types()

	/**
	 * Date types
	 *
	 * @since 		1.0.0
	 * @return 		mixed 			The settings field
	 */
	public function static_date() {
		$item = $this->banner_item;

		$options  	= get_option( $this->wpsticky . '_options' );
		$option 	= '';

		if ( ! empty( $options['static-date'] ) ) {
			$option = $options['static-date'];
		}

		?>
		<!-- <label for="static_date">Static Date Setting</label>
		<hr> -->
		<input name="static-date" type="datetime-local" value="<?php echo isset($item) ? $item->static_date : "" ; ?>" id="<?php echo $this->wpsticky; ?>_options[static-date]" class="regular-text code">
		<?php

	} // static_date()

	/**
	 * Relative date
	 *
	 * @since 		1.0.0
	 * @return 		mixed 			The settings field
	 */
	public function relative_date() {
		$item = $this->banner_item;

		$options  	= get_option( $this->wpsticky . '_options' );
		$day =0;
		$hour 	= 0;
		$minute 	= 0;
		$second 	= 0;
		if ( ! empty( $options['relative-date-day'] ) ) {
			$day = $options['relative-date-day'];
		}

		if ( ! empty( $options['relative-date-hour'] ) ) {
			$hour = $options['relative-date-hour'];
		}

		if ( ! empty( $options['relative-date-minute'] ) ) {
			$minute = $options['relative-date-minute'];
		}
		
		if ( ! empty( $options['relative-date-second'] ) ) {
			$second = $options['relative-date-second'];
		}
		?>
		<!-- <label for="static_date">Static Date Setting</label>
		<hr> -->
		
		<table class="form-table" role="presentation">
			<tbody>
			<tr>
					<th scope="row">
						<label for="tcp_form_status">Day</label>
					</th>
					<td>
						<input name="relative-day" class="regular-text code" type="number" value="<?php echo isset($item) ? $item->relative_day : ""; ?>" class="regular-text">
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="tcp_form_status">Hour</label>
					</th>
					<td>
						<input name="relative-hour" class="regular-text code selected-hour" type="number" value="<?php echo isset($item) ? $item->relative_hour : ""; ?>" class="regular-text">
					</td>
				</tr>

				<tr>
					<th scope="row">
						<label for="tcp_form_status">Minute</label>
					</th>
					<td>
						<input name="relative-minute" class="regular-text code" type="number"  min="0" max = "59" value="<?php echo isset($item) ? $item->relative_minutes : ""; ?>" class="regular-text">
					</td>
				</tr>

				<tr>
					<th scope="row">
						<label for="tcp_form_status">Second</label>
					</th>
					<td>
						<input name="relative-second" class="regular-text code" type="number" min="0" max = "59" value="<?php echo isset($item) ? $item->relative_seconds : ""; ?>" class="regular-text">
					</td>
				</tr>
			</tbody>
		</table>

		<?php

	} // relative_date()	

	/**
	 * Position settings
	 *
	 * @since 		1.0.0
	 * @return 		mixed 			The settings field
	 */
	public function position_settings() {
		$item = $this->banner_item;

		?>
		<select id="<?php echo $this->wpsticky; ?>_options[position-settings]" name="position-settings" >
			<option value="position_top" <?php if($item && $item->banner_position == 'position_top'){ echo "selected" ;}else{echo "";} ?> >Show at the top of the web page</option>
			<option value="position_bottom" <?php if($item && $item->banner_position == 'position_bottom'){ echo "selected" ;}else{echo "";} ?> >Show at the bottom of the web page</option>			
		</select>
		<p class="description">Specify the location to be displayed on the web page of the countdown widget.</p>
		<?php

	} // date_types()

    /**
	 * Disable on mobile
	 *
	 * @since 		1.0.0
	 * @return 		mixed 			The settings field
	 */
	public function disable_on_mobile_field() {
		$item = $this->banner_item;

		$options 	= get_option( $this->wpsticky . '_options' );
		$option 	= 0;

		if ( ! empty( $options['disable-on-mobile'] ) ) {

			$option = $options['disable-on-mobile'];

		}

		?>		
    <label class="">
      <input type="checkbox" id="<?php echo $this->wpsticky; ?>_options[disable-on-mobile]" name="display-on-mobile" value="1" <?php if($item && $item->on_mobile){echo 'checked';}else{ echo '';} ?> />
      <!-- <span class="slider"></span> -->
    </label>
    <!-- <p class="description">Disabling stickybar is also disabling front end loading of scripts css/js.</p> -->
    <?php
	} // disable_on_mobile_field()

    /**
	 * Disable on mac
	 *
	 * @since 		1.0.0
	 * @return 		mixed 			The settings field
	 */
	public function disable_on_mac_field() {
		$item = $this->banner_item;

		?>		
    <label class="">
      <input type="checkbox" id="<?php echo $this->wpsticky; ?>_options[disable-on-mac]" name="display-on-mac" value="1" <?php if($item && $item->on_mac){echo 'checked';}else{ echo '';} ?> />
      <!-- <span class="slider"></span> -->
    </label>
    <!-- <p class="description">Disabling stickybar is also disabling front end loading of scripts css/js.</p> -->
    <?php
		
	} // disable_on_mac_field()  
	

    /**
	 * Disable on page
	 *
	 * @since 		1.0.0
	 * @return 		mixed 			The settings field
	 */
	/**
	 * Creates our settings sections with fields etc. 
	 *
	 * @since    1.0.0
	 */
	public function disable_on_page_field() {
		$item = $this->banner_item;

		?>		
    <label class="">
      <input type="checkbox" id="<?php echo $this->wpsticky; ?>_options[disable-on-page]" name="display-on-page" value="1" <?php if($item && $item->on_page){echo 'checked';}else{ echo '';} ?> />
      <!-- <span class="slider"></span> -->
    </label>

    <!-- <p class="description">Disabling stickybar is also disabling front end loading of scripts css/js.</p> -->
    <?php
	
	}
	// disable on page

	 /**
	 * Disable on post
	 *
	 * @since 		1.0.0
	 * @return 		mixed 			The settings field
	 */
	/**
	 * Creates our settings sections with fields etc. 
	 *
	 * @since    1.0.0
	 */
	public function disable_on_post_field() {
		$item = $this->banner_item;

		?>		
    <label class="">
      <input type="checkbox" id="<?php echo $this->wpsticky; ?>_options[disable-on-post]" name="display-on-post" value="1" <?php if($item && $item->on_post){echo 'checked';}else{ echo '';} ?> />
      <!-- <span class="slider"></span> -->
    </label>

    <!-- <p class="description">Disabling stickybar is also disabling front end loading of scripts css/js.</p> -->
    <?php
	
	}
	// display on post



	 /**
	 * Disable countdown timmer
	 *
	 * @since 		1.0.0
	 * @return 		mixed 			The settings field
	 */
	/**
	 * Creates our settings sections with fields etc. 
	 *
	 * @since    1.0.0
	 */
	public function disable_countdown_timmer() {
		$item = $this->banner_item;

		?>		
    <label class="disable-margin">
      <input type="checkbox" id="<?php echo $this->wpsticky; ?>_options[disable-countdown-timmer]" class="change-countdown-timmer" name="enable-timmer" value="1"  <?php if($item && $item->enable_timmer == 1){echo 'checked';}else{ echo ' ';} ?> />
    </label>

    <!-- <p class="description">Disabling stickybar is also disabling front end loading of scripts css/js.</p> -->
    <?php
	
	}
	// display on post



	public function upload_background_img() {
		$item = $this->banner_item;
	
		$image_id = $item->background_img;
		$image_url = wp_get_attachment_image_url( $image_id, 'medium' );
		?>
		<?php if ( ! empty( $image_url ) ) : ?>
			<a href="#" class="rudr-upload"></a>
			<input type="hidden" name="background-image" value="<?php echo esc_attr( $image_url ); ?>">
			<a href="#" class="rudr-remove">Remove image</a>
		<?php else : ?>
			<a href="#" class="button rudr-upload">Select image</a>
			<input style="margin-left:15px ;" class="rudr_img" type="text" name="background-image" value="<?php echo esc_attr( $item->background_img ); ?>">
		<?php endif; ?>
		<script>
			jQuery(function($) {
				$('body').on('click', '.rudr-upload', function(event) {
					event.preventDefault();
					const button = $(this);
					const customUploader = wp.media({
						title: 'Insert image',
						library: {
							type: 'image'
						},
						button: {
							text: 'Use this image'
						},
						multiple: false
					}).on('select', function() {
						const attachment = customUploader.state().get('selection').first().toJSON();
						button.next().show();
						$('.rudr_img').val(attachment.url);
						notification();						
					});
					customUploader.open();
				});
				$('body').on('click', '.rudr-remove', function(event) {
					event.preventDefault();
					const button = $(this);
					button.next().val('');
				});
			});
		</script>
		<?php
	}
	
	// upload img

		 /**
	 * Disable countdown timmer
	 *
	 * @since 		1.0.0
	 * @return 		mixed 			The settings field
	 */
	/**
	 * Creates our settings sections with fields etc. 
	 *
	 * @since    1.0.0
	 */
	public function remove_background_img() {
		$item = $this->banner_item;
		
		?>
		<style>
			.remove-img {
				width: 110px !important;
				cursor: pointer;
				border: 1px solid #626298 !important;
				color: #2271b1;
				border-color: #2271b1;
				user-select: none !important;
			}
	
			.remove-img::placeholder {
				user-select: none !important;
				color: #2271b1;
				font-size: 13px
			}
	
			.remove-btn {
				margin-top: 15px;
			}
		</style>
	
		<label class="remove-btn">
			<input type="text" id="<?php echo $this->wpsticky; ?>_options[background-image]" class="button remove-img" name="<?php echo $this->wpsticky; ?>_options[background-image]" placeholder="Remove image"  />
		</label>
	
		<script>
			var removeImg = document.querySelector('.remove-img')
			removeImg.addEventListener('click', function(){
				removeImg.value = '';
				var urlImg =  document.querySelector('.rudr_img')
				urlImg.value = '';
				notification();
			})
		</script>
		<?php
	}
	
	// remove img


	public function settings_api_init(){
    
		$options  	= get_option( $this->wpsticky . '_options' );
		 
			
		// register_setting( $option_group, $option_name, $settings_sanitize_callback );
		register_setting(
			$this->wpsticky . '_options',
			$this->wpsticky . '_options',
			array( $this, 'settings_sanitize' )
		);

		// add_settings_section( $id, $title, $callback, $menu_slug );
		add_settings_section(
			$this->wpsticky . '-display-options', // section
			apply_filters( $this->wpsticky . '-display-section-title', __( '', $this->wpsticky ) ),
			array( $this, 'display_options_section' ),
			$this->wpsticky
		);
		// add_settings_field( $id, $title, $callback, $menu_slug, $section, $args );
		add_settings_field(
			'disable-stickybar',
			apply_filters( $this->wpsticky . '-disable-bar-label', __( 'Disable Stickybar', $this->wpsticky ) ),
			array( $this, 'disable_sticky_options_field' ),
			$this->wpsticky,
			$this->wpsticky . '-display-options' // section to add to
		);
		
		add_settings_field(
			'promotion-text',
			apply_filters( $this->wpsticky . '-promotion-text-label', __( 'Change promotion text', $this->wpsticky ) ),
			array( $this, 'promotion_text_input_field' ),
			$this->wpsticky,
			$this->wpsticky . '-display-options'
		);
		add_settings_field(
			'Select Text color',
			apply_filters( $this->wpsticky . 'Select Text color', __( 'Change text color', $this->wpsticky ) ),
			array( $this, 'change_text_color_field' ),
			$this->wpsticky,
			$this->wpsticky . '-display-options' // section to add to
		);
		add_settings_field(
			'Select banner background color',
			apply_filters( $this->wpsticky . '-Select banner background color', __( 'Change background color', $this->wpsticky ) ),
			array( $this, 'change_background_color_field' ),
			$this->wpsticky,
			$this->wpsticky . '-display-options' // section to add to
		);
		add_settings_field(
			'Select text timmer color',
			apply_filters( $this->wpsticky . '-Select text timmer color', __( 'Change text timmer color', $this->wpsticky ) ),
			array( $this, 'change_text_timmer_color_field' ),
			$this->wpsticky,
			$this->wpsticky . '-display-options' // section to add to
		);
		add_settings_field(
			'Select number timmer color',
			apply_filters( $this->wpsticky . '-Select number timmer color', __( 'Change number timer color', $this->wpsticky ) ),
			array( $this, 'change_number_timmer_color_field' ),
			$this->wpsticky,
			$this->wpsticky . '-display-options' // section to add to
		);
		add_settings_field(
			'Select background timmer color',
			apply_filters( $this->wpsticky . '-Select background timmer color', __( 'Change background timmer color', $this->wpsticky ) ),
			array( $this, 'change_background_timmer_color_field' ),
			$this->wpsticky,
			$this->wpsticky . '-display-options' // section to add to
		);
		add_settings_field(
			'date_type',
			apply_filters( $this->wpsticky . '-date-types', __( 'Date types', $this->wpsticky ) ),
			array( $this, 'date_types' ),
			$this->wpsticky,
			$this->wpsticky . '-display-options'
		);

		// if ( $options['date-types'] == 'relative_date' ) {
		// 	add_settings_field(
		// 		'relative_date',
		// 		apply_filters( $this->wpsticky . '-relative-date', __( 'Relative Date Setting', $this->wpsticky ) ),
		// 		array( $this, 'relative_date' ),
		// 		$this->wpsticky,
		// 		$this->wpsticky . '-display-options'
		// 	);			
		// } else {			
			add_settings_field(
				'static_date',
				apply_filters( $this->wpsticky . '-static-date', __( 'Static Date Setting', $this->wpsticky ) ),
				array( $this, 'static_date' ),
				$this->wpsticky,
				$this->wpsticky . '-display-options'
			);
		// }
    
		add_settings_field(
			'cta-text',
			apply_filters( $this->wpsticky . '-cta-text-label', __( 'Change call to action text', $this->wpsticky ) ),
			array( $this, 'cta_text_input_field' ),
			$this->wpsticky,
			$this->wpsticky . '-display-options'
		);
		
    add_settings_field(
			'cta-url',
			apply_filters( $this->wpsticky . '-cta-url-label', __( 'Change call to action url', $this->wpsticky ) ),
			array( $this, 'cta_url_input_field' ),
			$this->wpsticky,
			$this->wpsticky . '-display-options'
		);		

    // add_settings_field(
		// 	'background-image',
		// 	apply_filters( $this->wpsticky . '-background-image-label', __( 'Set the Background image.', $this->wpsticky ) ),
		// 	array( $this, 'background_image' ),
		// 	$this->wpsticky,
		// 	$this->wpsticky . '-display-options'
		// );		

    add_settings_field(
			'position-settings',
			apply_filters( $this->wpsticky . '-date-types', __( 'Position Settings', $this->wpsticky ) ),
			array( $this, 'position_settings' ),
			$this->wpsticky,
			$this->wpsticky . '-display-options'
		);

    add_settings_field(
			'disable-on-mobile',
			apply_filters( $this->wpsticky . '-disable-on-mobile', __( 'Disable on mobile', $this->wpsticky ) ),
			array( $this, 'disable_on_mobile_field' ),
			$this->wpsticky,
			$this->wpsticky . '-display-options' // section to add to
		);
    
    add_settings_field(
			'disable-on-mac',
			apply_filters( $this->wpsticky . '-disable-on-mac', __( 'Disable on mac', $this->wpsticky ) ),
			array( $this, 'disable_on_mac_field' ),
			$this->wpsticky,
			$this->wpsticky . '-display-options' // section to add to
		);
	add_settings_field(
		'disable-on-page',
		apply_filters( $this->wpsticky . '-disable-on-page', __( 'Disable on page', $this->wpsticky ) ),
		array( $this, 'disable_on_page_field' ),
		$this->wpsticky,
		$this->wpsticky . '-display-options' // section to add to
	);
	add_settings_field(
		'disable-on-post',
		apply_filters( $this->wpsticky . '-disable-on-post', __( 'Disable on post', $this->wpsticky ) ),
		array( $this, 'disable_on_post_field' ),
		$this->wpsticky,
		$this->wpsticky . '-display-options' // section to add to
	);
	add_settings_field(
		'disable-countdown-timmer',
		apply_filters( $this->wpsticky . '-disable-countdown-timmer', __( 'Disable countdown timmer', $this->wpsticky ) ),
		array( $this, 'disable_countdown_timmer' ),
		$this->wpsticky,
		$this->wpsticky . '-display-options' // section to add to
	);
	add_settings_field(
		'upload-background-img',
		apply_filters( $this->wpsticky . '-upload-background-img', __( 'Upload background image', $this->wpsticky ) ),
		array( $this, 'upload_background_img' ),
		$this->wpsticky,
		$this->wpsticky . '-display-options' // section to add to
	);
	add_settings_field(
		'remove-background-img',
		apply_filters( $this->wpsticky . '-remove-background-img', __( 'Remove background img', $this->wpsticky ) ),
		array( $this, 'remove_background_img' ),
		$this->wpsticky,
		$this->wpsticky . '-display-options' // section to add to
	);


	}
	

	/**
	 * Creates a settings section
	 *
	 * @since 		1.0.0
	 * @param 		array 		$params 		Array of parameters for the section
	 * @return 		mixed 						The settings section
	 */
	public function display_options_section( $params ) {

		echo '<p>' . $params['title'] . '</p>';

	} // display_options_section();


	
}

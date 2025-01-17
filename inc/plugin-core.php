<?php // Google Analytics - Core Functions

if (!function_exists('add_action')) die();

function sfga_google_analytics_init($SEO_Friendly_GA) {
	
	$options = get_option('sfga_options', $SEO_Friendly_GA->default_options());
	
	$location = isset($options['sfga_location']) ? $options['sfga_location'] : 'header';
	
	$admin = isset($options['admin_area']) ? $options['admin_area'] : 0;
	
	$function = 'sfga_google_analytics_tracking_code';
	
	if ($location === 'header') {
		
		if ($admin) add_action('admin_head', $function);
		
		add_action('wp_head', $function);
		
	} else {
		
		if ($admin) add_action('admin_footer', $function);
		
		add_action('wp_footer', $function);
		
	}
	
}

function sfga_google_analytics_tracking_code() {
	
	extract(sfga_google_analytics_options());
	
	if (empty($tracking_id)) {
		
		echo $custom;
		
		return;
		
	}
	
	if (empty($tracking_method)) return;
	
	if (current_user_can('administrator') && $disable_admin) return;
	
	if ($custom && $custom_location) echo $custom . "\n";
	
	if ($tracking_method == 3) {
		
		sfga_google_analytics_legacy($options);
		
	} elseif ($tracking_method == 2) {
		
		sfga_google_analytics_global($options);
		
	} else {
		
		if ($universal) {
			
			sfga_google_analytics_universal($options);
			
		} else {
			
			sfga_google_analytics_legacy($options);
			
		}
		
	}
	
	if ($custom && !$custom_location) echo $custom . "\n";
	
}

function sfga_google_analytics_universal() {
	
	extract(sfga_google_analytics_options());
	
	$custom_code = sfga_google_analytics_custom_code($custom_code);
	
	$ga_display = "ga('require', 'displayfeatures');";
	$ga_link    = "ga('require', 'linkid');";
	$ga_anon    = "ga('set', 'anonymizeIp', true);";
	$ga_ssl     = "ga('set', 'forceSSL', true);";
	
	?>

		<script>
			(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
			(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
			m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
			})(window,document,'script','<?=SFGA_URL?>js/analytics.js','ga');
			ga('create', '<?php echo $tracking_id; ?>', 'auto'<?php if ($tracker_object) echo ', '. $tracker_object; ?>);
			<?php 
				if ($custom_code) echo $custom_code . "\n\t\t\t";
				if ($display_ads) echo $ga_display  . "\n\t\t\t";
				if ($link_attr)   echo $ga_link     . "\n\t\t\t";
				if ($anonymize)   echo $ga_anon     . "\n\t\t\t";
				if ($force_ssl)   echo $ga_ssl      . "\n\t\t\t";
			?>ga('send', 'pageview');
		</script>

	<?php
	
}

function sfga_google_analytics_global() {
	
	extract(sfga_google_analytics_options());
	
	$custom_code = sfga_google_analytics_custom_code($custom_code);
	
	?>

		<script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo $tracking_id; ?>"></script>
		<script>
			window.dataLayer = window.dataLayer || [];
			function gtag(){dataLayer.push(arguments);}
			gtag('js', new Date());
			<?php if ($custom_code) echo $custom_code . "\n\t\t\t";
			?>gtag('config', '<?php echo $tracking_id; ?>'<?php if ($tracker_object) echo ', '. $tracker_object; ?>);
		</script>

	<?php
	
}

function sfga_google_analytics_legacy() {
	
	extract(sfga_google_analytics_options());
	
	$custom_code = sfga_google_analytics_custom_code($custom_code);
	
	$ga_alt  = "('https:' == document.location.protocol ? 'https://' : 'http://') + 'stats.g.doubleclick.net/dc.js';";
	$ga_src  = "('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';";
	$ga_link = "var pluginUrl = '//www.google-analytics.com/plugins/ga/inpage_linkid.js';\n\t\t\t_gaq.push(['_require', 'inpage_linkid', pluginUrl]);";
	$ga_anon = "_gaq.push(['_gat._anonymizeIp']);";
	$ga_ssl  = "_gaq.push(['_gat._forceSSL']);";
	
	if ($display_ads) $ga_src = $ga_alt;
	
	?>

		<script type="text/javascript">
			var _gaq = _gaq || [];
			<?php 
				if ($link_attr)   echo $ga_link     . "\n\t\t\t";
				if ($anonymize)   echo $ga_anon     . "\n\t\t\t"; 
				if ($force_ssl)   echo $ga_ssl      . "\n\t\t\t"; 
				if ($custom_code) echo $custom_code . "\n\t\t\t"; 
			?>_gaq.push(['_setAccount', '<?php echo $tracking_id; ?>']);
			_gaq.push(['_trackPageview']);
			(function() {
				var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
				ga.src = <?php echo $ga_src . "\n"; ?>
				var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
			})();
		</script>

	<?php
	
}

function sfga_google_analytics_custom_code($custom_code) {
	
	$custom_code_array = explode(PHP_EOL, $custom_code);
	
	$custom_code = '';
	
	foreach ($custom_code_array as $code) {
		
		$code = preg_replace("/%%userid%%/i", get_current_user_id(), $code);
		
		$custom_code .= "\t\t\t" . trim($code) . "\n";
		
	}
	
	$custom_code = trim($custom_code);
	
	return apply_filters('sfga_custom_code', $custom_code);
	
}

function sfga_google_analytics_options() {
	
	global $SEO_Friendly_GA;
	
	$options = get_option('sfga_options', $SEO_Friendly_GA->default_options());
	
	$tracking_id = (isset($options['sfga_id']) && !empty($options['sfga_id'])) ? $options['sfga_id'] : '';
	
	$location        = isset($options['sfga_location'])    ? $options['sfga_location']    : 'header';
	
	$tracking_method = isset($options['sfga_enable'])      ? $options['sfga_enable']      : 1;
	
	$universal       = isset($options['sfga_universal'])   ? $options['sfga_universal']   : 1;
	
	$display_ads     = isset($options['sfga_display_ads']) ? $options['sfga_display_ads'] : 0;
	
	$link_attr       = isset($options['link_attr'])       ? $options['link_attr']       : 0;
	
	$anonymize       = isset($options['sfga_anonymize'])   ? $options['sfga_anonymize']   : 0;
	
	$force_ssl       = isset($options['sfga_force_ssl'])   ? $options['sfga_force_ssl']   : 0;
	
	$admin_area      = isset($options['admin_area'])      ? $options['admin_area']      : 0;
	
	$disable_admin   = isset($options['disable_admin'])   ? $options['disable_admin']   : 0;
	
	$custom_location = isset($options['sfga_custom_loc'])  ? $options['sfga_custom_loc']  : 0;
	
	$tracker_object  = isset($options['tracker_object'])  ? $options['tracker_object']  : '';
	
	$custom_code     = isset($options['sfga_custom_code']) ? $options['sfga_custom_code'] : '';
	
	$custom          = isset($options['sfga_custom'])      ? $options['sfga_custom']      : '';
	
	// $options, $tracking_id, $location, $tracking_method, $universal, $display_ads, $link_attr, $anonymize, 
	// $force_ssl, $admin_area, $disable_admin, $custom_location, $tracker_object, $custom_code, $custom
	
	return array(
		
		'options'         => $options,
		'tracking_id'     => $tracking_id,
		'location'        => $location,
		'tracking_method' => $tracking_method,
		'universal'       => $universal,
		'display_ads'     => $display_ads,
		'link_attr'       => $link_attr,
		'anonymize'       => $anonymize,
		'force_ssl'       => $force_ssl,
		'admin_area'      => $admin_area,
		'disable_admin'   => $disable_admin,
		'custom_location' => $custom_location,
		'tracker_object'  => $tracker_object,
		'custom_code'     => $custom_code,
		'custom'          => $custom
	);
	
}

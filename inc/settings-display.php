<?php // Google Analytics - Settings Display

if (!function_exists('add_action')) die(); ?>

<div class="wrap">
	
	<h1><?php echo SFGA_NAME; ?> <small><?php echo 'v'. SFGA_VERSION; ?></small></h1>
	
	<form method="post" action="options.php">
		
		<?php settings_fields('sfga_plugin_options'); ?>
		
		<div class="metabox-holder">
			
			<div class="meta-box-sortables ui-sortable">
				
				<div id="sfga-panel-current" class="postbox">
					
					<h2><?php esc_html_e('Welcome', 'seo-friendly-ga'); ?></h2>
					
					<div class="toggle<?php if (isset($_GET['settings-updated'])) echo ' default-hidden'; ?>">
						
						<?php
							echo '<p>'.esc_html__('Thank you for using SEO Friendly GA plugin. Enjoy adding SEO friendly GA code to your website with a few easy settings.', 'seo-friendly-ga').'</p>';
						?>
						
					</div>
					
				</div>
				
				<div id="sfga-panel-settings" class="postbox">
					
					<h2><?php esc_html_e('Plugin Settings', 'seo-friendly-ga'); ?></h2>
					
					<div class="toggle<?php if (isset($_GET['settings-updated'])) echo ' default-hidden'; ?>">
						
						<div class="sfga-panel-settings">
							
							<table class="widefat">
								<tr>
									<th><label for="sfga_options[sfga_id]"><?php esc_html_e('GA Tracking ID', 'seo-friendly-ga') ?></label></th>
									<td><input id="sfga_options[sfga_id]" name="sfga_options[sfga_id]" type="text" size="20" maxlength="22" value="<?php if (isset($sfga_options['sfga_id'])) echo esc_attr($sfga_options['sfga_id']); ?>"></td>
								</tr>
								<tr>
									<th><label for="sfga_options[sfga_enable]"><?php esc_html_e('Tracking Method', 'seo-friendly-ga') ?></label></th>
									<td><?php echo $this->select_menu($this->options_libraries(), 'sfga_enable'); ?></td>
								</tr>
							</table>
							
							<div class="sfga-info-universal<?php if (isset($sfga_options['sfga_enable']) && $sfga_options['sfga_enable'] == 2) echo ' default-hidden'; ?>">
								
								<table class="widefat">
									<tr>
										<th><label for="sfga_options[sfga_display_ads]"><?php esc_html_e('Display Advertising', 'seo-friendly-ga') ?></label></th>
										<td>
											<input id="sfga_options[sfga_display_ads]" name="sfga_options[sfga_display_ads]" type="checkbox" value="1" <?php if (isset($sfga_options['sfga_display_ads'])) checked('1', $sfga_options['sfga_display_ads']); ?>> 
											<?php esc_html_e('Enable support for', 'seo-friendly-ga'); ?> 
											<a target="_blank" rel="noopener noreferrer" href="https://support.google.com/analytics/answer/2444872"><?php esc_html_e('Display Advertising', 'seo-friendly-ga'); ?></a>
										</td>
									</tr>
									<tr>
										<th><label for="sfga_options[link_attr]"><?php esc_html_e('Link Attribution', 'seo-friendly-ga') ?></label></th>
										<td>
											<input id="sfga_options[link_attr]" name="sfga_options[link_attr]" type="checkbox" value="1" <?php if (isset($sfga_options['link_attr'])) checked('1', $sfga_options['link_attr']); ?>> 
											<?php esc_html_e('Enable support for', 'seo-friendly-ga'); ?> 
											<a target="_blank" rel="noopener noreferrer" href="https://developers.google.com/analytics/devguides/collection/analyticsjs/enhanced-link-attribution"><?php esc_html_e('Enhanced Link Attribution', 'seo-friendly-ga'); ?></a>
										</td>
									</tr>
									<tr>
										<th><label for="sfga_options[sfga_anonymize]"><?php esc_html_e('IP Anonymization', 'seo-friendly-ga') ?></label></th>
										<td>
											<input id="sfga_options[sfga_anonymize]" name="sfga_options[sfga_anonymize]" type="checkbox" value="1" <?php if (isset($sfga_options['sfga_anonymize'])) checked('1', $sfga_options['sfga_anonymize']); ?>> 
											<?php esc_html_e('Enable support for', 'seo-friendly-ga'); ?> 
											<a target="_blank" rel="noopener noreferrer" href="https://developers.google.com/analytics/devguides/collection/analyticsjs/ip-anonymization"><?php esc_html_e('IP Anonymization', 'seo-friendly-ga'); ?></a>
										</td>
									</tr>
									<tr>
										<th><label for="sfga_options[sfga_force_ssl]"><?php esc_html_e('Force SSL', 'seo-friendly-ga') ?></label></th>
										<td>
											<input id="sfga_options[sfga_force_ssl]" name="sfga_options[sfga_force_ssl]" type="checkbox" value="1" <?php if (isset($sfga_options['sfga_force_ssl'])) checked('1', $sfga_options['sfga_force_ssl']); ?>>
											<?php esc_html_e('Enable support for', 'seo-friendly-ga'); ?> 
											<a target="_blank" rel="noopener noreferrer" href="https://developers.google.com/analytics/devguides/collection/analyticsjs/field-reference#forceSSL"><?php esc_html_e('Force SSL', 'seo-friendly-ga'); ?></a>
										</td>
									</tr>
								</table>
								
							</div>
							
							<table class="widefat">
								<tr>
									<th><label for="sfga_options[sfga_location]"><?php esc_html_e('Tracking Code Location', 'seo-friendly-ga'); ?></label></th>
									<td>
										<?php echo $this->select_menu($this->options_locations(), 'sfga_location'); ?>
										<div class="sfga-caption">
											<?php esc_html_e('Tip: Google recommends including the tracking code in the page head, but including it in the footer can benefit page performance.', 'seo-friendly-ga'); ?> 
											<?php esc_html_e('If in doubt, go with the head option.', 'seo-friendly-ga'); ?>
										</div>
									</td>
								</tr>
								<tr>
									<th><label for="sfga_options[tracker_object]"><?php esc_html_e('Custom Tracker Objects', 'seo-friendly-ga'); ?></label></th>
									<td>
										<textarea id="sfga_options[tracker_object]" name="sfga_options[tracker_object]" type="textarea" rows="4" cols="70"><?php if (isset($sfga_options['tracker_object'])) echo esc_textarea($sfga_options['tracker_object']); ?></textarea>
										<div class="sfga-caption"> 
											<?php esc_html_e('Any code entered here will be added to', 'seo-friendly-ga'); ?> <code>ga('create')</code> 
											<?php esc_html_e('for Universal Analytics, or added to', 'seo-friendly-ga'); ?> <code>gtag('config')</code> 
											<?php esc_html_e('for Global Site Tag. This is useful for things like', 'seo-friendly-ga'); ?> 
											<a target="_blank" rel="noopener noreferrer" href="https://developers.google.com/analytics/devguides/collection/analyticsjs/creating-trackers"><?php esc_html_e('tracker objects', 'seo-friendly-ga'); ?></a> 
											<?php esc_html_e('and', 'seo-friendly-ga'); ?> 
											<a target="_blank" rel="noopener noreferrer" href="https://support.google.com/optimize/answer/6262084"><?php esc_html_e('optimize', 'seo-friendly-ga'); ?></a>.
										</div>
									</td>
								</tr>
								<tr>
									<th><label for="sfga_options[sfga_custom_code]"><?php esc_html_e('Custom GA Code', 'seo-friendly-ga'); ?></label></th>
									<td>
										<textarea id="sfga_options[sfga_custom_code]" name="sfga_options[sfga_custom_code]" type="textarea" rows="4" cols="70"><?php if (isset($sfga_options['sfga_custom_code'])) echo esc_textarea($sfga_options['sfga_custom_code']); ?></textarea>
										<div class="sfga-caption"> 
											<?php esc_html_e('Any code entered here will be added to the GA code snippet. This is useful for things like creating', 'seo-friendly-ga'); ?> 
											<a target="_blank" rel="noopener noreferrer" href="https://developers.google.com/analytics/devguides/collection/analyticsjs/creating-trackers#working_with_multiple_trackers"><?php esc_html_e('multiple trackers', 'seo-friendly-ga'); ?></a> 
											<?php esc_html_e('and', 'seo-friendly-ga'); ?> 
											<a target="_blank" rel="noopener noreferrer" href="https://developers.google.com/analytics/devguides/collection/analyticsjs/user-opt-out"><?php esc_html_e('user opt-out', 'seo-friendly-ga'); ?></a>. 
											<?php esc_html_e('Note: you can use', 'seo-friendly-ga'); ?> <code>%%userid%%</code> <?php esc_html_e('to output the current user ID.', 'seo-friendly-ga'); ?>
										</div>
									</td>
								</tr>
								<tr>
									<th><label for="sfga_options[sfga_custom]"><?php esc_html_e('Custom Code', 'seo-friendly-ga'); ?></label></th>
									<td>
										<textarea id="sfga_options[sfga_custom]" name="sfga_options[sfga_custom]" type="textarea" rows="4" cols="70"><?php if (isset($sfga_options['sfga_custom'])) echo esc_textarea($sfga_options['sfga_custom']); ?></textarea>
										<div class="sfga-caption">
											<?php esc_html_e('Here you may specify any markup to be displayed in the', 'seo-friendly-ga'); ?> <code>&lt;head&gt;</code> 
											<?php esc_html_e('section (or in the footer, depending on the "Tracking Code Location" setting, above).', 'seo-friendly-ga'); ?>
										</div>
									</td>
								</tr>
								<tr>
									<th><label for="sfga_options[sfga_custom_loc]"><?php esc_html_e('Custom Code Location', 'seo-friendly-ga'); ?></label></th>
									<td>
										<input id="sfga_options[sfga_custom_loc]" name="sfga_options[sfga_custom_loc]" type="checkbox" value="1" <?php if (isset($sfga_options['sfga_custom_loc'])) checked('1', $sfga_options['sfga_custom_loc']); ?>> 
										<?php esc_html_e('Display Custom Code', 'seo-friendly-ga'); ?> <em><?php esc_html_e('before', 'seo-friendly-ga'); ?></em> 
										<?php esc_html_e('the GA tracking code (leave unchecked to display', 'seo-friendly-ga'); ?> <em><?php esc_html_e('after', 'seo-friendly-ga'); ?></em> <?php esc_html_e('the tracking code)', 'seo-friendly-ga'); ?>
									</td>
								</tr>
								<tr>
									<th><label for="sfga_options[admin_area]"><?php esc_html_e('Admin Area', 'seo-friendly-ga') ?></label></th>
									<td>
										<input id="sfga_options[admin_area]" name="sfga_options[admin_area]" type="checkbox" value="1" <?php if (isset($sfga_options['admin_area'])) checked('1', $sfga_options['admin_area']); ?>> 
										<?php esc_html_e('Enable tracking in WP Admin Area (adds tracking code only; to view stats log into your Google account)', 'seo-friendly-ga'); ?>
									</td>
								</tr>
								<tr>
									<th><label for="sfga_options[disable_admin]"><?php esc_html_e('Admin Users', 'seo-friendly-ga') ?></label></th>
									<td>
										<input id="sfga_options[disable_admin]" name="sfga_options[disable_admin]" type="checkbox" value="1" <?php if (isset($sfga_options['disable_admin'])) checked('1', $sfga_options['disable_admin']); ?>> 
										<?php esc_html_e('Disable tracking of Admin-level users', 'seo-friendly-ga') ?>
									</td>
								</tr>
								<tr>
									<th><label><?php esc_html_e('More Options', 'seo-friendly-ga') ?></label></th>
									<td class="ga-pro-info">&nbsp;</td>
								</tr>
							</table>
							
						</div>
						
						<input type="submit" class="button-primary" value="<?php esc_attr_e('Save Changes', 'seo-friendly-ga'); ?>" />
						
					</div>
					
				</div>
				
				<div id="sfga-panel-restore" class="postbox">
					
					<h2><?php esc_html_e('Restore Defaults', 'seo-friendly-ga'); ?></h2>
					
					<div class="toggle default-hidden">
						
						<p><?php esc_html_e('Click the link to restore the default plugin options.', 'seo-friendly-ga'); ?></p>
						
						<p><?php echo $this->callback_reset(); ?></p>
						
					</div>
					
				</div>
				
				<div id="sfga-panel-current" class="postbox">
					
					<h2><?php esc_html_e('Support Us', 'seo-friendly-ga'); ?></h2>
					
					<div class="toggle<?php if (isset($_GET['settings-updated'])) echo ' default-hidden'; ?>">
						
						<?php
							echo '<p>'.esc_html__('Thank you for using SEO Friendly GA plugin.', 'seo-friendly-ga').'</p>';
							echo '<p>'.esc_html__('Hours spent on development of this plugin. Many coffees are drunk, many nights passed without sleep.', 'seo-friendly-ga').'</p>';
							echo '<p>'.esc_html__('We will update this plugin with your help frequently when Google changes its search algorithm. So you don\'t need to worry about your website\'s SEO score.', 'seo-friendly-ga').'</p>';
							echo '<p>'.esc_html__('Would you mind to donate us to help further feature developments and updates? If yes, please go to the ', 'seo-friendly-ga').'<a href="https://mudimedia.com/wordpress-plugins/seo-friendly-google-analytics" target="_blank">plugin\'s page</a>'.esc_html__(' and click one of the donation buttons. Thank you!', 'seo-friendly-ga').'</p>';
						?>
						
					</div>
					
				</div>
				
			</div>
			
		</div>
		
	</form>
	
</div>
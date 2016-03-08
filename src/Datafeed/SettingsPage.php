<?php

class Datafeed_SettingsPage extends Datafeed_AbstractSubPage
{
public function render_settings_page()
	{
		$option_name = $this->settings_page_properties['option_name'];
		$option_group = $this->settings_page_properties['option_group'];
		$settings_data = $this->get_settings_data();
		?>
		<div class="wrap">
			<h2>Simplarity</h2>
			<p>This plugin is using the settings API.</p>
			<form method="post" action="options.php">
				<?php
				settings_fields( $this->plugin['settings_page_properties']['option_group']);
				?>
				<table class="form-table">
					<tr>
						<th><label for="textbox">Textbox:</label></th>
						<td>
							<input type="text" id="textbox"
							       name="<?php echo esc_attr( $option_name."[textbox]" ); ?>"
							       value="<?php echo esc_attr( $settings_data['textbox'] ); ?>" />
						</td>
					</tr>
				</table>
				<input type="submit" name="submit" id="submit" class="button button-primary" value="Save Options">
			</form>
		</div>
		<?php
	}

	public function get_default_settings_data()
	{
		$defaults = array();
		$defaults['textbox'] = '';

		return $defaults;
	}

	public function render_guide()
	{?>
		<div class="wrap">
			<h3 class="info"> Please refer to
				<a href="http://wiki.affiliatewindow.com/index.php/Downloading_A_Feed" target="_blank">
					http://wiki.affiliatewindow.com/index.php/Downloading_A_Feed
				</a>
			</h3>
			<h1>Shortcodes</h1>
			<ol>
				<li>[AWIN_DATA_FEED] - Default shortcode</li>
			</ol>
			<h2>Shortcode Options</h2>
			<ol>
				<li>title='any title in quote'</li>
				<li>no_of_product=any number</li>
				<li>keywords='comma separated value in quote'</li>
			</ol>
			<h2>Shortcode Examples</h2>
			<ol>
				<li>[AWIN_DATA_FEED title='hello world' no_of_product=2]</li>
				<li>[AWIN_DATA_FEED no_of_product=3 title='Iron Man vs Captain America' keywords='Iron Man, Captain America' ]</li>
			</ol>
		</div>

		<?php
	}
}

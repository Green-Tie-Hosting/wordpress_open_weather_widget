<?php
/***
 * Plugin Name: Open Weather Widget
 * Description: Uses the openweathermap.org API to display weather on your WordPress widget.
 * Plugin Author: Paul Stoute
 * Author URI: https://greentiehosting.com/
 * Version: 1.0
 */

 add_action( 'widgets_init', 'gth_weather_widget_init' );
 
function gth_weather_widget_init() {
    register_widget( 'gth_weather_widget' );
}
 
class gth_weather_widget extends WP_Widget
{
 
    public function __construct()
    {
        $widget_details = array(
            'classname' => 'gth_weather_widget',
            'description' => 'Add the local weather conditions for your city to a widgetized area.'
        );
 
        parent::__construct( 'gth_weather_widget', 'Weather Conditions', $widget_details );
 
    }
 
    public function form( $instance ) {
    
    if( !empty( $instance['city'] ) ) {
        $city = $instance['city'];
    }
	if( !empty( $instance['apikey'] ) ) {
        $apikey = $instance['apikey'];
    }
 
    ?>
 
    <p>
        <label for="<?php echo $this->get_field_name( 'apikey' ); ?>"><?php _e( 'API Key:' ); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id( 'apikey' ); ?>" name="<?php echo $this->get_field_name( 'apikey' ); ?>" type="text" value="<?php echo esc_attr( $apikey ); ?>" />
    </p>
 
    <p>
        <label for="<?php echo $this->get_field_name( 'city' ); ?>"><?php _e( 'CityID:' ); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id( 'city' ); ?>" name="<?php echo $this->get_field_name( 'city' ); ?>" type="text" value="<?php echo esc_attr( $city ); ?>" />
    </p>
	<p>
		<a href="http://openweathermap.org/current" target="_blank">Find your City ID here</a>
	</p>
    <div class='mfc-text'>
         
    </div>
 
    <?php
 
    echo $args['after_widget'];
    }
 
    public function update( $new_instance, $old_instance ) {  
		$instance = $old_instance;
		$instance['city'] = $new_instance['city'];
		$instance['apikey'] = $new_instance['apikey'];
        return $instance;
    }
 
    public function widget( $args, $instance ) {
		$cityID = $instance['city'];
		$apiKey = $instance['apikey'];
		$url = 'http://api.openweathermap.org/data/2.5/weather?id='.$cityID.'&units=imperial&lang=en&APPID='.$apikey;
		$json = file_get_contents($url);
		$data = json_decode($json,true);
		echo '
			<div id="weather_conditions" class="widget widget_text">
				<div class="heading">
					<h4 class="widget-title">'.$data['name'].' Weather</h4>
				</div>
				<div class="textwidget">
					<table width="100%">
						<thead>
							<tr>
								<td style="text-align:center">
									<img src="http://openweathermap.org/img/w/'.$data['weather']['0']['icon'].'.png"/>
								</td>
								<td style="text-align:center;font-size:1.5em;">
									'.$data['main']['temp'].'&#176;F
								</td>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>
									High Temp:
								</td>
								<td style="text-align:center;">
									'.$data['main']['temp_max'].'&#176;F
								</td>
							</tr>
							<tr>
								<td>
									Low Temp:
								</td>
								<td style="text-align:center;">
									'.$data['main']['temp_min'].'&#176;F
								</td>
							</tr>
							<tr>
								<td>
									Condition:
								</td>
								<td style="text-align:center;">
									'.$data['weather']['0']['main'].'
								</td>
							</tr>
							<tr>
								<td>
									Humidity:
								</td>
								<td style="text-align:center;">
									'.$data['main']['humidity'].' %
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		';
    }
 
}

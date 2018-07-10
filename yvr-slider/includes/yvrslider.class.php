<?php
if (!defined('ABSPATH')) die('No direct access.');

/**
 * Generic Slider super class. Extended by library specific classes.
 *
 * This class handles all slider related functionality, including saving settings and outputting
 * the slider HTML (front end and back end)
 */
class YVRSlider {

	public $id = 0; // slider ID
    public $identifier = 0; // unique identifier
    public $slides = array(); // slides belonging to this slider
    public $settings = array(); // slider settings

    /**
     * Constructor
     *
     * @param int   $id                 Slider ID
     * @param array $shortcode_settings Short code settings
     */
    public function __construct( $id, $shortcode_settings ) {
        $this->id = $id;
        $this->settings = array_merge( $shortcode_settings, $this->get_settings() );
        $this->identifier = 'yvrslider_' . $this->id;
        //$this->populate_slides();
    }


    /**
     * Get settings for the current slider
     *
     * @return array slider settings
     */
    private function get_settings() {
        $settings = get_post_meta( $this->id, 'yvr-slider_settings', true );

        if ( is_array( $settings ) &&
            isset( $settings['type'] ) &&
            in_array( $settings['type'], array( 'flex', 'coin', 'nivo', 'responsive' ) ) ) {
            return $settings;
        } else {
            return $this->get_default_parameters();
        }
    }


    /**
	 * Get the slider libary parameters, this lists all possible parameters and their
	 * default values. Slider subclasses override this and disable/rename parameters
	 * appropriately.
	 *
	 * @return string javascript options
	 */
	public function get_default_parameters() {
		$params = array(
			'type' => 'flex',
			'random' => false,
			'cssClass' => '',
			'printCss' => true,
			'printJs' => true,
			'width' => 700,
			'height' => 300,
			'spw' => 7,
			'sph' => 5,
			'delay' => 3000,
			'sDelay' => 30,
			'opacity' => 0.7,
			'titleSpeed' => 500,
			'effect' => 'random',
			'navigation' => true,
			'links' => true,
			'hoverPause' => true,
			'theme' => 'default',
			'direction' => 'horizontal',
			'reverse' => false,
			'animationSpeed' => 600,
			'prevText' => __('Previous', 'ml-slider'),
			'nextText' => __('Next', 'ml-slider'),
			'slices' => 15,
			'center' => false,
			'smartCrop' => true,
			'carouselMode' => false,
			'carouselMargin' => 5,
			'firstSlideFadeIn' => true,
			'easing' => 'linear',
			'autoPlay' => true,
			'thumb_width' => 150,
			'thumb_height' => 100,
			'fullWidth' => false,
			'noConflict' => true
		);
		return apply_filters('yvrslider_default_parameters', $params);
	}
}
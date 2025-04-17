<?php

// Preventing to direct access
defined( 'ABSPATH' ) OR die( 'Direct access not acceptable!' );

/**
* Add popup for frontend users
* @package WeCreativez/Public
* @since 1.2
*/
class WWS_Widget {

	public function __construct() {
		add_action( 'wp_footer', array( $this, 'display_popup' ) );
	}

	/**
	* Displaying widget on frontend
	* @since 1.2
	*/
	public function display_popup() {
		if ( true !== apply_filters( 'wws_display_widget_on_current_page', $this->disable_popup() ) ) {
			return;
		}

		$layout         = apply_filters( 'wws_current_layout', get_option( 'wws_layout' ) );
		$layout_path    = WWS_PLUGIN_PATH . 'templates/wws-template-' . intval( $layout ) . '.php';

		require_once apply_filters( 'wws_template_path', $layout_path );
	}

	/**
	* Display on page
	* @return bool
	* @since 1.2
	*/
	public function display_on_page() {
		global $post;

		// Not display on selected URL
		$urls       = get_option( 'wws_filter_by_url_exclude' );
		$urls_array = explode( PHP_EOL, $urls );

		if ( $urls && $urls_array ) {
			foreach( $urls_array as $url_a ) {
				if ( 1 === get_the_ID() ) {
					continue;
				}
				if ( get_the_ID() === url_to_postid( trim( $url_a ) ) ) {
					return false;
				}
			}
		}

		// Not display wws on selected page
		$page_ids = get_option( 'wws_filter_by_page_id_exclude', array() );
		if ( 0 !== count( $page_ids ) ) {
			$post_id = $post->ID;
			if ( in_array( $post_id, $page_ids) ) {
				return false;
			}
		}

		// display wws on selected page
		$page_ids = get_option( 'wws_filter_by_page_id_include', array() );
		if ( 0 !== count( $page_ids ) ) {
			$page_id = $post->ID;
			return in_array( $page_id, $page_ids );
		}

		// display wws on front page
		if ( is_front_page() == true && 'yes' === get_option( 'wws_filter_by_front_page' ) ) {
			return true;
		}

		// display wws on all pages and posts
		if ( 'yes' === get_option( 'wws_filter_by_everywhere' ) ) {
			return true;
		}

		return false;
	}

	public function disable_popup() {
		if ( true === wp_is_mobile() && 'yes' !== get_option( 'wws_display_on_mobile' ) ) {
			return false;
		}
		if ( ! wp_is_mobile() && 'yes' !== get_option( 'wws_display_on_desktop' ) ) {
			return false;
		}
		if ( true !== $this->display_on_page() ) {
			return false;
		}
		if ( true !== $this->is_schedule() ) {
			return false;
		}

		return true;
	}

	public function format_time_for_compare( $time ) {
		return intval( str_replace( ":", "", $time ) );
	}

	public function is_schedule() {
		$current_day    = strtolower( current_time( 'D' ) );
		$current_time   = (int)current_time( 'His' );
		$schedules      = get_option( 'wws_filter_by_schedule', array() );

		foreach( $schedules as $day => $schedule ) {
			if ( $current_day !== $day ) {
				continue;
			}
			if ( 'yes' !== $schedule['status'] ) {
				return false;
			}
			if ( ( $current_time > $this->format_time_for_compare( $schedule['start'] ) )
			&& ( $current_time < $this->format_time_for_compare( $schedule['end'] ) ) ) {
				return true;
			}
		}

		return false;
	}

} // .WWS_Widget

$wss_widget = new WWS_Widget;

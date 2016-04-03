<?php

class Flowplayer5_Plugin {

	public function run() {
		$this->register_common();
		if ( is_admin() ) {
			$this->register_backend();
		} else {
			$this->register_frontend();
		}
	}

	protected function register_common() {
		$settings = new Flowplayer5_Settings;
		add_action( 'admin_init', array( $settings, 'register_settings' ) );

		$sanitize_settings = new Flowplayer5_Sanitize_Settings( $settings->get_all() );
		add_filter( 'fp5_settings_sanitize_checkbox', array( $sanitize_settings, 'sanitize_checkbox' ), 10, 2 );
		add_filter( 'fp5_settings_sanitize_text', array( $sanitize_settings, 'sanitize_text' ), 10, 2 );
		add_filter( 'fp5_settings_sanitize_password', array( $sanitize_settings, 'sanitize_password' ), 10, 2 );
		add_filter( 'fp5_settings_sanitize_select', array( $sanitize_settings, 'sanitize_select' ), 10, 2 );
		add_filter( 'fp5_settings_sanitize_upload', array( $sanitize_settings, 'sanitize_upload' ), 10, 2 );
	}

	protected function register_backend() {
		$flowplayer_drive = new Flowplayer_Drive();
		add_action( 'plugins_loaded', array( $flowplayer_drive, 'run' ) );

		$taxonomy_meta = new Flowplayer5_Taxonomy_Meta();
		add_action( 'admin_head-edit-tags.php', array( $taxonomy_meta, 'remove_category_fields' ) );
		add_action( 'save_post', array( $taxonomy_meta, 'video_save' ), 10, 2 );
		add_action( 'edited_playlist', array( $taxonomy_meta, 'save_taxonomy_custom_meta' ), 10, 2 );
		add_action( 'create_playlist', array( $taxonomy_meta, 'save_taxonomy_custom_meta' ), 10, 2 );
		add_action( 'playlist_add_form_fields', array( $taxonomy_meta, 'taxonomy_add_new_meta_field' ), 10, 2 );
		add_action( 'delete_playlist', array( $taxonomy_meta, 'taxonomy_delete_meta_field' ), 10, 2 );
		add_action( 'playlist_edit_form_fields', array( $taxonomy_meta, 'taxonomy_edit_meta_field' ), 10, 2 );
		add_filter( 'manage_edit-playlist_columns', array( $taxonomy_meta, 'add_playlist_columns' ) );
		add_filter( 'manage_playlist_custom_column', array( $taxonomy_meta, 'add_playlist_column_content' ), 10, 3 );
		add_action( 'split_shared_term', array( $taxonomy_meta, 'update_playlist_order_for_split_terms' ), 10, 4 );
	}

	protected function register_frontend() {
		$frontend = new Flowplayer5_Frontend();
		// Filter video output to video post
		add_filter( 'the_content',  array( $frontend, 'get_video_output' ) );
		// Load script for Flowplayer global configuration
		add_action( 'wp_head', array( $frontend, 'global_config_script' ) );

		$shortcode = new Flowplayer5_Shortcode();
		add_action( 'init', array( $shortcode, 'register' ) );

		$styles_scripts = new Flowplayer5_Styles_Scripts();
		add_action( 'wp_enqueue_scripts', array( $styles_scripts, 'run' ) );
	}
}

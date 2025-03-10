<?php

if ( ! function_exists( 'codepopular_add_dashboard_widgets' ) ) {
	/**
	 * Add a widget to the dashboard.
	 *
	 * This function is hooked into the 'wp_dashboard_setup' action below.
	 */
	function codepopular_add_dashboard_widgets() {
		global $wp_meta_boxes;

		add_meta_box( 'codepopular_latest_news_dashboard_widget', __( 'CodePopular Latest News from Blog', 'unlimited-theme-addons' ), 'codepopular_dashboard_widget_render', 'dashboard', 'side', 'high' );

	}
	add_action( 'wp_dashboard_setup', 'codepopular_add_dashboard_widgets', 1 );
}

if ( ! function_exists( 'codepopular_dashboard_widget_render' ) ) {
	/**
	 * Function to get dashboard widget data.
	 */
	function codepopular_dashboard_widget_render() {

		// Enter the name of your blog here followed by /wp-json/wp/v2/posts and add filters like this one that limits the result to 2 posts.
		$response = wp_remote_get( 'https://codepopular.com/wp-json/wp/v2/posts?per_page=5&categories=19' );

		// Exit if error.
		if ( is_wp_error( $response ) ) {
			return;
		}

		// Get the body.
		$posts = json_decode( wp_remote_retrieve_body( $response ) );

		// Exit if nothing is returned.
		if ( empty( $posts ) ) {
			return;
		}
		?>

		<?php
		// If there are posts.
		if ( ! empty( $posts ) ) {
			// For each post.
			foreach ( $posts as $post ) {
				$fordate = gmdate( 'M j, Y', strtotime( $post->modified ) ); ?>
				<p class="codepopular-blog-feeds"> <a style="text-decoration: none;font-weight: bold" href="<?php echo esc_url( $post->link ); ?>?utm_source=unlimited-theme-addons" target=_balnk><?php echo esc_html( $post->title->rendered ); ?></a> - <?php echo esc_html( $fordate ); ?></p>
				<span><?php echo wp_trim_words( $post->content->rendered, 25, '...' );  //phpcs:ignore ?></span>
				<?php
			}
			?>
			<hr>
			<p> <a style="text-decoration: none;font-weight: bold" href="<?php echo esc_url( 'https://codepopular.com/blog/' ); ?>?utm_source=unlimited-theme-addons" target=_balnk><?php echo esc_html__( 'Get more WordPress tips & news on our blog...', 'unlimited-theme-addons' ); ?></a></p>
			<a style="text-decoration: none; font-weight: bold; color: #fff; border: 1px solid #ccc; padding: 6px 10px; border-radius: 4px; background: #39b54a; " href="<?php echo esc_url_raw('https://codepopular.com/contact?utm_source=wp_dashboard&utm_medium=blog_feed');?>?utm_source=unlimited-theme-addons" target="_balnk"><?php echo esc_html('Talk with WordPress Expert');?></a>
			<?php
		}
	}
}


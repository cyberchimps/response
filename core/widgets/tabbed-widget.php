<?php

add_action('widgets_init', 'response_tabbed_widget');
add_image_size('response-tabbed', 45, 45, true);

function response_tabbed_widget() {
	register_widget('Response_Tabbed_Widget');
}

class response_Tabbed_Widget extends WP_Widget {
	function __construct() {
		$widget_options = array(
			'classname' => 'response_tabbed_widget',
			'description' => __('A tabbed widget that display popular posts, recent posts, comments and tags.', 'response')
		);

		$control_options = array(
			'width' => 300,
			'height' => 350,
			'id_base' => 'response_tabbed_widget'
		);

		parent::__construct(
			'response_tabbed_widget',
			__('Response Tabbed Widget', 'response'),
			$widget_options,
			$control_options
		);
	}

	function widget($args, $instance) {
		global $wpdb;

		$tab1 = $instance['tab1'];
		$tab2 = $instance['tab2'];
		$tab3 = $instance['tab3'];
		$tab4 = $instance['tab4'];

		echo $args['before_widget'];

		$id = $args['widget_id']; // grab the widget ID for some custom css
		
		echo '<style type="text/css">';
		echo "#$id.widget-container {padding: 0px;}";
		echo "#$id.widget-container ul {padding-top: 10px; margin-bottom: -1px;}";
		echo "#$id.widget-container li {margin-left: 0px;}";
		echo '</style>';
	
?>
<div class="response-tabbed-widget">
	<div class="response-tabbed-wrap">
		<ul class="response-tabbed-header">
			<li class="first"><a href="#tab-1"><?php echo $instance['tab1'] ?></a></li>
			<li class=""><a href="#tab-2"><?php echo $instance['tab2'] ?></a></li>
			<li class=""><a href="#tab-3"><?php echo $instance['tab3'] ?></a></li>
			<li class="last"><a href="#tab-4"><?php echo $instance['tab4'] ?></a></li>
		</ul>
		<div class="response-tabbed-tab" id="tab-1">
			<ul class="response-tabbed-popular-posts">
				<?php
					$popular_posts = new WP_Query();
					$popular_posts->query(array('ignore_sticky_posts' => '1', 'posts_per_page' => 5, 'orderby' => 'comment_count'));
					while($popular_posts->have_posts()): $popular_posts->the_post();

					?>
						<li>
							<div class="tab-image">
								<a href="<?php the_permalink() ?>"><?php the_post_thumbnail("response-tabbed") ?>
							</div>

							<div class="details">
								<h5 class="tabbed-title"><a href="<?php the_permalink() ?>"><?php the_title() ?></a></h5>
								<span class="tabbed-date">
									<?php the_time(get_option('date_format')) ?>,
									<?php comments_popup_link(__('No comments', 'response'), __('1 Comment', 'response'), __('% Comments', 'response')); ?>
								</span>
							</div>
						</li>
					<?php
					endwhile;
				?>
			</ul>
		</div><!-- #tab-1 -->

		<div class="response-tabbed-tab" id="tab-2">
			<ul class="response-tabbed-recent-posts">
				<?php
					$recent_posts = new WP_Query();
					$recent_posts->query(array('ignore_sticky_posts' => '1', 'posts_per_page' => 5));
					while($recent_posts->have_posts()): $recent_posts->the_post();

					?>
						<li>
							<div class="tab-image" >
								<a href="<?php the_permalink() ?>"><?php the_post_thumbnail("response-tabbed") ?>
							</div>

							<div class="details">
								<h5 class="tabbed-title"><a href="<?php the_permalink() ?>"><?php the_title() ?></a></h5>
								<span class="tabbed-date">
									<?php the_time(get_option('date_format')) ?>,
									<?php comments_popup_link(__('No comments', 'response'), __('1 Comment', 'response'), __('% Comments', 'response')); ?>
								</span>
							</div>
						</li>
					<?php
					endwhile;
				?>
			</ul>
		</div><!-- #tab-2 -->

		<div class="response-tabbed-tab" id="tab-3">
			<ul class="response-tabbed-comments"> 
				<?php foreach(get_comments(array('number' => 5)) as $comment): ?>
					<?php $post = get_post($comment->comment_post_ID); ?>
					<li>
						<div class="tab-image">
						<a href="<?php echo get_permalink($post->ID); ?>#comment-<?php echo $comment->comment_ID; ?>" title="<?php echo strip_tags($comment->comment_author); ?> <?php _e('on ', 'response'); ?><?php echo $post->post_title; ?>"><?php echo get_avatar( $comment, '45' ); ?></a>
						</div>

						<div class="details">
						<h5 class="tabbed-title"><a href="<?php echo get_permalink($post->ID); ?>#comment-<?php echo $comment->comment_ID; ?>" title="<?php echo strip_tags($comment->comment_author); ?> <?php _e('on ', 'response'); ?><?php echo $post->post_title; ?>"><?php echo strip_tags($comment->comment_author); ?>: <?php echo substr(strip_tags($comment->comment_content), 0, 50); ?>...</a></h5>
						</div>
					</li>
				<?php endforeach; ?>
			</ul>
		</div><!-- #tab-3 -->

		<div class="response-tabbed-tab" id="tab-4">
			<?php wp_tag_cloud(array('largest' => 12, 'smallest' => 12, 'unix' => 'px')) ?>
		</div><!-- #tab-4 -->
	</div> <!-- .response-tabbed-wrap -->
</div> <!-- .response-tabbed-widget -->
</div> <!-- end tabbed widget container -->
	<?php
		wp_reset_query();
	}

	function update($new_instance, $old_instance) {
		$instance = $old_instance;

		

		$instance['tab1'] = $new_instance['tab1'];
		$instance['tab2'] = $new_instance['tab2'];
		$instance['tab3'] = $new_instance['tab3'];
		$instance['tab4'] = $new_instance['tab4'];
		
		return $instance;
	}

	function form($instance) {
		$defaults = array(
			
			'tab1' => 'Popular',
			'tab2' => 'Recent',
			'tab3' => 'Comments',
			'tab4' => 'Tags'
		);

		$instance = wp_parse_args((array) $instance, $defaults);
		?>
			

			<p>
				<label for="<?php echo $this->get_field_id( 'tab1' ); ?>"><?php _e('Popular Title:') ?></label>
				<input class="widefat" id="<?php echo $this->get_field_id( 'tab1' ); ?>" name="<?php echo $this->get_field_name( 'tab1' ); ?>" value="<?php echo $instance['tab1']; ?>" />
			</p>

			<p>
				<label for="<?php echo $this->get_field_id( 'tab2' ); ?>"><?php _e('Recent Title:') ?></label>
				<input class="widefat" id="<?php echo $this->get_field_id( 'tab2' ); ?>" name="<?php echo $this->get_field_name( 'tab2' ); ?>" value="<?php echo $instance['tab2']; ?>" />
			</p>

			<p>
				<label for="<?php echo $this->get_field_id( 'tab3' ); ?>"><?php _e('Comment Title:') ?></label>
				<input class="widefat" id="<?php echo $this->get_field_id( 'tab3' ); ?>" name="<?php echo $this->get_field_name( 'tab3' ); ?>" value="<?php echo $instance['tab3']; ?>" />
			</p>

			<p>
				<label for="<?php echo $this->get_field_id( 'tab4' ); ?>"><?php _e('Tag Title:') ?></label>
				<input class="widefat" id="<?php echo $this->get_field_id( 'tab4' ); ?>" name="<?php echo $this->get_field_name( 'tab4' ); ?>" value="<?php echo $instance['tab4']; ?>" />
			</p>
		<?php
	}
}

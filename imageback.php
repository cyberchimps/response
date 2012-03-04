<?php
?>
<?php get_header(); ?>

        <div id="content-images" class="grid col-620">
        
<?php if (have_posts()) : ?>

		<?php while (have_posts()) : the_post(); ?>
          
            <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <h1><?php the_title(); ?></h1>
                <p><?php _e('&#8249; Return to', 'shell'); ?> <a href="<?php echo get_permalink($post->post_parent); ?>" rel="gallery"><?php echo get_the_title($post->post_parent); ?></a></p>

                <div class="post-meta">
                <?php 
                    printf( __( '<span class="%1$s">Posted on</span> %2$s by %3$s', 'shell' ),'meta-prep meta-prep-author',
		            sprintf( '<a href="%1$s" title="%2$s" rel="bookmark">%3$s</a>',
			            get_permalink(),
			            esc_attr( get_the_time() ),
			            get_the_date()
		            ),
		            sprintf( '<span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s">%3$s</a></span>',
			            get_author_posts_url( get_the_author_meta( 'ID' ) ),
			        sprintf( esc_attr__( 'View all posts by %s', 'shell' ), get_the_author() ),
			            get_the_author()
		                )
			        );
		        ?>
				    <?php if ( comments_open() ) : ?>
                        <span class="comments-link">
                        <span class="mdash">&mdash;</span>
                    <?php comments_popup_link(__('No Comments &darr;', 'shell'), __('1 Comment &darr;', 'shell'), __('% Comments &darr;', 'shell')); ?>
                        </span>
                    <?php endif; ?> 
                </div><!-- end of .post-meta -->
                                
                <div class="attachment-entry">
                    <a href="<?php echo wp_get_attachment_url($post->ID); ?>"><?php echo wp_get_attachment_image( $post->ID, 'large' ); ?></a>
					<?php if ( !empty($post->post_excerpt) ) the_excerpt(); ?>
                    <?php the_content(__('See more &#8250;;', 'shell')); ?>
                    <?php wp_link_pages(array('before' => '<div class="pagination">' . __('Pages:', 'shell'), 'after' => '</div>')); ?>
                </div><!-- end of .post-entry -->

               <div class="navigation">
	               <div class="previous"><?php previous_image_link( 'thumbnail' ); ?></div>
			      <div class="next"><?php next_image_link( 'thumbnail' ); ?></div>
		       </div><!-- end of .navigation -->
                        
                <?php if ( comments_open() ) : ?>
                <div class="post-data">
				    <?php the_tags(__('Tagged with:', 'shell') . ' ', ', ', '<br />'); ?> 
                    <?php the_category(__('Posted in %s', 'shell') . ', '); ?> 
                </div><!-- end of .post-data -->
                <?php endif; ?>             

            <div class="post-edit"><?php edit_post_link(__('Edit', 'shell')); ?></div>             
            </div><!-- end of #post-<?php the_ID(); ?> -->
            
			<?php comments_template( '', true ); ?>
            
        <?php endwhile; ?>  

	    <?php else : ?>

        <h1 class="title-404"><?php _e('404 &#8212; Fancy meeting you here!', 'shell'); ?></h1>
        <p><?php _e('Don\'t panic, we\'ll get through this together. Let\'s explore our options here.', 'shell'); ?></p>
        <h6><?php _e( 'You can return', 'shell' ); ?> <a href="<?php echo home_url(); ?>/" title="<?php esc_attr_e( 'home', 'shell' ); ?>"><?php _e( '&#9166; Home', 'shell' ); ?></a> <?php _e( 'or search for the page you were looking for', 'shell' ); ?></h6>
        <?php get_search_form(); ?>

<?php endif; ?>  
      
        </div><!-- end of #content-image -->

<?php get_sidebar('gallery'); ?>
<?php get_footer(); ?>
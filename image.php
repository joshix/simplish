<?php get_header(); ?>

	<div id="content" class="widecolumn" role="main">

<?php if(have_posts()): while(have_posts()): the_post(); ?>

		<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<h2 class="entry-title"><a href="<?php echo get_permalink($post->post_parent); ?>" rev="attachment"><?php echo get_the_title($post->post_parent); ?></a> &raquo; <?php the_title(); ?></h2>

			<div class="posted">
				<?php _e('Posted by', 'simplish'); ?> <?php sp_byline_hcard() ?> &ndash; 
					<abbr class="published posted_date" title="<?php the_time('Y-m-d\TH:i:sP') ?>"><?php echo get_the_time(get_option('date_format')) ?></abbr>
			</div>
			<br class="clear" />

			<div class="entry">
				<p class="attachment"><a href="<?php echo wp_get_attachment_url($post->ID); ?>"><?php echo wp_get_attachment_image( $post->ID, 'large' ); ?></a></p>
                <div class="caption"><?php if(!empty($post->post_excerpt)) the_excerpt(); // this is the "caption" ?></div>

				<?php the_content(); ?>

				<div id="gallerynav" class="navigation">
					<div class="prevlink"><?php previous_image_link() ?></div>
					<div class="nextlink"><?php next_image_link() ?></div>
				</div>
				<br class="clear" />
			</div>
		</div>

	<?php comments_template(); ?>

	<?php endwhile; else: ?>

		<p><?php _e('Sorry, no attachments matched your criteria.', 'simplish'); ?></p>

<?php endif; ?>

	</div>
<?php get_sidebar(); ?>
<?php get_footer(); ?>

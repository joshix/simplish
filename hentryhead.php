<?php
/*
 * hentry^(head meta) includes are divided on these margins to allow
 * including from any of index/single/image/archive, while the entire
 * hentry is treated a little differently on each.
 */
?>
				<h2 class="entry-title"><a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a></h2>
				<div class="posted"><?php _e('Posted by', 'simplish'); ?> <?php sp_byline_hcard() ?>  &ndash; 
					<a href="<?php the_permalink() ?>" rel="bookmark">
						<abbr class="published posted_date" title="<?php the_time('Y-m-d\TH:i:sP') ?>"><?php echo get_the_time(get_option('date_format'))  /* Use get_the_time() to avoid the_date() output bug in this context. */ ?></abbr>
					</a>
				</div>

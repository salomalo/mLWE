<?php
global $post, $job_manager_bookmarks;
?>

<?php knowhere_job_listing_breadcrumbs(); ?>

<header class="kw-listing-single-header kw-type-4">

	<div class="container">

		<div class="kw-md-table-row kw-xs-small-offset row">

			<div class="col-sm-7">

				<h1 class="kw-listing-item-title" itemprop="name">

					<?php echo get_the_title(); ?>

				</h1>

				<ul class="kw-listing-item-data kw-icons-list kw-hr-type">

					<?php knowhere_job_listing_company($post); ?>

					<?php if ( get_the_job_location($post) ): ?>
						<li><span class="lnr icon-map-marker"></span><?php echo get_the_job_location($post); ?></li>
					<?php endif; ?>

					<li><span class="lnr icon-clock3"></span><?php the_job_publish_date($post); ?></li>

					<?php knowhere_job_listing_post_views() ?>

				</ul>

			</div>

			<div class="col-sm-5 kw-right-edge">

					<div class="kw-listing-item-price"><?php esc_html_e('Price', 'knowherepro') ?>:
						<strong><?php echo knowhere_get_invoice_price( $post->ID ) ?></strong>
					</div>

				<ul class="kw-listing-item-actions kw-icons-list kw-hr-type">

					<?php if ( $job_manager_bookmarks !== null && method_exists( $job_manager_bookmarks, 'bookmark_form' ) ) : ?>
						<li><?php echo $job_manager_bookmarks->bookmark_form(); ?></li>
					<?php endif; ?>

					<li><span class="lnr icon-share2"></span><a class="kw-share-popup-link" href="#kw-share-popup"><?php esc_html_e('Share', 'knowherepro') ?></a></li>
					<li><span class="lnr icon-printer"></span><a href="javascript:window.print()"><?php esc_html_e('Print', 'knowherepro') ?></a></li>
				</ul>

				<div id="kw-share-popup" class="kw-share-popup mfp-hide">
					<?php if ( function_exists('knowhere_job_single_share') ): ?>
						<?php knowhere_job_single_share(); ?>
					<?php endif; ?>
				</div>

			</div>

		</div>

	</div>

</header>
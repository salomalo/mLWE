
<?php
$email = get_post_meta( get_the_ID(), 'knowhere_agent_email', true );
$address = get_post_meta( get_the_ID(), 'knowhere_agent_address', true );
$position = get_post_meta( get_the_ID(), 'knowhere_agent_position', true );
$phone = get_post_meta( get_the_ID(), 'knowhere_agent_phone', true );
$facebook = get_post_meta( get_the_ID(), 'knowhere_agent_facebook', true );
$google_plus = get_post_meta( get_the_ID(), 'knowhere_agent_google_plus', true );
$twitter = get_post_meta( get_the_ID(), 'knowhere_agent_twitter', true );
$linkedin = get_post_meta( get_the_ID(), 'knowhere_agent_linkedin', true );
$pinterest = get_post_meta( get_the_ID(), 'knowhere_agent_pinterest', true );
$agency = get_post_meta( get_the_ID(), 'knowhere_agent_agencies', true );
$agency_title = get_the_title($agency);
$agency_permalink = get_the_permalink($agency);
?>

<header class="kw-listing-single-header kw-agent-single-header kw-type-3">

	<div class="container">

		<div class="kw-sm-table-row kw-xs-small-offset row">

			<div class="col-sm-9">

				<?php if ( has_post_thumbnail() ): ?>
					<div class="kw-agent-photo">
						<?php the_post_thumbnail( array(165, 165) ) ?>
					</div>
				<?php endif; ?>

				<div class="kw-agent-meta">

					<h1 class="kw-listing-item-title"><?php the_title() ?></h1>

					<ul class="kw-listing-item-data kw-icons-list kw-hr-type">

						<?php if ( !empty($address) ): ?>

							<li class="kw-agent-address"><?php echo sprintf('%s', $address); ?></li>

						<?php endif; ?>

						<?php if ( !empty($position) ): ?>
							<li class="kw-agent-position">

								<?php echo sprintf('%s', $position); ?>

								<?php if ( !empty($agency) && $agency > 0 ): ?>
									<?php esc_html_e( 'at', 'knowherepro' ) ?> <a href="<?php echo esc_url($agency_permalink) ?>"><?php echo esc_html($agency_title) ?></a>
								<?php endif; ?>

							</li>
						<?php endif; ?>

						<?php if ( !empty($phone) ): ?>
							<li><span class="lnr icon-telephone"></span><?php echo sprintf('%s', $phone); ?></li>
						<?php endif; ?>

					</ul><!--/ .kw-listing-item-data-->

					<ul class="kw-social-links">

						<?php if ( !empty($facebook) ): ?>
							<li><a target="_blank" href="<?php echo esc_url($facebook) ?>"><i class="fa fa-facebook"></i></a></li>
						<?php endif; ?>

						<?php if ( !empty($google_plus) ): ?>
							<li><a target="_blank" href="<?php echo esc_url($google_plus) ?>"><i class="fa fa-google-plus"></i></a></li>
						<?php endif; ?>

						<?php if ( !empty($twitter) ): ?>
							<li><a target="_blank" href="<?php echo esc_url($twitter) ?>"><i class="fa fa-twitter"></i></a></li>
						<?php endif; ?>

						<?php if ( !empty($linkedin) ): ?>
							<li><a target="_blank" href="<?php echo esc_url($linkedin) ?>"><i class="fa fa-linkedin"></i></a></li>
						<?php endif; ?>

						<?php if ( !empty($pinterest) ): ?>
							<li><a target="_blank" href="<?php echo esc_url($pinterest) ?>"><i class="fa fa-pinterest"></i></a></li>
						<?php endif; ?>

					</ul>

				</div><!--/ .kw-agent-meta-->

			</div>

			<div class="col-sm-3 kw-right-edge">

				<ul class="kw-listing-item-actions kw-icons-list kw-hr-type">
					<li><span class="lnr icon-share2"></span><a class="kw-share-popup-link" href="#kw-share-popup"><?php esc_html_e('Share', 'knowherepro') ?></a></li>
					<li><span class="lnr icon-printer"></span><a href="javascript:window.print()"><?php esc_html_e('Print', 'knowherepro') ?></a></li>
				</ul>

				<div class="kw-hr-btns-group">
					<div class="kw-group-item">

						<?php if ( is_email($email) ): ?>
							<a href="mailto:<?php echo antispambot($email, 1) ?>" class="kw-btn-medium kw-theme-color">
								<?php esc_html_e( 'Contact This Agent', 'knowherepro' ) ?>
							</a>
						<?php endif; ?>

					</div>
				</div>

				<div id="kw-share-popup" class="kw-share-popup mfp-hide">
					<?php if ( function_exists('knowhere_job_single_share') ): ?>
						<?php knowhere_job_single_share(); ?>
					<?php endif; ?>
				</div>

			</div>

		</div>

	</div><!--/ .container -->

</header>
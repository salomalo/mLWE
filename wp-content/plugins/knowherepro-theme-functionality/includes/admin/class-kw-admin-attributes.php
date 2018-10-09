<?php
/**
 * Attributes Page
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * KW_Admin_Attributes Class.
 */
class KW_Admin_Attributes {

	/**
	 * Handles output of the attributes page in admin.
	 *
	 * Shows the created attributes and lets you add new ones or edit existing ones.
	 * The added attributes are stored in the database and can be used for layered navigation.
	 */
	public static function output() {
		$result = '';
		$action = '';

		// Action to perform: add, edit, delete or none
		if ( ! empty( $_POST['add_new_attribute'] ) ) {
			$action = 'add';
		} elseif ( ! empty( $_POST['save_attribute'] ) && ! empty( $_GET['edit'] ) ) {
			$action = 'edit';
		} elseif ( ! empty( $_GET['delete'] ) ) {
			$action = 'delete';
		}

		switch ( $action ) {
			case 'add' :
				$result = self::process_add_attribute();
			break;
			case 'edit' :
				$result = self::process_edit_attribute();
			break;
			case 'delete' :
				$result = self::process_delete_attribute();
			break;
		}

		if ( is_wp_error( $result ) ) {
			echo '<div id="errors" class="error"><p>' . wp_kses_post( $result->get_error_message() ) . '</p></div>';
		}

		// Show admin interface
		if ( ! empty( $_GET['edit'] ) ) {
			self::edit_attribute();
		} else {
			self::add_attribute();
		}
	}

	/**
	 * Get and sanitize posted attribute data.
	 * @return array
	 */
	private static function get_posted_attribute() {
		$attribute = array(
			'attribute_label'   => isset( $_POST['attribute_label'] )   ? kw_clean( stripslashes( $_POST['attribute_label'] ) ) : '',
			'attribute_name'    => isset( $_POST['attribute_name'] )    ? kw_sanitize_taxonomy_name( stripslashes( $_POST['attribute_name'] ) ) : '',
			'attribute_type'    => isset( $_POST['attribute_type'] )    ? kw_clean( $_POST['attribute_type'] ) : 'select',
			'attribute_category' => isset( $_POST['attribute_category'] ) ? kw_clean( $_POST['attribute_category'] ) : array()
		);

		if ( empty( $attribute['attribute_type'] ) ) {
			$attribute['attribute_type'] = 'select';
		}
		if ( empty( $attribute['attribute_label'] ) ) {
			$attribute['attribute_label'] = ucfirst( $attribute['attribute_name'] );
		}
		if ( empty( $attribute['attribute_name'] ) ) {
			$attribute['attribute_name'] = kw_sanitize_taxonomy_name( $attribute['attribute_label'] );
		}
		if ( empty( $attribute['attribute_category'] ) ) {
			$attribute['attribute_category'] = array();
		}

		return $attribute;
	}

	/**
	 * Add an attribute.
	 *
	 * @return bool|WP_Error
	 */
	private static function process_add_attribute() {
		check_admin_referer( 'kw-add-new_attribute' );

		$attribute = self::get_posted_attribute();
		$args      = array(
			'name'         => $attribute['attribute_label'],
			'slug'         => $attribute['attribute_name'],
			'type'         => $attribute['attribute_type'],
			'category'     => $attribute['attribute_category']
		);

		$id = kw_create_attribute( $args );

		if ( is_wp_error( $id ) ) {
			return $id;
		}

		return true;
	}

	/**
	 * Edit an attribute.
	 *
	 * @return bool|WP_Error
	 */
	private static function process_edit_attribute() {
		$attribute_id = absint( $_GET['edit'] );
		check_admin_referer( 'kw-save-attribute_' . $attribute_id );

		$attribute = self::get_posted_attribute();
		$args      = array(
			'name'         => $attribute['attribute_label'],
			'slug'         => $attribute['attribute_name'],
			'type'         => $attribute['attribute_type'],
			'category'     => $attribute['attribute_category']
		);

		$id = kw_update_attribute( $attribute_id, $args );

		if ( is_wp_error( $id ) ) {
			return $id;
		}

		echo '<div class="updated"><p>' . __( 'Attribute updated successfully', 'knowherepro_app_textdomain' ) . '</p><p><a href="' . esc_url( admin_url( 'edit.php?post_type=job_listing&amp;page=job-manager-attributes' ) ) . '">' . __( 'Back to Attributes', 'knowherepro_app_textdomain' ) . '</a></p></div>';

		return true;
	}

	/**
	 * Delete an attribute.
	 *
	 * @return bool
	 */
	private static function process_delete_attribute() {
		$attribute_id = absint( $_GET['delete'] );
		check_admin_referer( 'kw-delete-attribute_' . $attribute_id );

		return kw_delete_attribute( $attribute_id );
	}

	/**
	 * Edit Attribute admin panel.
	 *
	 * Shows the interface for changing an attributes type between select and text.
	 */
	public static function edit_attribute() {
		global $wpdb;

		$edit = absint( $_GET['edit'] );

		$attribute_to_edit = $wpdb->get_row( "SELECT attribute_type, attribute_label, attribute_name, attribute_category FROM " . $wpdb->prefix . "kw_attribute_taxonomies WHERE attribute_id = '$edit'" );

		?>
		<div class="wrap woocommerce">
			<h1><?php _e( 'Edit attribute', 'knowherepro_app_textdomain' ) ?></h1>

			<?php

				if ( ! $attribute_to_edit ) {
					echo '<div id="woocommerce_errors" class="error"><p>' . __( 'Error: non-existing attribute ID.', 'knowherepro_app_textdomain' ) . '</p></div>';
				} else {
					$att_type    = $attribute_to_edit->attribute_type;
					$att_cat     = $attribute_to_edit->attribute_category;
					$att_label   = $attribute_to_edit->attribute_label;
					$att_name    = $attribute_to_edit->attribute_name;
				?>

				<form action="edit.php?post_type=job_listing&amp;page=job-manager-attributes&amp;edit=<?php echo absint( $edit ); ?>" method="post">
					<table class="form-table">
						<tbody>

							<tr class="form-field form-required">
								<th scope="row" valign="top">
									<label for="attribute_label"><?php _e( 'Name', 'knowherepro_app_textdomain' ); ?></label>
								</th>
								<td>
									<input name="attribute_label" id="attribute_label" type="text" value="<?php echo esc_attr( $att_label ); ?>" />
									<p class="description"><?php _e( 'Name for the attribute (shown on the front-end).', 'knowherepro_app_textdomain' ); ?></p>
								</td>
							</tr>
							<tr class="form-field form-required">
								<th scope="row" valign="top">
									<label for="attribute_name"><?php _e( 'Slug', 'knowherepro_app_textdomain' ); ?></label>
								</th>
								<td>
									<input name="attribute_name" id="attribute_name" type="text" value="<?php echo esc_attr( $att_name ); ?>" maxlength="28" />
									<p class="description"><?php _e( 'Unique slug/reference for the attribute; must be no more than 28 characters.', 'knowherepro_app_textdomain' ); ?></p>
								</td>
							</tr>
							<tr class="form-field form-required">
								<th scope="row" valign="top">
									<label for="attribute_type"><?php _e( 'Type', 'knowherepro_app_textdomain' ); ?></label>
								</th>
								<td>
									<select name="attribute_type" id="attribute_type">

										<?php foreach ( kw_get_attribute_types() as $key => $value ) : ?>
											<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $att_type, $key ); ?>><?php echo esc_attr( $value ); ?></option>
										<?php endforeach; ?>

									</select>
									<p class="description"><?php _e( '<strong>Select</strong> allows pre-configured terms in a drop-down list.', 'knowherepro_app_textdomain' ); ?></p>
								</td>
							</tr>
							<tr class="form-field form-required">
								<th scope="row" valign="top">
									<label for="attribute_category"><?php _e( 'Categories', 'knowherepro_app_textdomain' ); ?></label>
								</th>
								<td>
									<select name="attribute_category[]" multiple id="attribute_category">

										<?php foreach ( kw_get_attribute_categories() as $key => $value ) : ?>

											<?php
												$selected_cats = explode( ', ', $att_cat );
												$selected = in_array( $key, $selected_cats ) ? ' selected="selected" ' : '';
											?>

											<option value="<?php echo esc_attr( $key ); ?>" <?php echo $selected; ?>><?php echo esc_attr( $value ); ?></option>
										<?php endforeach; ?>

									</select>
									<p class="description"><?php _e( '<strong>Select</strong> listing category.', 'knowherepro_app_textdomain' ); ?></p>
								</td>
							</tr>

						</tbody>
					</table>
					<p class="submit"><input type="submit" name="save_attribute" id="submit" class="button-primary" value="<?php esc_attr_e( 'Update', 'knowherepro_app_textdomain' ); ?>"></p>
					<?php wp_nonce_field( 'kw-save-attribute_' . $edit ); ?>
				</form>
			<?php } ?>
		</div>
		<?php
	}

	/**
	 * Add Attribute admin panel.
	 *
	 * Shows the interface for adding new attributes.
	 */
	public static function add_attribute() {
		?>

		<div class="wrap">
			<h1><?php echo get_admin_page_title(); ?></h1>

			<br class="clear" />
			<div id="col-container">
				<div id="col-right">
					<div class="col-wrap">
						<table class="widefat attributes-table wp-list-table ui-sortable" style="width:100%">
							<thead>
								<tr>
									<th scope="col"><?php _e( 'Name', 'knowherepro_app_textdomain' ); ?></th>
									<th scope="col"><?php _e( 'Slug', 'knowherepro_app_textdomain' ); ?></th>
									<th scope="col"><?php _e( 'Type', 'knowherepro_app_textdomain' ); ?></th>
									<th scope="col"><?php _e( 'Terms', 'knowherepro_app_textdomain' ); ?></th>
									<th scope="col"><?php _e( 'Category', 'knowherepro_app_textdomain' ); ?></th>
								</tr>
							</thead>

							<tbody>
								<?php
									if ( $attribute_taxonomies = kw_get_attribute_taxonomies() ) :
										foreach ( $attribute_taxonomies as $tax ) :
											?><tr>
												<td>
													<strong><a href="edit-tags.php?taxonomy=<?php echo esc_html( kw_attribute_taxonomy_name( $tax->attribute_name ) ); ?>&amp;post_type=job_listing"><?php echo esc_html( $tax->attribute_label ); ?></a></strong>

													<div class="row-actions"><span class="edit"><a href="<?php echo esc_url( add_query_arg( 'edit', $tax->attribute_id, 'edit.php?post_type=job_listing&amp;page=job-manager-attributes' ) ); ?>"><?php _e( 'Edit', 'knowherepro_app_textdomain' ); ?></a> | </span><span class="delete"><a class="delete" href="<?php echo esc_url( wp_nonce_url( add_query_arg( 'delete', $tax->attribute_id, 'edit.php?post_type=job_listing&amp;page=job-manager-attributes' ), 'kw-delete-attribute_' . $tax->attribute_id ) ); ?>"><?php _e( 'Delete', 'knowherepro_app_textdomain' ); ?></a></span></div>
												</td>
												<td><?php echo esc_html( $tax->attribute_name ); ?></td>
												<td><?php echo esc_html( kw_get_attribute_type_label( $tax->attribute_type ) ); ?></td>
												<td class="attribute-terms"><?php
													$taxonomy = kw_attribute_taxonomy_name( $tax->attribute_name );

													if ( taxonomy_exists( $taxonomy ) ) {

														$terms = get_terms( $taxonomy, 'hide_empty=0&menu_order=false' );

//														if ( 'menu_order' === kw_attribute_orderby( $taxonomy ) ) {
//															$terms = get_terms( $taxonomy, 'hide_empty=0&menu_order=ASC' );
//														} else {
//															$terms = get_terms( $taxonomy, 'hide_empty=0&menu_order=false' );
//														}

														$terms_string = implode( ', ', wp_list_pluck( $terms, 'name' ) );
														if ( $terms_string ) {
															echo $terms_string;
														} else {
															echo '<span class="na">&ndash;</span>';
														}
													} else {
														echo '<span class="na">&ndash;</span>';
													}

												?>
												<br /><a href="edit-tags.php?taxonomy=<?php echo esc_html( kw_attribute_taxonomy_name( $tax->attribute_name ) ); ?>&amp;post_type=job_listing" class="configure-terms"><?php _e( 'Configure terms', 'knowherepro_app_textdomain' ); ?></a>
												</td>
												<td>
													<?php echo esc_html( kw_get_attribute_category_label( $tax->attribute_category ) ) ?>
												</td>
											</tr><?php
										endforeach;
									else :
										?><tr><td colspan="6"><?php _e( 'No attributes currently exist.', 'knowherepro_app_textdomain' ) ?></td></tr><?php
									endif;
								?>
							</tbody>

						</table>
					</div>
				</div>

				<div id="col-left">
					<div class="col-wrap">
						<div class="form-wrap">

							<h2><?php _e( 'Add new attribute', 'knowherepro_app_textdomain' ); ?></h2>
							<p><?php _e( 'Please note: you cannot rename an attribute later on.', 'knowherepro_app_textdomain' ); ?></p>

							<form action="edit.php?post_type=job_listing&amp;page=job-manager-attributes" method="post">

								<div class="form-field">
									<label for="attribute_label"><?php _e( 'Name', 'knowherepro_app_textdomain' ); ?></label>
									<input name="attribute_label" id="attribute_label" type="text" value="" />
									<p class="description"><?php _e( 'Name for the attribute (shown on the front-end).', 'knowherepro_app_textdomain' ); ?></p>
								</div>

								<div class="form-field">
									<label for="attribute_name"><?php _e( 'Slug', 'knowherepro_app_textdomain' ); ?></label>
									<input name="attribute_name" id="attribute_name" type="text" value="" maxlength="28" />
									<p class="description"><?php _e( 'Unique slug/reference for the attribute; must be no more than 28 characters.', 'knowherepro_app_textdomain' ); ?></p>
								</div>

								<div class="form-field">
									<label for="attribute_type"><?php _e( 'Type', 'knowherepro_app_textdomain' ); ?></label>
									<select name="attribute_type" id="attribute_type">
										<?php foreach ( kw_get_attribute_types() as $key => $value ) : ?>
											<option value="<?php echo esc_attr( $key ); ?>"><?php echo esc_attr( $value ); ?></option>
										<?php endforeach; ?>
									</select>
									<p class="description"><?php _e( '<strong>Select</strong> allows pre-configured terms in a drop-down list.', 'knowherepro_app_textdomain' ); ?></p>
								</div>

								<div class="form-field">
									<label for="attribute_category"><?php _e( 'Category', 'knowherepro_app_textdomain' ); ?></label>
									<select name="attribute_category[]" multiple id="attribute_category">
										<?php foreach ( kw_get_attribute_categories() as $key => $value ) : ?>
											<option value="<?php echo esc_attr( $key ); ?>"><?php echo esc_attr( $value ); ?></option>
										<?php endforeach; ?>
									</select>
									<p class="description"><?php _e( '<strong>Select</strong> listing category.', 'knowherepro_app_textdomain' ); ?></p>
								</div>

								<p class="submit"><input type="submit" name="add_new_attribute" id="submit" class="button button-primary" value="<?php esc_attr_e( 'Add attribute', 'knowherepro_app_textdomain' ); ?>"></p>

								<?php wp_nonce_field( 'kw-add-new_attribute' ); ?>

							</form>
						</div>
					</div>
				</div>
			</div>
			<script type="text/javascript">
			/* <![CDATA[ */

				jQuery( 'a.delete' ).click( function() {
					if ( window.confirm( '<?php _e( "Are you sure you want to delete this attribute?", 'knowherepro_app_textdomain' ); ?>' ) ) {
						return true;
					}
					return false;
				});

			/* ]]> */
			</script>
		</div>
		<?php
	}
}
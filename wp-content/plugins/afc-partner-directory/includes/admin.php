<?php
/**
 * wp-admin meta boxes, partner field UI, and metadata save handling.
 *
 * @package AFC_Partner_Directory
 */

defined( 'ABSPATH' ) || exit;

/**
 * Registers partner meta boxes.
 */
function afc_partner_directory_register_meta_boxes(): void {
	add_meta_box(
		'afc-partner-details',
		__( 'Partner Details', 'afc-partner-directory' ),
		'afc_partner_directory_render_partner_meta_box',
		'partner',
		'normal',
		'default'
	);
}

/**
 * Renders the Partner Details meta box fields.
 *
 * @param WP_Post $post Current partner post.
 */
function afc_partner_directory_render_partner_meta_box( WP_Post $post ): void {
	wp_nonce_field(
		'afc_partner_directory_save_partner_meta',
		'afc_partner_directory_partner_nonce'
	);

	$website_url = get_post_meta( $post->ID, AFC_PARTNER_META_WEBSITE_URL, true );
	$category    = get_post_meta( $post->ID, AFC_PARTNER_META_CATEGORY, true );
	$logo_id     = (int) get_post_meta( $post->ID, AFC_PARTNER_META_LOGO_ID, true );
	$logo_url    = $logo_id > 0 ? wp_get_attachment_image_url( $logo_id, 'medium' ) : '';
	?>
	<div class="afc-partner-details-metabox">
		<p>
			<label for="afc_partner_website_url">
				<strong><?php esc_html_e( 'Website URL', 'afc-partner-directory' ); ?></strong>
			</label><br />
			<input
				type="url"
				id="afc_partner_website_url"
				name="afc_partner_website_url"
				value="<?php echo esc_url( $website_url ); ?>"
				class="widefat"
				placeholder="https://afcscholarshipfund.org/"
			/>
		</p>

		<p>
			<label for="afc_partner_category">
				<strong><?php esc_html_e( 'Category', 'afc-partner-directory' ); ?></strong>
			</label><br />
			<input
				type="text"
				id="afc_partner_category"
				name="afc_partner_category"
				value="<?php echo esc_attr( $category ); ?>"
				class="widefat"
			/>
		</p>

		<fieldset class="afc-partner-logo-field">
			<legend><strong><?php esc_html_e( 'Logo', 'afc-partner-directory' ); ?></strong></legend>
			<input type="hidden" id="afc_partner_logo_id" name="afc_partner_logo_id" value="<?php echo esc_attr( (string) $logo_id ); ?>" />
			<div class="afc-partner-logo-preview-wrap">
				<img
					id="afc-partner-logo-preview"
					src="<?php echo esc_url( $logo_url ? $logo_url : '' ); ?>"
					alt=""
					style="max-width: 200px; height: auto;<?php echo $logo_url ? '' : ' display: none;'; ?>"
				/>
			</div>
			<p class="afc-partner-logo-actions">
				<button type="button" class="button" id="afc-partner-select-logo">
					<?php esc_html_e( 'Select logo', 'afc-partner-directory' ); ?>
				</button>
				<button
					type="button"
					class="button"
					id="afc-partner-remove-logo"
					style="<?php echo $logo_id > 0 ? '' : 'display: none;'; ?>"
				>
					<?php esc_html_e( 'Remove logo', 'afc-partner-directory' ); ?>
				</button>
			</p>
			<p class="description">
				<?php esc_html_e( 'Choose an image from the Media Library. The attachment ID is stored for use in listings and the REST API.', 'afc-partner-directory' ); ?>
			</p>
		</fieldset>
	</div>
	<?php
}

/**
 * Enqueues admin scripts for the partner post edit screen.
 *
 * @param string $hook_suffix Current admin page hook.
 */
function afc_partner_directory_enqueue_admin_assets( string $hook_suffix ): void {
	if ( ! in_array( $hook_suffix, array( 'post.php', 'post-new.php' ), true ) ) {
		return;
	}

	$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;
	if ( ! $screen || 'partner' !== $screen->post_type ) {
		return;
	}

	wp_enqueue_media();

	wp_enqueue_style(
		'afc-partner-directory-admin',
		afc_partner_directory_url( 'assets/css/admin.css' ),
		array(),
		AFC_PARTNER_DIRECTORY_VERSION
	);

	wp_enqueue_script(
		'afc-partner-directory-admin',
		afc_partner_directory_url( 'assets/js/admin.js' ),
		array( 'jquery' ),
		AFC_PARTNER_DIRECTORY_VERSION,
		true
	);

	wp_localize_script(
		'afc-partner-directory-admin',
		'afcPartnerAdmin',
		array(
			'selectLogoTitle'  => __( 'Select partner logo', 'afc-partner-directory' ),
			'selectLogoButton' => __( 'Use this image', 'afc-partner-directory' ),
		)
	);
}

/**
 * Saves partner metadata when a partner post is updated.
 *
 * @param int $post_id Post ID being saved.
 */
function afc_partner_directory_save_partner_meta( int $post_id ): void {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( wp_is_post_revision( $post_id ) ) {
		return;
	}

	if ( 'partner' !== get_post_type( $post_id ) ) {
		return;
	}

	if ( ! isset( $_POST['afc_partner_directory_partner_nonce'] ) ) {
		return;
	}

	$nonce = sanitize_text_field( wp_unslash( $_POST['afc_partner_directory_partner_nonce'] ) );
	if ( ! wp_verify_nonce( $nonce, 'afc_partner_directory_save_partner_meta' ) ) {
		return;
	}

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	if ( isset( $_POST['afc_partner_website_url'] ) ) {
		$website_url = esc_url_raw( wp_unslash( $_POST['afc_partner_website_url'] ) );
		update_post_meta( $post_id, AFC_PARTNER_META_WEBSITE_URL, $website_url );
	}

	if ( isset( $_POST['afc_partner_category'] ) ) {
		$category = sanitize_text_field( wp_unslash( $_POST['afc_partner_category'] ) );
		update_post_meta( $post_id, AFC_PARTNER_META_CATEGORY, $category );
	}

	if ( isset( $_POST['afc_partner_logo_id'] ) ) {
		$logo_id = absint( wp_unslash( $_POST['afc_partner_logo_id'] ) );
		if ( $logo_id > 0 && 'attachment' !== get_post_type( $logo_id ) ) {
			$logo_id = 0;
		}
		update_post_meta( $post_id, AFC_PARTNER_META_LOGO_ID, $logo_id );
	}
}

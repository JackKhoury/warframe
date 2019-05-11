<?php
/**
 * Plugin updater
 *
 * @since      1.0.0
 *
 * @package    Shopify_Buybuttonwp
 * @subpackage Shopify_Buybuttonwp/includes
 */
class Shopify_Buybuttonwp_Updater extends Themify_Plugin_Updater {
	/**
	 * Constructor
	 * 
	 * @since 1.0.0
	 * @access public
	 * @param array $args
	 */
	public function __construct( $args = array() ) {
		parent::__construct( $args );

		if ( 'index.php' == basename( $_SERVER['PHP_SELF'] ) ) 
			add_action( 'admin_notices', array( $this, 'check_version' ), 3 );
	}

	/**
	 * Check plugin update.
	 * 
	 * @since 1.0.0
	 * @access public
	 */
	public function check_version() {
		$notifications = '';
		$version = $this->args['plugin_data']['Version'];

		// Check update transient
		$current = get_transient( $this->args['nicename'] . '_check_update'); // get last check transient
		$timeout = 60;
		$time_not_changed = isset( $current->lastChecked ) && $timeout > ( time() - $current->lastChecked );
		$newUpdate = get_transient( $this->args['nicename'] . '_new_update'); // get new update transient

		if ( is_object( $newUpdate ) && $time_not_changed ) {
			if ( version_compare( $version, $newUpdate->version, '<') ) {
				$notifications .= sprintf( __('<p class="update">%s version %s is now available. <a href="%s" class="%s">Update now</a> or view the <a href="https://themify.me/changelogs/%s.txt" class="themify-changelogs" target="_blank" data-changelog="https://themify.me/changelogs/%s.txt">change log</a> for details.</p>', 'themify'),
					$this->args['plugin_data']['Name'],
					$newUpdate->version,
					add_query_arg( array( 'page' => $this->args['nicename'] . '_update', 'action' => 'upgrade' ), admin_url( 'admin.php' ) ),
					$this->args['name'] . '-upgrade-plugin themify-upgrade-plugin',
					$this->args['name'],
					$this->args['name']
				);
				echo '<div class="notice error">'. $notifications . '</div>';
			}
			return;
		}

		// get remote version
		$remote_version = $this->get_remote_plugin_version( $this->args['name'] );

		// delete update checker transient
		delete_transient(  $this->args['nicename'] . '_check_update' );

		$new = new stdClass();
		$new->version = $remote_version;

		if ( version_compare( $version, $remote_version, '<' ) ) {
			set_transient(  $this->args['nicename'] . '_new_update', $new );
			$notifications .= '<div class="notice error">';
			$notifications .= sprintf( __('<p class="update">%s version %s is now available. <a href="%s" class="%s">Update now</a> or view the <a href="https://themify.me/changelogs/%s.txt" class="themify-changelogs" target="_blank" data-changelog="https://themify.me/changelogs/%s.txt">change log</a> for details.</p>', 'themify'),
				$this->args['plugin_data']['Name'],
				$new->version,
				add_query_arg( array( 'page' => $this->args['nicename'] . '_update', 'action' => 'upgrade' ), admin_url( 'admin.php' ) ),
				$this->args['name'] . '-upgrade-plugin themify-upgrade-plugin',
				$this->args['name'],
				$this->args['name']
			);
			$notifications .= '</div>';
		} else {
			if ( isset( $_GET['page'] ) && $this->args['admin_page'] == $_GET['page'] ) {
				$notifications .= sprintf( '<div class="notice updated"><p>%s</p></div>', esc_html__( 'You have the latest version of Shopify Buy Button Plugin. Yay!', 'themify' ) );
			}
		}

		// update transient
		$this->set_update();

		wp_enqueue_style( 'themify-plugin-updater' );
		wp_enqueue_script( 'themify-plugin-updater' );
		echo $notifications;
	}
}
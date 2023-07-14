<?php
/*
Template Name: Super Admin Dashboard
*/

// Check if the user is logged in
if ( is_user_logged_in() ) {
	// Get the current user object
	$current_user = wp_get_current_user();

	// Check if the logout form is submitted
	if ( isset( $_POST['super_admin_logout'] ) ) {
		// Verify the logout nonce
		if ( wp_verify_nonce( $_POST['super_admin_logout_nonce'], 'super_admin_logout' ) ) {
			// Perform the logout
			wp_logout();

			// Destroy the session
			session_destroy();

			// Redirect to the login page
			wp_redirect( home_url( '/super-admin-login/' ) );
			exit;
		}
	}
	?>

    <h1>Welcome, <?php echo esc_html( $current_user->user_login ); ?>!</h1>

    <!-- Add your dashboard content here -->

    <form method="post" action="">
		<?php wp_nonce_field( 'super_admin_logout', 'super_admin_logout_nonce' ); ?>
        <input type="hidden" name="super_admin_logout" value="1">
        <input type="submit" value="Log Out">
    </form>

	<?php
} else {
	// Redirect to the login page
	wp_redirect( home_url( '/super-admin-login/' ) );
	exit;
}
?>

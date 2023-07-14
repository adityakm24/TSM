<?php
/*
Template Name: Super Admin Login
*/

// Check if the form is submitted
if ( isset( $_POST['super_admin_login'] ) ) {
	// Sanitize and validate form inputs
	$username = sanitize_user( $_POST['username'] );
	$password = wp_strip_all_tags( $_POST['password'] );

	global $wpdb;
	$table_name = 'tsm_super_admin';

	// Check if the entered username exists in the super admin table
	$user = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $table_name WHERE username = %s", $username ) );

	if ( $user ) {
		// Verify the entered password against the stored hashed password
		if ( password_verify( $password, $user->password ) ) {
			// Login successful, redirect to the super admin dashboard
			wp_set_auth_cookie( $user->id, false );
			wp_redirect( home_url( '/super-admin-dashboard/' ) );
			exit;
		}
	}

	// Invalid username or password
	echo '<div class="error-message">Invalid username or password.</div>';
}


?>

<!-- HTML code for the login form -->
<form method="post" action="<?php echo esc_url( home_url( '/super-admin-login/' ) ); ?>">
    <label for="username">Username:</label>
    <input type="text" name="username" id="username" required><br>

    <label for="password">Password:</label>
    <input type="password" name="password" id="password" required><br>

    <input type="hidden" name="super_admin_login" value="1">
    <input type="submit" value="Log In">
</form>

<p>Don't have an account? Signup <a href="<?php echo esc_url( home_url( '/super-admin-signup/' ) ); ?>">here</a></p>

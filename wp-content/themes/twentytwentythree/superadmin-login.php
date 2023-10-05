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

<style>
    body {
        font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f1f1f1;
    }

    .login-box {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
    }

    .login-form {
        max-width: 360px;
        padding: 30px;
        background-color: #fff;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        border-radius: 5px;
    }

    label {
        display: block;
        font-weight: bold;
        margin-bottom: 5px;
        color: #333;
    }

    input[type="text"],
    input[type="password"] {
        width: 100%;
        padding: 12px;
        margin-bottom: 20px;
        border: 1px solid #ccc;
        border-radius: 3px;
    }

    input[type="submit"] {
        background-color: #0078D4;
        color: #fff;
        padding: 12px 20px;
        border: none;
        cursor: pointer;
        border-radius: 3px;
    }

    input[type="submit"]:hover {
        background-color: #005a9e;
    }

    .error-message {
        color: red;
        margin-bottom: 15px;
    }

    p {
        margin-top: 20px;
        text-align: center;
    }
</style>

<div class="login-box">
    <div class="login-form">
        <form method="post" action="<?php echo esc_url( home_url( '/super-admin-login/' ) ); ?>">
            <label for="username">Username:</label>
            <input type="text" name="username" id="username" required>

            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required>

            <input type="hidden" name="super_admin_login" value="1">
            <input type="submit" value="Log In">
        </form>

        <p>Don't have an account? Signup <a href="<?php echo esc_url( home_url( '/super-admin-signup/' ) ); ?>">here</a></p>
    </div>
</div>

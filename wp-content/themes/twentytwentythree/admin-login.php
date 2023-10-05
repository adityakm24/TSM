<?php
/*
Template Name: Admin Login
*/

// Check if the form is submitted
if ( isset( $_POST['admin_login'] ) ) {
    // Sanitize and validate form inputs
    $mail_id = sanitize_user( $_POST['mail_id'] );
    $password = wp_strip_all_tags( $_POST['password'] );

    global $wpdb;
    $table_name = 'tsm_school';

    // Check if the entered mail_id exists in the admin table
    $admin = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $table_name WHERE mail_id = %s", $mail_id ) );

    if ( $admin ) {
        // Verify the entered password against the stored hashed password
        if ( password_verify( $password, $admin->password ) ) {
            // Login successful, set cookies and redirect
            wp_set_auth_cookie( $admin->id, false );

            setcookie( 'id', $admin->id, time() + 3600, '/' );
            setcookie( 'admin_id', $admin->admin_id, time() + 3600, '/' );
            setcookie( 'school_name', $admin->school_name, time() + 3600, '/' );
            setcookie( 'mail_id', $admin->mail_id, time() + 3600, '/' );

            wp_redirect( home_url( '/admin-dashboard/' ) );
            exit;
        }
    }

    // Invalid mail_id or password
    echo '<div class="error-message">Invalid Email or Password.</div>';
}
?>

<!-- Rest of the HTML code for the login form -->




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
<h1>Admin Login</h1>
<div class="login-box">

	<div class="login-form">
		<form method="post" action="<?php echo esc_url( home_url( '/admin-login/' ) ); ?>">
			<label for="mail_id">Email:</label>
			<input type="text" name="mail_id" id="mail_id" required>

			<label for="password">Password:</label>
			<input type="password" name="password" id="password" required>

			<input type="hidden" name="admin_login" value="1">
			<input type="submit" value="Log In">
		</form>

	</div>
</div>

<?php
/*
Template Name: Super Admin Signup
*/

// Check if the form is submitted
if ( isset( $_POST['super_admin_signup'] ) ) {
    // Sanitize and validate form inputs
    $username = sanitize_user( $_POST['username'] );
    $password = wp_strip_all_tags( $_POST['password'] );
    $email = sanitize_email( $_POST['email'] );

    $errors = array();

    if ( empty( $username ) ) {
        $errors[] = 'Username is required.';
    }

    if ( empty( $password ) ) {
        $errors[] = 'Password is required.';
    } elseif ( ! is_strong_password( $password ) ) {
        $errors[] = 'Password must contain at least 8 characters, including uppercase letters, lowercase letters, numbers, and special characters.';
    }

    if ( empty( $email ) || ! is_email( $email ) ) {
        $errors[] = 'Please enter a valid email address.';
    }

    // Check if username or email is already taken
    global $wpdb;
    $table_name = 'tsm_super_admin';

    $existing_user = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $table_name WHERE username = %s OR email = %s", $username, $email ) );

    if ( $existing_user ) {
        if ( $existing_user->username === $username ) {
            $errors[] = 'Username is already taken.';
        }

        if ( $existing_user->email === $email ) {
            $errors[] = 'Email is already taken.';
        }
    }

    // Display error messages or proceed with data insertion
    if ( ! empty( $errors ) ) {
        foreach ( $errors as $error ) {
            echo '<div class="error-message">' . esc_html( $error ) . '</div>';
        }
    } else {
        // Continue with the data insertion process

        // Generate a secure password hash
        $hashed_password = password_hash( $password, PASSWORD_DEFAULT );

        // Insert data into the tsm_super_admin table
        $insert_data = array(
            'username' => $username,
            'password' => $hashed_password,
            'email' => $email,
            'created_at' => current_time( 'mysql' ),
        );

        $insert_success = $wpdb->insert( $table_name, $insert_data );

        if ( $insert_success ) {
            // Redirect to the login page
            wp_redirect( home_url( '/super-admin-login/' ) );
            exit;
        } else {
            echo '<div class="error-message">Error inserting data into the database. Details: ' . $wpdb->last_error . '</div>';
        }
    }
}


?>

<style>
    body {
        font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f1f1f1;
    }

    .signup-box {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
    }

    .signup-form {
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
    input[type="password"],
    input[type="email"] {
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

<div class="signup-box">
    <div class="signup-form">
        <form method="post" action="<?php echo esc_url( home_url( '/super-admin-signup/' ) ); ?>">
            <label for="username">Username:</label>
            <input type="text" name="username" id="username" required>

            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required>

            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required>

            <input type="hidden" name="super_admin_signup" value="1">
            <input type="submit" value="Sign Up">
            <p>Already have an account? Login <a href="<?php echo esc_url( home_url( '/super-admin-login/' ) ); ?>">here</a></p>
        </form>
    </div>
</div>



<?php
/**
 * Check if a password meets the required strength.
 *
 * @param string $password The password to check.
 * @param int    $min_length Minimum length requirement.
 *
 * @return bool True if the password meets the strength requirements, false otherwise.
 */
function is_strong_password( $password, $min_length = 8 ) {
	// Check if the password meets the minimum length requirement
	if ( strlen( $password ) < $min_length ) {
		return false;
	}

	// Additional password strength checks
	// You can customize these checks based on your requirements

	// Check for at least one lowercase letter
	if ( ! preg_match( '/[a-z]/', $password ) ) {
		return false;
	}

	// Check for at least one uppercase letter
	if ( ! preg_match( '/[A-Z]/', $password ) ) {
		return false;
	}

	// Check for at least one digit
	if ( ! preg_match( '/\d/', $password ) ) {
		return false;
	}

	// Check for at least one special character
	if ( ! preg_match( '/[^a-zA-Z\d]/', $password ) ) {
		return false;
	}

	return true;
}
?>













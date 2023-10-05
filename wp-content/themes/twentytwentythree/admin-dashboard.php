<?php

/*
Template Name: Admin Dashboard
*/

global $wpdb;
$table_name = 'tsm_school';

// Check if the user is logged in
if (is_user_logged_in()) {
    // Get the current user object
    $current_user = wp_get_current_user();


    if (isset($_POST['admin_logout'])) {
        // Verify the logout nonce
        if (wp_verify_nonce($_POST['admin_logout_nonce'], 'admin_logout')) {
            // Perform the logout
            wp_logout();

            // Destroy the session
            session_destroy();

            // Redirect to the login page
            wp_redirect(home_url('/admin-login/'));
            exit;
        }
    }


?>

    <style>
        /* Microsoft-themed styling */
        body {
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            background-color: #ffffff;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        h1 {
            font-size: 28px;
            font-weight: bold;
            color: #0078D4;
            margin-bottom: 30px;
        }

        button {
            background-color: #0078D4;
            color: #fff;
            padding: 12px 20px;
            border: none;
            cursor: pointer;
            border-radius: 3px;
            margin-bottom: 20px;
        }

        .popup-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }

        .popup-container {
            display: block;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            border-radius: 5px;
            z-index: 1000;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
            color: #333;
        }

        input[type="text"],
        input[type="password"],
        input[type="email"],
        textarea {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 3px;
        }

        .submit {
            background-color: rgba(0, 120, 212, 0);
            color: #0078D4;
            padding: 15px 20px;
            border: 2px solid #0078D4;
            cursor: pointer;
            border-radius: 3px;
        }

        .submit:hover {
            background-color: #0078D4;
            color: white;
        }

        .logout {
            background-color: red;
            color: #fff;
            padding: 12px 20px;
            border: none;
            cursor: pointer;
            border-radius: 3px;
        }



        .error-message {
            color: red;
            margin-bottom: 15px;
        }

        .success-message {
            color: green;
            margin-bottom: 15px;
        }

        p {
            margin-top: 20px;
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 8px;
        }

        th {
            background-color: #f2f2f2;
        }

        /* Top navigation bar */
        .topnav {
            background-color: black;
            color: #fff;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
        }

        /* Left side of the top navigation bar */
        .left {
            display: flex;
            align-items: center;
        }

        /* Special School Management logo text */
        .logo {
            font-size: 20px;
            font-weight: bold;
        }

        /* Right side of the top navigation bar */
        .right {
            position: relative;
            /* Position the profile icon and dropdown */
            display: flex;
            align-items: center;
        }

        /* Profile icon */
        .profile-icon {
            cursor: pointer;
        }

        /* Dropdown menu content */
        .dropdown-content {
            position: absolute;
            top: 100%;
            right: 0;
            background-color: #f9f9f9;
            border: 1px solid #ccc;
            border-radius: 4px;
            display: none;
            padding: 10px;
        }

        /* Show the dropdown menu on hover */
        .profile:hover .dropdown-content {
            display: block;
        }

        /* Style the links inside the dropdown */
        .dropdown-content a {
            display: block;
            padding: 10px;
            text-decoration: none;
            color: #333;
        }

        /* Change the link color on hover */
        .dropdown-content a:hover {
            background-color: #0078D4;
            color: #fff;
        }

        /* Boxes */
        .boxes {
            padding-top: 30px;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }

        .box {
            width: calc(25% - 20px);
            /* Adjust the width as needed */
            padding: 20px;
            background-color: #f8f8f8;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-bottom: 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .box h2 {
            font-size: 18px;
            color: #0078D4;
            margin-bottom: 10px;
        }

        .box p {
            color: #333;
        }
    </style>
    <!-- Make sure to include Font Awesome CSS in the <head> of your HTML file -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <div class="topnav">
        <div class="left">
            <span class="logo">Special School Management</span>
        </div>
        <div class="right">
            <div class="profile">
                <i class="fas fa-user-circle profile-icon" style="font-size: 24px;"></i>
                <div class="dropdown-content">
                    <a href="#">Profile</a>
                    <a href="#">Settings</a>
                    <form method="post" action="">
                        <?php wp_nonce_field('admin_logout', 'admin_logout_nonce'); ?>
                        <input class="logout" type="submit" name="admin_logout" value="Logout">
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="boxes">
        <div class="box">
            <h2>Add Teachers</h2>
            <a href="wp_redirect(home_url('/addteachers/')"><button class="submit">Get Started &rarr;</button></a>
        </div>
        <div class="box">
            <h2>Add Students</h2>
            <a href="wp_redirect(home_url('/addstudents/')"><button class="submit">Get Started &rarr;</button></a>
        </div>
        <div class="box">
            <h2>View Students</h2>
            <a href="wp_redirect(home_url('/viewStudent/')"><button class="submit">Get Started &rarr;</button></a>
        </div>
    </div>



<?php
} else {
    // Redirect to the login page if the user is not logged in
    wp_redirect(home_url('/admin-login/'));
    exit;
}

<?php
/*
Template Name: Teacher Dashboard
*/

// Check if the user is logged in and has the 'teacher' role
if (is_user_logged_in() && current_user_can('teacher')) {
    get_header(); // Include the header if needed

    // Get the current user object
    $current_user = wp_get_current_user();
    $teacher_name = $current_user->display_name;

    // Logout functionality for teachers
    if (isset($_POST['teacher_logout'])) {
        // Verify the logout nonce
        if (wp_verify_nonce($_POST['teacher_logout_nonce'], 'teacher_logout')) {
            // Perform the logout
            wp_logout();

            // Redirect to the login page
            wp_redirect(home_url('/teacher-login/'));
            exit;
        }
    }

    // Your code for the teacher's dashboard goes here
    // For example, displaying a welcome message and a list of students

    echo '<div class="container">';
    echo '<h1>Welcome, ' . esc_html($teacher_name) . '!</h1>';

    // Display a list of students (assuming you have a custom post type for students)
    $args = array(
        'post_type' => 'student', // Replace 'student' with your custom post type name
        'posts_per_page' => -1, // Retrieve all students
    );

    $student_query = new WP_Query($args);

    if ($student_query->have_posts()) {
        echo '<h2>Students</h2>';
        echo '<ul>';

        while ($student_query->have_posts()) {
            $student_query->the_post();
            echo '<li><a href="' . get_permalink() . '">' . get_the_title() . '</a></li>';
        }

        echo '</ul>';
    } else {
        echo '<p>No students found.</p>';
    }

    wp_reset_postdata();

    // Add a form to upload student progress reports
    echo '<h2>Upload Student Progress Report</h2>';
    echo '<form method="post" action="" enctype="multipart/form-data">';
    wp_nonce_field('teacher_upload_report', 'teacher_upload_nonce');

    echo '<label for="student_id">Select Student:</label>';
    // You can populate this dropdown with student names or IDs
    // Example: <select name="student_id"><option value="1">Student 1</option></select>

    echo '<label for="report">Upload Report:</label>';
    echo '<input type="file" name="report" required>';

    echo '<input type="submit" name="upload_report" value="Upload Report">';
    echo '</form>';

    // Handle report upload here
    if (isset($_POST['upload_report'])) {
        // Handle the file upload and database insertion
        // Make sure to validate the uploaded file and sanitize data
        // Example: $student_id = sanitize_text_field($_POST['student_id']);
        // Example: $file = $_FILES['report'];

        // Insert the report data into your custom database table
        // Example: $wpdb->insert($custom_table_name, $data);

        // Display a success message or handle errors
        // Example: echo '<div class="success-message">Report uploaded successfully.</div>';
    }

    echo '</div>'; // Close the container

    get_footer(); // Include the footer if needed
} else {
    // Redirect unauthorized users to the teacher login page or display an error message
    wp_redirect(home_url('/teacher-login/')); // Redirect to the teacher login page
    exit;
}

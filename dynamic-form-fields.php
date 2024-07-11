<?php
/*
Plugin Name: Dynamic Form Fields
Description: Adds dynamic fields to forms using a "+" icon.
Version: 1.0
Author: Your Name
*/

function dynamic_form_fields_scripts() {
    // Register the main script
    wp_register_script(
        'dynamic-form-fields-script',
        plugins_url('/assets/js/script.js', __FILE__),
        array('jquery'), // Dependencies
        '1.0.0', // Version number
        true // Load in footer
    );

    // Register the style
    wp_register_style(
        'dynamic-form-fields-style',
        plugins_url('/assets/css/style.css', __FILE__)
    );

    // Enqueue the script
    wp_enqueue_script('dynamic-form-fields-script');

    // Enqueue the style
    wp_enqueue_style('dynamic-form-fields-style');
}
add_action('wp_enqueue_scripts', 'dynamic_form_fields_scripts');

function my_plugin_enqueue_bootstrap() {
    wp_enqueue_style('my-plugin-bootstrap-css', plugins_url('/assets/bootstrap-5.3/css/bootstrap.min.css', __FILE__));
    wp_enqueue_script('my-plugin-bootstrap-js', plugins_url('/assets/bootstrap-5.3/js/bootstrap.min.js', __FILE__), array('jquery'), '5.0.0', true);
}
add_action('wp_enqueue_scripts', 'my_plugin_enqueue_bootstrap');

function my_plugin_admin_enqueue_bootstrap() {
    wp_enqueue_style('my-plugin-bootstrap-admin-css', plugins_url('/assets/bootstrap-5.3/css/bootstrap.min.css', __FILE__));
    wp_enqueue_script('my-plugin-bootstrap-admin-js', plugins_url('/assets/bootstrap-5.3/css/bootstrap.min.css', __FILE__), array('jquery'), '5.0.0', true);
}
add_action('admin_enqueue_scripts', 'my_plugin_admin_enqueue_bootstrap');


function get_rooms_data() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'rooms'; // Assuming the table name is prefixed
    $query = "SELECT * FROM {$table_name}";
    
    // Execute the query and fetch all rows
    $results = $wpdb->get_results($query);
    
    // Convert the result set to an array of objects
    $rooms = array_map(function($row) {
        return (object)$row;
    }, $results);
    
    return $rooms;
}

function display_rooms_data_shortcode() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['room_number']) && isset($_POST['room_number_input'])) {
            $totalResult = 0; // Initialize total result variable
            foreach ($_POST['room_number'] as $index => $selectedRoomType) {
                $enteredNumber = intval($_POST['room_number_input'][$index]);
                $rooms = get_rooms_data();
                $room = find_room_by_type($rooms, $selectedRoomType);

                if ($room !== null) {
                    $roomJsonValue = intval($room->room_json);
                    $result = $roomJsonValue * $enteredNumber;
                    $totalResult += $result; // Add individual result to total

                    // Updated HTML with Bootstrap classes
                    echo '<div class="card mb-3">'; // Card for each room data
                        echo '<div class="card-body">';
                            echo '<h5 class="card-title">Room ' . ($index + 1) . '</h5>';
                            echo '<p class="card-text">Single price: ' . esc_html($room->room_json) . '</p>';
                            echo '<p class="card-text">Total price: ' . esc_html($result) . '</p>';
                        echo '</div>';
                    echo '</div>';
                }
            }
            echo '<div class="mt-3"><p><strong>Total for all Rooms: ' . esc_html($totalResult) . '</strong></p></div>';
            echo '<a href="' . esc_url_raw(add_query_arg('action', 'go_back')) . '" class="btn btn-primary df-text-decoration">Go Back</a>';
        }
    } else {
        echo display_room_selection_form();
    }
}







// Helper function to find a room by its type
function find_room_by_type($rooms, $type) {
    foreach ($rooms as $room) {
        if ($room->room_type_title === $type) {
            return $room;
        }
    }
    return null;
}



add_shortcode('display_rooms', 'display_rooms_data_shortcode');


function display_room_selection_form() {
    // Start output buffering to capture HTML output
    ob_start();

    // Generate the form
    echo '<form method="post" action="" class="needs-validation" novalidate>'; // Added Bootstrap validation class
    echo '<div id="roomFormContainer" class="container mt-5">'; // Added Bootstrap container and margin-top classes

    // Initial form elements
    echo '<select name="room_number[]" class="form-select mb-3 mt-3">'; // Added Bootstrap select class and margin-bottom
    echo '<option value="" disabled selected>Select Room Type</option>';
    echo '<option value="Single Bed">Single Bed</option>';
    echo '<option value="Double Bed">Double Bed</option>';
    echo '<option value="HAll">Hall</option>';
    echo '</select>';

    echo '<div class="mb-3">'; // Wrapper for input number and label
        echo '<input type="number" placeholder="Enter Number of Rooms" class="form-control" id="room_number_input" name="room_number_input[]" min="1" max="100" required>'; // Added Bootstrap input class
    echo '</div>';

    // Wrap the buttons in a row and justify them
    echo '<div class="row d-flex justify-content-between">';
        echo '<button type="button" class="btn btn-primary js-add-another-type">Add Another Type</button>'; // Added Bootstrap button class
        echo '<input type="submit" value="Submit All" class="btn btn-success mt-3" style="width:auto;">'; // Added Bootstrap submit button class and style
    echo '</div>';

    echo '</div>'; // Close container

    // Get the captured HTML output
    $output = ob_get_clean();

    // Return the output
    return $output;
}



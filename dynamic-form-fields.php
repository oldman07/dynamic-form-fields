<?php
/*
Plugin Name: Dynamic Form Fields
Description: Adds dynamic fields to forms using a "+" icon.
Version: 1.0
Author: Your Name
*/

function dynamic_form_fields_scripts() {
    // Register the script
    wp_register_script(
        'dynamic-form-fields-script',
        plugins_url('/assets/js/script.js', __FILE__),
        array('jquery'), // Dependencies
        '1.0.0', // Version number
        true // In footer
    );

    // Enqueue the script
    wp_enqueue_script('dynamic-form-fields-script');
}
add_action('wp_enqueue_scripts', 'dynamic_form_fields_scripts');


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

                    echo '<div class="room">';
                    echo '<h2>Item ' . ($index + 1) . ': ' . esc_html($room->room_json) . '</h2>';
                    echo '<p>Total: ' . esc_html($result) . '</p>';
                    
                    echo '</div>';
                }
            }
            echo '<p><strong>Total for all items: ' . esc_html($totalResult) . '</strong></p>';
            echo '<a href="' . esc_url_raw(add_query_arg('action', 'go_back')) . '">Go Back</a>';
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
    echo '<form method="post" action="">';
    echo '<div id="roomFormContainer">'; // Container for the initial form elements

    // Initial form elements
    echo '<select name="room_number[]">'; // Notice the square brackets for multiple selections
    echo '<option value="" disabled selected>Select Room Type</option>';
    echo '<option value="Single Bed">Single Bed</option>';
    echo '<option value="Double Bed">Double Bed</option>';
    echo '<option value="Hall">Hall</option>';
    echo '</select>';
    // echo '<label for="room_number_input">Enter Number of Rooms:</label>';
    echo '<input type="number" placeholder="Enter Number of Rooms" id="room_number_input" name="room_number_input[]" min="1" max="100" required>';
    echo '<button type="button" class="js-add-another-type">Add Another Type</button>'; // Clone button

    echo '</div>'; // Close container
    echo '<input type="submit" value="Submit All">'; // Submit button for all forms

    // Get the captured HTML output
    $output = ob_get_clean();

    // Return the output
    return $output;
}



















#functionality working code
// function dynamic_form_fields_scripts() {
//     // Register the script
//     wp_register_script(
//         'dynamic-form-fields-script',
//         plugins_url('/assets/js/script.js', __FILE__),
//         array('jquery'), // Dependencies
//         '1.0.0', // Version number
//         true // In footer
//     );

//     // Enqueue the script
//     wp_enqueue_script('dynamic-form-fields-script');
// }
// add_action('wp_enqueue_scripts', 'dynamic_form_fields_scripts');


// function get_rooms_data() {
//     global $wpdb;
//     $table_name = $wpdb->prefix . 'rooms'; // Assuming the table name is prefixed
//     $query = "SELECT * FROM {$table_name}";
    
//     // Execute the query and fetch all rows
//     $results = $wpdb->get_results($query);
    
//     // Convert the result set to an array of objects
//     $rooms = array_map(function($row) {
//         return (object)$row;
//     }, $results);
    
//     return $rooms;
// }

// function display_rooms_data_shortcode() {
//     if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//         if (isset($_POST['room_number']) && isset($_POST['room_number_input'])) {
//             $totalResult = 0; // Initialize total result variable
//             foreach ($_POST['room_number'] as $index => $selectedRoomType) {
//                 $enteredNumber = intval($_POST['room_number_input'][$index]);
//                 $rooms = get_rooms_data();
//                 $room = find_room_by_type($rooms, $selectedRoomType);

//                 if ($room !== null) {
//                     $roomJsonValue = intval($room->room_json);
//                     $result = $roomJsonValue * $enteredNumber;
//                     $totalResult += $result; // Add individual result to total

//                     echo '<div class="room">';
//                     echo '<h2>Item ' . ($index + 1) . ': ' . esc_html($room->room_json) . '</h2>';
//                     echo '<p>Total: ' . esc_html($result) . '</p>';
                    
//                     echo '</div>';
//                 }
//             }
//             echo '<p><strong>Total for all items: ' . esc_html($totalResult) . '</strong></p>';
//             echo '<a href="' . esc_url_raw(add_query_arg('action', 'go_back')) . '">Go Back</a>';
//         }
//     } else {
//         echo display_room_selection_form();
//     }
// }






// // Helper function to find a room by its type
// function find_room_by_type($rooms, $type) {
//     foreach ($rooms as $room) {
//         if ($room->room_type_title === $type) {
//             return $room;
//         }
//     }
//     return null;
// }



// add_shortcode('display_rooms', 'display_rooms_data_shortcode');


// function display_room_selection_form() {
//     // Start output buffering to capture HTML output
//     ob_start();

//     // Generate the form
//     echo '<form method="post" action="">';
//     echo '<div id="roomFormContainer">'; // Container for the initial form elements

//     // Initial form elements
//     echo '<select name="room_number[]">'; // Notice the square brackets for multiple selections
//     echo '<option value="" disabled selected>Select Room Type</option>';
//     echo '<option value="Single Bed">Single Bed</option>';
//     echo '<option value="Double Bed">Double Bed</option>';
//     echo '<option value="Hall">Hall</option>';
//     echo '</select>';
//     echo '<label for="room_number_input">Enter Number of Rooms:</label>';
//     echo '<input type="number" id="room_number_input" name="room_number_input[]" min="1" max="100" required>';
//     echo '<button type="button" class="js-add-another-type">Add Another Type</button>'; // Clone button

//     echo '</div>'; // Close container
//     echo '<input type="submit" value="Submit All">'; // Submit button for all forms

//     // Get the captured HTML output
//     $output = ob_get_clean();

//     // Return the output
//     return $output;
// }



// jQuery(document).ready(function($) {
//     alert('Script is loaded!');
// });

jQuery(document).ready(function($) {
    // Initialize counter for new fields
    var count = 1;

    // Add event listener to the clone button
    $('.js-add-another-type').click(function(e) {
        e.preventDefault(); // Prevent the default action

        // Create new select and input elements
        var newSelect = $('<select>', {name: 'room_number[]'});
        var newOption = $('<option>', {value: "", text: "Select Room Type", disabled: true, selected: true});
        var newInput = $('<input>', {type: "number", id: "room_number_input_" + count, name: "room_number_input[]", min: "1", max: "100", required: true});

        // Append options to the new select
        newSelect.append(newOption);
        newSelect.append($('<option>', {value: "Single Bed", text: "Single Bed"}));
        newSelect.append($('<option>', {value: "Double Bed", text: "Double Bed"}));
        newSelect.append($('<option>', {value: "Hall", text: "Hall"}));

        // Wrap the new elements in a div and prepend them to the form container
        var newRow = $('<div>').append(newSelect, newInput);
        $('#roomFormContainer').prepend(newRow);

        // Increment the counter for the next addition
        count++;
    });
});









// jQuery(document).ready(function($) {
//     $('.add-field').click(function(e) {
//         e.preventDefault();
//         var currentCount = $('#dynamicFormFields select, #dynamicFormFields input').length;
//         var newSelect = $('<select>', {name: "cars[]", id: "cars" + currentCount}).append(
//             $('<option>', {value: "volvo", text: "Volvo"})
//             , $('<option>', {value: "saab", text: "Saab"})
//             , $('<option>', {value: "mercedes", text: "Mercedes"})
//             , $('<option>', {value: "audi", text: "Audi"})
//         );
//         var newInput = $('<input>', {type: "number", placeholder: "Number of Rooms"});
//         $('#dynamicFormFields').append(newSelect, newInput);
//     });

//     $('#dynamicForm').on('submit', function(e) {
//         e.preventDefault();
//         $('#thanksMessage').show();
//     });
// });


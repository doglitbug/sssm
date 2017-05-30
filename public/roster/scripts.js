////// Functions for adding new shifts
//Attach click for adding a new shift
$(document).on("click", "#shifttable td.shifts", function (e) {
    var data = $(this).attr('id');
    //Split up user_id and date from destination
    var split = data.indexOf('-');
    var user_id = data.substring(0, split);
    var start_date = data.substring(split + 1);
    alert("Add new shift for: " + user_id + " on " + start_date);
});

//Attach click for editing a shift
$(document).on("click", ".shift", function (event) {
    //Stop the add new shift part
    event.stopPropagation();

    var roster_id = $(this).attr('id');
    alert("Edit shift :" + roster_id);
});

//Enable dragging for all shifts
$(function () {
    $(".shift").draggable({
        containment: '#shifttable',
        cursor: 'move',
        zIndex: 100,
        revert: "invalid"
    });
});

//Enable dropping for all shift locations
$(function () {
    $(".shifts").droppable({
        drop: doDrop
    });
});


//Action functions
function doDrop(event, ui) {
    //Get id of moved shift(same as in database)
    var roster_id = ui.draggable.attr('id');

    //Get all the required data
    var target_location = event.target.id;

    //Split up user_id and date of target
    var split = target_location.indexOf('-');
    var user_id = target_location.substring(0, split);
    var start_date = target_location.substring(split + 1);

    //Split up time and get start_time and end_time
    var time = ui.draggable.children(".time").text();
    var split = time.indexOf("-");
    var start_time = time.substring(0, split - 1);//Adjust because divider is " - "
    var end_time = time.substring(split + 2);//Adjust because divider is " - ";

    var description = ui.draggable.children(".description").text();
    //Use jQuery here to move shift in database
    $.getJSON({
        type: 'post',
        url: 'update.php',
        data: $.param({'roster_id': roster_id, 'user_id': user_id, 'start_date': start_date, 'start_time': start_time, 'end_time': end_time, 'description': description}),
        success: function (data, status, jqXHR) {
            if (data.success) {
                console.log(data.message);
                //Move shift into dropped position
                ui.draggable.appendTo(event.target).css({top: '0px', left: '0px'});
            } else {
                //TODO Deal with error
                console.log("Error: " + data.message);
                //Move dragged shift back to the original position
                ui.draggable.animate({top: 0, left: 0}, 'slow');
            }

        },
        error: function (data, status, headers, config) {
            //TODO Deal with serious error
            console.log("Serious error: " + data.message);
            //Move dragged shift back to the original position
            ui.draggable.animate({top: 0, left: 0}, 'slow');
        }});

}
function doAlert(message, severity){
    
}

jQuery(function($){

    function arrivalOutput() {

        var output = '';

        output += '<tr><th scope="row"></th>';
        output += '<td><div class="wc-bookings-booking-form">';
        output += '<label for="arrival-time">Approximate arrival time:</label>';
        output += '<input type="text" name="arrival-time" />';
        output += '</div></td></tr>';

        return output;
    }



    if($('.wc-bookings-booking-form').length) {

        $('.wc-bookings-booking-form').closest('tr').after(arrivalOutput());

    }



});

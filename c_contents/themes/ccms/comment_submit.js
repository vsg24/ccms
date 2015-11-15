$(document).ready(function() {
    // spin.js code start
    var opts = {
        lines: 7 // The number of lines to draw
        , length: 27 // The length of each line
        , width: 14 // The line thickness
        , radius: 32 // The radius of the inner circle
        , scale: 0.20 // Scales overall size of the spinner
        , corners: 1 // Corner roundness (0..1)
        , color: '#000' // #rgb or #rrggbb or array of colors
        , opacity: 0.25 // Opacity of the lines
        , rotate: 0 // The rotation offset
        , direction: 1 // 1: clockwise, -1: counterclockwise
        , speed: 1.50 // Rounds per second
        , trail: 60 // Afterglow percentage
        , fps: 20 // Frames per second when using setTimeout() as a fallback for CSS
        , zIndex: 2e9 // The z-index (defaults to 2000000000)
        , className: 'spinner' // The CSS class to assign to the spinner
        , top: '50%' // Top position relative to parent
        , left: '50%' // Left position relative to parent
        , shadow: false // Whether to render a shadow
        , hwaccel: false // Whether to use hardware acceleration
        , position: 'relative' // Element positioning
    };
    var target = document.getElementById('spinner');
    // spin.js code ends

    // process the form
    $('#comment_form').submit(function(event) {

        var spinner = new Spinner(opts).spin(target);

        // get the form data
        // there are many ways to get this data using jQuery (you can use the class or id also)

        var mode;
        if($('input[name=user_id]').val() == "")
        {
            mode = '1';
        }
        else
        {
            mode = '2';
        }

        var formData = {
            'name'              : $('input[name=new_user_name]').val(),
            'email'              : $('input[name=new_user_email]').val(),
            'text'              : $('#comment_text').val(),
            'id'              : $('input[name=post_id]').val(),
            'mode'              : mode,
            'user'              : $('input[name=user_id]').val()
        };

        // process the form
        $.ajax({
            type        : 'POST', // define the type of HTTP verb we want to use (POST for our form)
            url         : 'comment_submit.php', // the url where we want to POST
            data        : formData, // our data object
            dataType    : 'json', // what type of data do we expect back from the server
            encode          : true
        })

            // using the done promise callback
            .done(function(data) {
                // log data to the console so we can see
                //console.log(data);

                // here we will handle errors and validation messages
                if ( ! data.success) {
                    spinner.stop();
                    //console.log('comment too short');
                    if( $('#error').length )         // use this if you are using id to check
                    {
                        $("#error").effect( "shake",
                            {times:4}, 1000 );
                        spinner.stop();
                    }
                    else
                    {
                        $('#comments').append("<h4 id='error' style='color: red;'>" + data.errors[0] + "</h4>");
                    }

                } else {
                    //console.log('comment submitted');
                    $('#error').hide();
                    $('#comment_text').val('');
                    var comment = "<p><em>" + data.author + "</em> on <span class=\"help-block\" style=\"display: inline;\">" + data.date + "</span> said:</p><div>" + data.text + "</div><br>";
                    $('#comments').append(comment);
                    $('input[name=user_id]').val(data.author_id);
                    spinner.stop();
                }
            })

            .fail(function(data) {
                console.log(data);
                console.log('ajax error');
            });

        // stop the form from submitting the normal way and refreshing the page
        event.preventDefault();
    });

});
(function ($) {
    'use strict';

    // Instantiates the variable that holds the media library frame.
    var meta_image_frame;

    // runs when image button clicked.
    $('#upload_image_btn').click(function (e) {
        // prevents default action occuring.
        e.preventDefault();
        // if frame exists, re-open it.
        if (meta_image_frame) {
            meta_image_frame.open();
            return;
        }
        // sets media library frame
        meta_image_frame = wp.media.frames.meta_image_frame = wp.media({
            title: meta_image.title,
            button: {
                text: meta_image.button
            },
            library: {
                type: 'image'
            }
        });
        // runs when image selected.
        meta_image_frame.on('select', function () {
            // grabs attachment selection , creates json representation of model.
            var media_attachment = meta_image_frame.state().get('selection').first().toJSON();
            // sends attachment url our custom image input field.
            $('#txt_upload_image').val(media_attachment.url);
        });
        // opens media library frame.
        meta_image_frame.open();
    });

})(jQuery);

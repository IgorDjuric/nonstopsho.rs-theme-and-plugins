jQuery(document).ready(function($) {
    var custom_uploader;

    function clickHandler(event, input, submitButton, target) {
        event.preventDefault();
        //Extend the wp.media object
        custom_uploader = wp.media.frames.file_frame = wp.media({
            title: 'Upload image',
            button: {
                text: 'Select'
            },
            multiple: false
        });
        //When a file is selected, grab the URL and set it as the text field's value
        custom_uploader.on('select', function() {
            var attachment = custom_uploader.state().get('selection').first().toJSON();
            input.val(attachment.url);
            submitButton.click();
        });

        //If the uploader object has already been created, reopen the dialog
        if (custom_uploader) {
            custom_uploader.open();
            return;
        }

        //Open the uploader dialog
        custom_uploader.open();
        return;
    }

    $('.row').on('click', '#upload-sticker-image-new', function(e) {
        clickHandler(e, $('.image_select_new'), $('#sticker_submit'));
    });
    $('.row').on('click', '#upload-sticker-image-soldout', function(e) {
        clickHandler(e, $('.image_select_soldout'), $('#sticker_submit'));
    });
    $('.row').on('click', '#upload-sticker-image-sale', function(e) {
        clickHandler(e, $('.image_select_sale'), $('#sticker_submit'));
    });
});
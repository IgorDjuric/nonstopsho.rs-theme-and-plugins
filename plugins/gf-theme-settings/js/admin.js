$('.upload-image-button').click(function() {
    var send_attachment_bkp = wp.media.editor.send.attachment;
    var button = $(this);
    wp.media.editor.send.attachment = function(props, attachment) {
        $(button).parent().prev().attr('src', attachment.url);
        $(button).prev().val(attachment.id);
        wp.media.editor.send.attachment = send_attachment_bkp;
    }
    wp.media.editor.open(button);
    return false;
});

// The "Remove" button (remove the value from input type='hidden')
$('.remove-image-button').click(function() {
    var answer = confirm('Da li ste sigurni da želite da obrišete sliku?');
    if (answer == true) {
        var src = $(this).parent().prev();
        $(this).parent().prev().attr('src', src);
        $(this).prev().prev().val('');
        $('#image_submit').click();
    }
    return false;
});



$('.upload-image-banners-button').click(function() {
    // $('.upload-image-banners-button').preventDefault()
    var send_attachment_bkp = wp.media.editor.send.attachment;
    var button = $(this);
    wp.media.editor.send.attachment = function(props, attachment) {
        $(button).parent().prev().attr('src', attachment.url);
        $(button).prev().val(attachment.id);
        wp.media.editor.send.attachment = send_attachment_bkp;
    }
    wp.media.editor.open(button);
    return false;
});

// The "Remove" button (remove the value from input type='hidden')
$('.remove-image-banners-button').click(function() {
    var answer = confirm('Da li ste sigurni da želite da obrišete sliku?');
    if (answer == true) {
        var src = $(this).parent().prev();
        $(this).parent().prev().attr('src', src);
        $(this).prev().prev().val('');
        $('#image_banners_submit').click();
    }
    return false;
});
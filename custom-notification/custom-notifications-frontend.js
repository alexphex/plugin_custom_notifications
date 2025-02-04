jQuery(document).ready(function($) {
    $('.custom-notification-close').on('click', function() {
        $(this).parent('.custom-notification').hide();
    });
});

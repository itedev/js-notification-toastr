(function($) {
  $(document).ready(function() {
    $(document).on('ite-show.toastr.notification', function(e, notification) {
      toastr[notification.type](notification.message, notification.title, notification.options);
    });
  });
})(jQuery);
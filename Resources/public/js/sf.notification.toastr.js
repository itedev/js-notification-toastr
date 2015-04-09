(function ($) {
  $(document).ready(function () {
    $(document).on('ite-show.notification', function (e, notification) {
      toastr[notification.type](notification.message, notification.title, notification.pluginOptions);
    });
  });
})(jQuery);
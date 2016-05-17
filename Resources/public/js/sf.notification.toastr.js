(function($) {
  SF.notifier.channels['toastr'] = new SF.classes.Channel({
    message: function (type, title, message, options) {
      toastr[type](message, title, options);
    }
  });
})(jQuery);
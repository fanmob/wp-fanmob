(function ($) {
  $(function () {
    var fanmobOrigin = WpFanmob.origin;

    $(window).on('message', function ($evt) {
      var evt = $evt.originalEvent;
      if (evt.origin !== fanmobOrigin) {
        return;
      }
      var msg = evt.data;
      switch (msg.type) {
        case 'insert_poll': {
          var id = msg.payload.id;
          if (!id || id.length === 0) {
            console.error('insert_poll message missing id in payload', msg);
            break;
          }
          window.send_to_editor('[fanmob id="' + id + '"]');
          break;
        }
        default: {
          console.error('Unknown Wordpress FanMob message type:', msg.type,
                        'in event', evt);
        }
      }
    });
  });
})(jQuery);

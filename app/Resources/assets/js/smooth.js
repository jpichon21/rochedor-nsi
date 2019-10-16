;(function ($) {
    'use strict';
    var content  = $('.m-scene').smoothState({
          onStart : {
            duration: 250,
            render: function () {
              content.toggleAnimationClass('is-exiting');
            }
          }
        }).data('smoothState'); 
  })(jQuery);
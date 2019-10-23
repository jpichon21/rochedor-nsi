import $ from 'jquery'

$(document).ready(function () {

  $('a').click(function() {
    var $this = this
    event.preventDefault();
    var newLocation = $this.href;
    $('.content, .footer').fadeOut(600, newpage(newLocation));
   
  });

    function newpage(newLocation) {
        window.location = newLocation;
    }
    
});
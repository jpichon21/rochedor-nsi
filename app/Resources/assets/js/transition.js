import $ from 'jquery'

$(document).ready(function () {

  $('.menu a, .yellow, .black').click(function(event) {
    console.log("test");
    var $this = this;
    event.preventDefault();
    var newLocation = $this.href;
    $('body').fadeOut(1000, newpage(newLocation));  
  });
});

/*$(document).ready(function () {

  $('.menu a, button').click(function() {
      var $this = this
      event.preventDefault();
      var newLocation = $this.href;
      $('.content, .footer').fadeOut(600, newpage(newLocation));
     
    });
  
      function newpage(newLocation) {
          window.location = newLocation;
      }
      
  });*/
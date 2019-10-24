import $ from 'jquery'

$(document).ready(function () {

  $('.menu a, .button').click(function() {
    console.log("test");
    var $this = this;
    event.preventDefault();
    var newLocation = $this.href;
    $('body').fadeOut(600, newpage(newLocation));
   
  });

    function newpage(newLocation) {
      window.location = newLocation;
      fadeNew();
    }

    function fadeNew(){
      $('body').fadeIn(600);
    }
    
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
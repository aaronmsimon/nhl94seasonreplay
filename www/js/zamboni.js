$(document).ready(function() {
  var w_window = window.innerWidth;
  var w_zamboni = 171;

  function animateZam() {
    $("#zamboni").animate({
      left: w_window + "px"
    }, 10000, "linear",function() {
      $(this).css("left",-w_zamboni + "px");
      animateZam();
    });
  }
  animateZam();
});

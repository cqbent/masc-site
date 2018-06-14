(function ($) {
  $( document ).ready(function() {
    /* main menu dropdown actions */
    $('header nav h2').click(function() {
      if ($(this).parent().hasClass('active')) {
        $(this).parent().removeClass('active');
      }
      else {
        $(this).parent().addClass('active');
      }
    });
    $('header nav li.menu-item--expanded').click(function() {
      if ($(this).hasClass('active')) {
        $(this).removeClass('active');
      }
      else {
        $(this).addClass('active');
      }
    });
  });

})(jQuery);
/**
 * @file
 * Global utilities.
 *
 */
(function($, Drupal) {

  'use strict';
  Drupal.behaviors.wow_animations = {
    attach: function (context, settings) {
      new WOW().init();
    }
  };

  Drupal.behaviors.next_active = {
    attach: function (context, settings) {
      var active = $('#main-nav .active');
      var next = active.closest('li');
      $('#next-button').attr("href", next.next().find('.nav-link').attr("href"));
    }
  };

  Drupal.behaviors.circle_animate = {
    attach: function (context, settings) {
      $(document).ready(function () {
      setTimeout(
        function()
        {
          var circle = $('.circle-wrapper');
          circle.fadeIn('slow', function () {
            circle.attr("animation-play-state", "running");
          });
        }, 1000);
      });
    }
  };

  Drupal.behaviors.bootstrap_barrio_subtheme = {
    attach: function(context, settings) {
      var position = $(window).scrollTop();
      $(window).scroll(function () {
        if ($(this).scrollTop() > 50) {
          $('body').addClass("scrolled");
        }
        else {
          $('body').removeClass("scrolled");
        }
        var scroll = $(window).scrollTop();
        if (scroll > position) {
          $('body').addClass("scrolldown");
          $('body').removeClass("scrollup");
        } else {
          $('body').addClass("scrollup");
          $('body').removeClass("scrolldown");
        }
        position = scroll;
      });

    }
  };

  Drupal.behaviors.smooth_scroll = {
    attach: function(context, settings) {
  // Add smooth scrolling on all links inside the navbar
  $("#navbar-sections a, .more-sections a").on('click', function(event) {

    // Make sure this.hash has a value before overriding default behavior
    if (this.hash !== "") {

      // Prevent default anchor click behavior
      event.preventDefault();

      // Store hash
      var hash = this.hash;

      // Using jQuery's animate() method to add smooth page scroll
      // The optional number (800) specifies the number of milliseconds it takes to scroll to the specified area
      $('html, body').animate({
        scrollTop: $(hash).offset().top - 100
      }, 800, function(){

        // Add hash (#) to URL when done scrolling (default click behavior)
        window.location.hash = hash;
      });

    } // End if

  });
    }
  };

})(jQuery, Drupal);

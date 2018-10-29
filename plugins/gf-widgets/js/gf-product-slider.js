jQuery(document).ready(function($) {
  //don't start on wrong pages
  if (typeof gfSliderColumnCount !== "undefined") {

		//@Important activate slider after tab is displayed in order to have access to proper width
		$("#tabs").tabs({
			activate: function(event, ui) {
				if (!$(ui.newPanel.selector).hasClass('slick-initialized')) {
					startSlider(ui.newPanel.selector);
				}
			}
		});
		startSlider('#tabs-1');
		hookSliderEvents();
		startSlider('.without-tabs');
  }

  function startSlider(selector) {
    $(selector).slick({
      infinite: true,
      slidesToShow: gfSliderColumnCount,
      slidesToScroll: gfSliderColumnCount,
      arrows: false,
	    dots: false,
      responsive: [{
          breakpoint: 1024,
          settings: {
            slidesToShow: 3,
            slidesToScroll: 3,
            infinite: true
          }
        },
        {
          breakpoint: 600,
          settings: {
            slidesToShow: 2,
            slidesToScroll: 2
          }
        },
        {
          breakpoint: 480,
          settings: {
            slidesToShow: 2,
            slidesToScroll: 2
          }
        }
			]
    });
  }

  function hookSliderEvents() {
	// standard carousel controls
    $('.widget_gf_product_slider_without_tabs_widget .product-slider__control-prev').click(function(e) {
        e.preventDefault();
        $(this).parents('.widget_gf_product_slider_without_tabs_widget').find('.slider-inner').slick('slickPrev');
    });

    $('.widget_gf_product_slider_without_tabs_widget .product-slider__control-next').click(function(e) {
        e.preventDefault();
        $(this).parents('.widget_gf_product_slider_without_tabs_widget').find('.slider-inner').slick('slickNext');
    });

	//tabbed carousel controls
	$('#tabs .product-slider__control-prev').click(function(e) {
	  e.preventDefault();
	  $('.slider-inner').each(function(key, value) {
		  if ($(value).attr('aria-hidden') == 'false') {
			  $(this).slick('slickPrev');
		  }
	  });
	});

	$('#tabs .product-slider__control-next').click(function(e) {
	  e.preventDefault();
	  $('.slider-inner').each(function(key, value) {
		  if ($(value).attr('aria-hidden') == 'false') {
			  $(this).slick('slickNext');
		  }
	  });
	});
  }

});

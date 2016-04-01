var animated;

function fadeElement(el) {
	if (animated) {
		return false;
	}
	var showSlide = $(el).attr("href") || $(el).data("slide"),
		effect = function() {},
		$el = $(el),
		effectType = $(el).data('effect');


	switch (effectType) {
		case "resizeMap":
			effect = function() {
				setTimeout(function() {
					var center = map.getCenter();
					google.maps.event.trigger(map, "resize");
					map.setCenter(center);
				}, 300);
			};

			break;
		case "mainMenuAnimation":
			effect = function() {
				//$('.menu_1 a').removeClass('active');

				if($(el).parent('li').hasClass('active')){	
					$('.menu_animation > li').removeClass('active');
					$(el).closest('.menu_animation').find('li').removeClass('disabled'); 
				} else {
					$('.menu_animation > li').removeClass('active').addClass('disabled');
					$(el).closest('li').removeClass('disabled').addClass('active');
				}

			};

			break;
		case "contactMenuAnimation":
			effect = function() {
				$('.map-menu > li').removeClass('active');
				$el.closest('li').addClass('active').prependTo(".map-menu");
				clearMarkers();
				markers = [];
				setAllMarkers($el);
				var wrap = $el.next('.inner_block');
				$('.map-contacts-wrap').appendTo(wrap);

			};

			break;

		default:
			effect = function() {

			};
			break;

	}


	if (!$(el).hasClass('active')) {
		animated = true;
		$('.slide:visible').fadeOut(function() {
			$el.closest('.slide, .nav-selecter').find('.slide-trigger').removeClass('active');
			effect();
			$(showSlide).fadeIn();
			$el.addClass('active');
			animated = false;

		})
	} else {
		if($el.parents('.main_menu').hasClass('menu_animation')){
			animated = true;
			$('.slide:visible').fadeOut(function() {
				$el.removeClass('active');
				effect();
				$(showSlide).fadeIn();
				animated = false;
			})
		}
	}
}

function openMore(trigger, target, textClose, textOpen) {
	var $this = $(trigger);
	if ($this.hasClass('opened')) {
		$this.removeClass('opened').text(textClose);
		$(target).slideUp();
	} else {
		$this.addClass('opened').text(textOpen);
		$(target).slideDown();
	}
}


///////////////
//google map //
///////////////

var map,
	map2,
	mapContacts,
	curMarker,
	mapData,
	lat,
	lang,
	markers = [];

function addMarker(lat, lng) {
	var marker = new MarkerWithLabel({
		position: new google.maps.LatLng(lat, lng),
		map: map,
		draggable: false,
		raiseOnDrag: false,
		labelAnchor: new google.maps.Point(17, 31),
		labelClass: "",
		icon: '/local/templates/hogart_mobile_new/images/pin_map.png'
	});

    map.setCenter(marker.getPosition());

	var markerContacts = new MarkerWithLabel({
		position: new google.maps.LatLng(lat, lng),
		map: mapContacts,
		draggable: false,
		raiseOnDrag: false,
		labelAnchor: new google.maps.Point(17, 31),
		labelClass: "",
		icon: '/local/templates/hogart_mobile_new/images/pin_map.png'
	});
};



function setAllMap(map) {
	for (var i = 0; i < markers.length; i++) {
		markers[i].setMap(map);
	}
}

function clearMarkers() {
	setAllMap(null);
}

function setAllMarkers(point) {
	mapData = $(point).data('val').split(',');
	lat = Number(mapData[0]);
	lang = Number(mapData[1]);
	addMarker(lat, lang);
}


function map_initialize(block) {
	mapData = $(block).data('val').split(',');
	lat = Number(mapData[0]);
	lang = Number(mapData[1]);

	var latlng = new google.maps.LatLng(lat, lang);
	var myOptions = {
		zoom: 12,
		center: latlng,
		mapTypeControl: false,
		draggable: false,
		minZoom: 3,
		rotateControl: false,
		streetViewControl: false,
		scaleControl: true,
		zoomControl: false,
		mapTypeIds: google.maps.MapTypeId.ROADMAP,
	};
	map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
	if ($("#map-contacts-main").length) {
		mapContacts = new google.maps.Map(document.getElementById("map-contacts-main"), myOptions);
	}

	setAllMarkers(block);

}



$(function() {
	$('.slide-trigger').on('click', function(e) {

		e.preventDefault();
		fadeElement(this);
		if ($('#main_slide').is(':visible') && $(e.target).parent().hasClass('menu_1')) {

			document.location.href = "/";
		}

	});

	map_initialize('.map_active');

	if ($('.contacts-map-main').length) {
		map_initialize('.contacts-map-main');
	}



	$(".owl-carousel").each(function(index, el) {
		var pagination = $(el).data('pagination'),
			items = $(el).data('items') || 1;
		$(el).owlCarousel({
			auto: false,
			items: items,
			navigation: true,
			navigationText: ['', ''],
			pagination: pagination,
			rewindNav: false
		});
	});

	$('.breadcrumbs .active').on('click', function(e) {
		$('.breadcrumbs').toggleClass('opened');
		e.preventDefault();
	});

	// $('.show-more-top').on('click', function() {
	// 	$(this).toggleClass('opened');
	// 	$(this).prev().toggleClass('opened');
	// })

	$('body').on('click', '.open-next', function(e) {
		$(this).toggleClass('opened')
		$(this).siblings('.open-block').toggleClass('opened');
		e.preventDefault();

	});

	$('body').on('change', '.choose-block-trigger', function(e) {
		var block = $(this).val();
		$('.choose-items').removeClass('opened');
		$('.' + block).addClass('opened');
		e.preventDefault();
	});



	$('.masked').inputmask("+7 (999)999-99-99");

	$(".slider").each(function(index, el) {
		var $this = $(this),
			container = $this.closest('.filter-block');

		$this.slider({
			min: $this.data('min'),
			max: $this.data('max'),
			step: $this.data('step'),
			range: true,
			values: [$this.data('start-value'), $this.data('end-value')],
			slide: function(event, ui) {
				container.find('.min').val(ui.values[0]);
				container.find('.max').val(ui.values[1]);
				container.find('.slider-min').val(ui.values[0]);
				container.find('.slider-max').val(ui.values[1]);

			}
		})
	});
	var monthNamesArray = ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь',
		'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'
	];
	$.datepicker.regional['ru'] = {
		closeText: 'Закрыть',
		prevText: '<',
		nextText: '>',
		currentText: 'Сегодня',
		monthNames: monthNamesArray,
		monthNamesShort: monthNamesArray,
		// monthNamesShort: ['Янв', 'Фев', 'Мар', 'Апр', 'Май', 'Июн',
		// 	'Июл', 'Авг', 'Сен', 'Окт', 'Ноя', 'Дек'
		// ],
		dayNames: ['воскресенье', 'понедельник', 'вторник', 'среда', 'четверг', 'пятница', 'суббота'],
		dayNamesShort: ['Вск', 'Пнд', 'Втр', 'Срд', 'Чтв', 'Птн', 'Сбт'],
		dayNamesMin: ['Вс', 'Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб'],
		dateFormat: 'dd.mm.yy',
		firstDay: 1,
		isRTL: false
	};
	$.datepicker.setDefaults($.datepicker.regional['ru']);


	$(".datepicker").each(function(index, el) {
		var $this = $(this),
			events = $('.event'),
			dates = {};
		$('.event').each(function(index, el) {
			//content = $(el).html(),
			var date = $(el).data('date'),
				isLastDay = $(el).data('is_last_day');
			//dates.date.push({'lastDay':isLastDay})
			dates[date] = {};
			dates[date].lastDay = isLastDay;
			// dates[date].isLastDay = isLastDay;
			console.dir(dates[date])
		});
		$(".datepicker").datepicker({
			//minDate: 0,
			changeMonth: true,
			changeYear: true,
			showWeek: false,
			inline: true,
			beforeShowDay: function(date) {
				var search = $.datepicker.formatDate('dd.mm.yy', date),
					highlightClass,
					disableClass = 'futureDate';

				if (date < new Date()) {
					disableClass = 'pastDate'

				}

				if (search in dates) {
					if (!dates[search].lastDay) {
						highlightClass = "highlightSimple"
					} else {
						highlightClass = 'highlight'
					}
					return [true, highlightClass + ' ' + disableClass, search];
				} else {
					return [false, '', ""];
				}

			},
			onSelect: function(dateText, inst) {

				var eventContent = $('.event[data-date="' + dateText + '"]').html();

				$('.place-event-content').html(eventContent);
			},
		});
	});

	$('.datepicker').swipe({
		swipeLeft: function(event, direction, distance, duration, fingerCount) {
			$('.ui-datepicker-next').trigger('click');
		},
		swipeRight: function(event, direction, distance, duration, fingerCount) {
			$('.ui-datepicker-prev').trigger('click');
		},
	});


	jQuery.validator.setDefaults({
		onkeyup: false,
		onfocusout: false,
		onclick: false,
		ignore: "",
		errorPlacement: function(error, element) {},
		highlight: function(element, errorClass, validClass) {
			$(element).addClass('error');

		},
		unhighlight: function(element, errorClass, validClass) {
			$(element).removeClass('error');

		},
		submitHandler: function(form) {
			$.ajax({
				url: $(form).attr('action'),
				type: $(form).attr('method'),
				data: $(form).serialize(),
				success: function(data) {
					var container = $(form).closest('.ajax-form-wrap');
					container.find('.ajax-hide').hide();
					container.find('.confirm').show();

				}
			});

		}
	});

	$('.simple_validate').each(function(index, el) {
		$(this).validate({

		});
	});


	// $('.show-filter-btn').on('click', function(e) {
	// 	openMore('.show-filter-btn', '.main-filter-form', 'Показать фильтр', 'скрыть фильтр');
	// });


	 $('body').on('click', '.green-form .add-field', function( e ) {
		$(this).closest('.green-form').find('.hidden-field:hidden').first().show();
	});

	$('.add-file').on('change', function() {
		var filepath = $(this).val();
		var filename = (filepath.match(/(?:^|\/|\\)([^\\\/]+)$/)[1]);
		$('.place_file', $(this).parent()).text(filename);
	});

	$('.open-img').magnificPopup({
		type: 'image',
		gallery: {
			enabled: true
		},


	});

	$('.accordion .accordion-head').on('click', function() {

		if (animated) {
			return false;
		}
		animated = true;
		var $this = $(this);
		var opened = $('.accordion').find('.accordion-head.opened');
		opened.next('.accordion-body').slideUp(function() {
			opened.removeClass('opened');
			animated = false;
		});
		if (!$this.hasClass('opened')) {
			$this.next('.accordion-body').slideDown(function() {
				$this.addClass('opened');
				animated = false;
			});
		}
	})

	$('body').on('click', '.brand-doc-type .result-item .btn', function(e){
		e.preventDefault();
		$(this).parents('.result-item').find('.brand-doc-name').show(0);
		$(this).hide(0);
	});

	$('body').on('click', '.show_next_brand', function(e){
		e.preventDefault();
		function showBrands(_this){
			_this.parents('.result-block').find('.brand-item:hidden').eq(0).show(0);
		}

		if($(this).parents('.result-block').find('.brand-item:hidden').length == 1){
			showBrands($(this));
			$(this).hide(0);
		} else {
			showBrands($(this));
		}
	});

	$('body').on('click', '.show_all_params', function(e){
		e.preventDefault();
		function showActParams(_this){
			_this.parents('.item_body').find('.param-item:hidden').eq(0).show(0);
		}

		if($(this).parents('.item_body').find('.param-item:hidden').length == 1){
			showActParams($(this));
			//$(this).hide(0);
			$(this).empty();
			$(this).html("СКРЫТЬ");
		} else {
			showActParams($(this));
			$(this).parents('.item_body').find('.param-item').hide(0);
			//$(this).show(0);
			$(this).empty();
			$(this).html("ВСЕ ХАР-КИ");
		}
	});	

});


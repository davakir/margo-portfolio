$(document).ready(function () {
	var duration = 3000,
		delay = 1000,
		opaque = {opacity: 1},
		transparent = {opacity: 0},
		elements = $("section.animation .part");
	
	elements.each(function(index, element) {
		var id = '#' + element.id;
		
		var iterator = (function () {
			var backgrounds = $.map($(id + ' .img').toArray(), function (item) {
					return $(item);
				}),
				length = backgrounds.length,
				index = 0;

			return {
				next: function () {
					if (index == length)
						index = 0;
					return backgrounds[index++];
				},
				index: index
			};
		})();

		var traverse = function () {
			var fire = false;

			iterator.next()
				.animate(opaque, duration)
				.delay(delay)
				.animate(
					transparent,
					{
						duration: duration,
						progress: function (p, n, r) {
							if (!fire && n > 0.5) {
								fire = true;
								traverse();
							}
						}
					}
				);
		};

		traverse();
	});
});
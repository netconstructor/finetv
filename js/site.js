
	jQuery(document).ready(function($){

		if($('div#datetime-panel').length > 0)
		{
			startTime();
		}

		

		// Your JavaScript goes here
		function startTime()
		{

			var today = new Date();
			var year = today.getFullYear();
			var month = today.getMonth()+1;
			var day = today.getDate();

			var hour=today.getHours();
			var minute=today.getMinutes();

			// add a zero in front of numbers < 10
			hour = checkTime(hour);
			minute=checkTime(minute);

			month=checkTime(month);
			day=checkTime(day);


			$('#datetime-panel').text(year+"-"+month+"-"+day+" "+hour+":"+minute);
			
			t=setTimeout(function(){startTime()},500);
		}
		function checkTime(i)
		{
			if (i<10)
			  {
			  i="0" + i;
			  }
			return i;
		}

		

		$('#slider').fullSlider();

		

});


(function($){
	$.fn.fullSlider = function(){
		return this.each(function(){



			function getSliderItem($source)
			{
				$slides = $source.find('> div');

				//console.log($slides.length);

				if($slides.length == 1)
				{
					// Refresh the page sometimes to account for new code...
					if(refreshCounter > 60)
					{
						$('body').fadeOut(1000,function(){
							location.reload();
						});
						
					}else {

						

				
						// Do a hit
						$.get(document.URL, function(data) {

						  $(data).find('div#'+container_id+' > div').each(function(){
						  	
						  	$(this).appendTo($source);

						  });
						  
						}).error(function() { 
							
							$fallback_source.find('> div').each(function(){
							$(this).appendTo($source);
						});

						});
					}

				} 
				else if($slides.length == 0) // If ther is some error
				{
					// Refresh the page to try to avoid a error loop... 
					if(refreshCounter > 3)
					{
						//location.reload();
					}

					refreshCounter++;
					return false;
				}

				refreshCounter++;

				//Grab the first item and remove it from the source

				$slide_object = $slides.filter(':first').appendTo($fallback_source);
				$fallback_source.filter(':first').remove();

				return $slide_object;
			}

			// 1. setup

				// Capture a cache of all div the elements in the slider container
				

				var $container = $(this);
				var container_id = $(this).attr("id");

				/*slides = [],
				currentItem = 1,
				total = 0;*/

				var refreshCounter = 0;

				var $source = $('<div id="source" />').hide().appendTo('body');

				var $fallback_source = $('<div id="fallback_source" />').hide().appendTo('body');

				$('<div id="transition_element" />').appendTo('body');

			

				// Capture the cache 
				// TODO: Create ofline slides to, have incase the internet conection fails...
/*
				$container.find('> div').each(function(){
					slides.push('<div class="slider-item">'+$(this).html()+'</div>');
				});

				total = slides.length;*/

				//console.log(slides);

				// Comp the list down to one

				$container.find('> div').clone().appendTo($fallback_source);
				$container.find('> div').filter(':gt(0)').appendTo($source);


				$(window).resize(function() {
					var height = parseInt($(window).innerHeight())-30;
  					$container.css("height",height);
  					$container.find('> div:visible').vAlign();
				});


				

				setTimeout(function()
					{
						$(window).resize();
					}, 10);

				$container.find('> div').first().vAlign();
				
				
			
				
				
				

				//2. effect

				function cyckle()
				{

					var $item = getSliderItem($source);

					var duraction = "3000";

					if($item != false)
					{
						// insert a new item with opacity and height of zero
						var $insert = $item.prependTo($container).hide();

						// Get the settings for the slide...
						var slide_settings = extract_slide_settings($insert);


						// fade the last item out
						/*$container.find('> div:last').fadeOut(1000,function(){
							// increase the height of the NEW first item
							$insert.fadeIn(1000);
							// AND at the same time - decrease the height of the LAST item
							
							// finally fade the first item in (and remove the last)
							$(this).remove();
						});*/
						

						
						$('div#transition_element').transition({backgroundColor : '#fff'}, function() {
    						$container.find('> div:last').remove();
    						$insert.show().vAlign();
							$('div#transition_element').transition({backgroundColor : 'transparent'});
						});

						// Get the duraction of the slide
						duraction = slide_settings.duraction;
					}
					
					setTimeout(cyckle, duraction);
					
				}
				// Prepare the first slide, for the slider, so the first slide  has duraction...
				var first_slide = $container.find('> div:first');
				var slide_settings = extract_slide_settings(first_slide);
				setTimeout(cyckle, slide_settings.duraction);				
		});

		


		
		function extract_slide_settings(slider_element)
		{

			var duraction = $(slider_element).find('input[name="slide_duraction"]').val() * 1000;

			if(duraction <= 0 || duraction == undefined || duraction == "")
			{
				duraction = 10;
			}

			var to = $(slider_element).find('input[name="slide_show_to"]').val();
			var from =  $(slider_element).find('input[name="slide_show_from"]').val();

			var slide_settings = new Object();
			
			slide_settings.duraction = duraction;
			slide_settings.to = to;
			slide_settings.from = from;

			return slide_settings;

		}

		function pausecomp(ms) {
			ms += new Date().getTime();
			while (new Date() < ms){}
		}



	};
})(jQuery);

(function($){
	$.fn.vAlign = function(){
    return this.each(function(i){
    var ah = $(this).height();
    var ph = $(this).parent().height();
    var mh = Math.ceil((ph-ah) / 2);
    $(this).css('margin-top', mh);
    });
};
})(jQuery);
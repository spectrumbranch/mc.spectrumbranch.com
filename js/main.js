function lazyLoadImage(src, imgclass, successCallback) {
	var img = $("<img />").attr('src', src).addClass(imgclass)
		.load(function() {
			if (!this.complete || typeof this.naturalWidth == "undefined" || this.naturalWidth == 0) {
				console.log('Broken image');
			} else {
				successCallback(img);
			}
		});
}

$(function() {
	$('.player').each(function() {
		var player = $(this);

		$.ajax({
			url: "get-texture.php",
			type: 'GET',
			data: {uuid: player.data("uuid")},
			dataType: 'json',
			success: function(data) {
				var player_head_container = player.find('.player-head-container');
				var encoded_texture = encodeURIComponent(data.texture);
				lazyLoadImage(
					'playerskin.php?texture=' + encoded_texture + '&size=' + player.data("size"),
					'player-head',
					function(img){
						player_head_container.find('.loader').remove();
						player_head_container.prepend(img);
					}
				);

				// Hats for non-old skins only
				if (!data.oldskin) {
					lazyLoadImage(
						'playerskin.php?texture=' + encoded_texture + '&size=' + player.data("size"),
						'player-hat',
						function(img){
							player_head_container.find('.loader').remove();
							player_head_container.append(img);
						}
					);
				}

			}
		});//end ajax

	});
});
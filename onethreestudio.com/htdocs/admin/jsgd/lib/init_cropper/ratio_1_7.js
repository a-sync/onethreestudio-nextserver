function onEndCrop( coords, dimensions ) {
  $('cropX').value = coords.x1;
  $('cropY').value = coords.y1;
  $('cropWidth').value = dimensions.width;
  $('cropHeight').value = dimensions.height;
}

Event.observe(window, 'load', function() { 
	new Cropper.Img(
		'cropImage',
		{
			ratioDim: {x: 1,y: 7},
			displayOnInit: true, 
			onEndCrop: onEndCrop
		}
	);
});
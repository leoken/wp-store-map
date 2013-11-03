// Store locator with customisations
// - custom marker
// - custom info window (using Info Bubble)
// - custom info window content (+ store hours)

var globalSettings = jQuery.parseJSON(wpmm_panel_vars.wpmm_settings);

//var ICON = new google.maps.MarkerImage(globalSettings.marker_icon, null, null,
//		new google.maps.Point(14, 13));

var SHADOW = new google.maps.MarkerImage(globalSettings.marker_shadow, null, null,
    new google.maps.Point(14, 2));

google.maps.event.addDomListener(window, 'load', function() {

  var map = new google.maps.Map(document.getElementById('map-canvas'), {
    center: new google.maps.LatLng(parseFloat(wpmm_panel_vars.lat), parseFloat(wpmm_panel_vars.lng)),
    zoom: parseInt(globalSettings.default_zoom),
    mapTypeId: eval(globalSettings.map_type)
  });

  var panelDiv = document.getElementById('panel');

  var data = new WPmmDataSource;

  var view = new storeLocator.View(map, data, {
    geolocation: true,
    features: data.getFeatures()
  });

  view.createMarker = function(store) {
    var markerOptions = {
      position: store.getLocation(),
      icon: store.getDetails().icon,
      shadow: SHADOW,
      title: store.getDetails().title,
      phone: store.getDetails().phone,
      email: store.getDetails().email,
      website: store.getDetails().website,
      territories: store.getDetails().territories,
      products: store.getDetails().products,
    };
    return new google.maps.Marker(markerOptions);
  }

  var infoBubble = new InfoBubble;
  view.getInfoWindow = function(store) {
    if (!store) {
      return infoBubble;
    }

    var details = store.getDetails();

    var html = ['<div class="store"><div class=" alignleft thumb">', 
      details.thumb , '</div><div class="title">',
      details.title, '</div><div class="address">',
      details.address, '</div><div class="phone">',
      details.phone, '</div><div class="website"><a href="', details.website , '">',
      details.website, '</a></div><div class="email"><a href="mailto:', details.email , '">',
      details.email, '</a></div><div class="territories"><strong>Territories Covered:</strong> ', 
      details.territories, '</div><div class="products"><strong>Product Lines:</strong> ', 
      details.products, '</div>',
      //'<div class="permalink misc"><a href="', 
      //details.link, '">view details</a></div>
      '</div>'].join('');

    infoBubble.setContent(jQuery(html)[0]);
    return infoBubble;
  };

  new storeLocator.Panel(panelDiv, {
    view: view
  });
});

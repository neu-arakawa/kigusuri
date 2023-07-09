/*
 * gmaps.v3.js 0.6 - Google Maps JavaScript API V2 -> V3
 *
 * Copyright (c) 2010,2013 AOK (aokura.com)
 *
 * Since:     2010-07-01
 * Modified:  2013-05-29
 *
 *
<script type="text/javascript"
src="http://maps.google.com/maps/api/js?sensor=false"></script>
<script type="text/javascript" src="gmaps.v3.js"></script>
 *
 */
(function(){ if (google && google.maps) {

var $G = google.maps;
var $W = window;
var $T = function(a){return(typeof a!='undefined');};
var $I = function(a,b){return(a instanceof b);};
var $P = 'prototype';
var $D = 100;

/*
 *  MyOverlay
 *
 */
var MyOverlay = function(map) {this.setMap(map);};
MyOverlay[$P] = new $G.OverlayView;
MyOverlay[$P].onAdd = function() {};
MyOverlay[$P].onRemove = function() {};
MyOverlay[$P].draw = function() {};

/*
 *  GMap2
 *
 */
$W.GMap2 = function(container, opts) {
  this.info = new GInfoWindow();
  this.overlays = [];
  if (!$T(opts)) opts = {};
  if (opts.size) delete opts.size;
  if (opts.googleBarOptions) delete opts.googleBarOptions;
  if (opts.mapTypes) {
    opts.mapTypeControlOptions = {};
    opts.mapTypeControlOptions.mapTypeIds = opts.mapTypes;
    delete opts.mapTypes;
  }
  opts.disableDefaultUI = true;
  opts.mapTypeId = 'roadmap';
  opts.scrollwheel = false;
  opts.zoom = 0;
  this.v3 = new $G.Map(container, opts);
  this._my_overlay = new MyOverlay(this.v3);
};

GMap2[$P].enableDragging = function() {
  this.v3.setOptions({ draggable: true });
};

GMap2[$P].disableDragging = function() {
  this.v3.setOptions({ draggable: false });
};

GMap2[$P].draggingEnabled = function() {
  var r = this.v3.get('draggable');
  return $T(r) ? r : true;
};

GMap2[$P].enableInfoWindow = function() {};
GMap2[$P].disableInfoWindow = function() {};
GMap2[$P].infoWindowEnabled = function() { return true; };

GMap2[$P].enableDoubleClickZoom = function() {
  this.v3.setOptions({ disableDoubleClickZoom: false });
};

GMap2[$P].disableDoubleClickZoom = function() {
  this.v3.setOptions({ disableDoubleClickZoom: true });
};

GMap2[$P].doubleClickZoomEnabled = function() {
  var r = this.v3.get('disableDoubleClickZoom');
  return $T(r) ? r : false;
};

GMap2[$P].enableContinuousZoom = function() {};
GMap2[$P].disableContinuousZoom = function() {};
GMap2[$P].continuousZoomEnabled = function() { return true; };
GMap2[$P].enableGoogleBar = function() {};
GMap2[$P].disableGoogleBar = function() {};

GMap2[$P].enableScrollWheelZoom = function() {
  this.v3.setOptions({ scrollwheel: true });
};

GMap2[$P].disableScrollWheelZoom = function() {
  this.v3.setOptions({ scrollwheel: false });
};

GMap2[$P].scrollWheelZoomEnabled = function() {
  var r = this.v3.get('scrollwheel');
  return $T(r) ? r : true;
};

GMap2[$P].enablePinchToZoom = function() {};
GMap2[$P].disablePinchToZoom = function() {};
GMap2[$P].pinchToZoomEnabled = function() { return true; };

GMap2[$P].getDefaultUI = function() {
  var ui = new GMapUIOptions(this.getSize());
  return ui;
}; 

GMap2[$P].setUIToDefault = function() {
  var ui = this.getDefaultUI();
  this.setUI(ui);
};

GMap2[$P].setUI = function(ui) {
  var o = {};
  o.mapTypeControlOptions = {};
  var arr = [];
  if (ui.maptypes.normal) arr.push('roadmap');
  if (ui.maptypes.satellite) arr.push('satellite');
  if (ui.maptypes.hybrid) arr.push('hybrid');
  if (ui.maptypes.physical) arr.push('terrain');
  o.mapTypeControl = (arr.length > 0);
  o.mapTypeControlOptions.mapTypeIds = arr;
  o.scrollwheel = ui.zoom.scrollwheel;
  o.disableDoubleClickZoom = !ui.zoom.doubleclick;
  o.keyboardShortcuts = ui.keyboard;
  o.navigationControlOptions = {};
  if (ui.controls.largemapcontrol3d) {
    o.navigationControl = true;
    o.navigationControlOptions.style = 3;
  }
  if (ui.controls.smallzoomcontrol3d) {
    o.navigationControl = true;
    o.navigationControlOptions.style = 1;
  }
  o.mapTypeControl = (ui.controls.maptypecontrol || ui.controls.menumaptypecontrol);
  if (ui.controls.maptypecontrol) {
    o.mapTypeControlOptions.style = 1;
  } else if (ui.controls.menumaptypecontrol) {
    o.mapTypeControlOptions.style = 2;
  }
  o.scaleControl = ui.controls.scalecontrol;
  if (ui.controls.overviewmapcontrol) {
    this.addOverlay(new GOverviewMapControl());
  }
  this.v3.setOptions(o);
};

GMap2[$P].addControl = function(control, position) {
  if (!$I(control,GControl)) return;
  control.setMap(this, position);
};

GMap2[$P].removeControl = function(control) {
  control.setMap(null);
};

GMap2[$P].getContainer = function() {
  return this.v3.getDiv();
};

GMap2[$P].getMapTypes = function() {
  return this.v3.get('mapTypeControlOptions').mapTypeIds;
};

GMap2[$P].getCurrentMapType = function() {
  return this.v3.getMapTypeId();
};

GMap2[$P].setMapType = function(type) {
  this.v3.setMapTypeId(type);
};

GMap2[$P].addMapType = function(type) {
  this.getMapTypes().push(type);
};

GMap2[$P].removeMapType = function(type) {
  var ids = this.getMapTypes();
  var len = ids.length;
  for (var i = 0; i < len; i++) {
    if (ids[i] == type) {
      ids.splice(i, 1);
      break;
    }
  }
};

GMap2[$P].isLoaded = function() {
  return (this.v3.getBounds() != null);
};

GMap2[$P].getCenter = function() {
  return this.v3.getCenter();
};

GMap2[$P].getBounds = function() {
  return this.v3.getBounds();
};

GMap2[$P].getBoundsZoomLevel = function(bounds) { return 0; };

GMap2[$P].getSize = function() {
  var node = this.v3.getDiv();
  return new GSize(parseInt(node.clientWidth), parseInt(node.clientHeight));
};

GMap2[$P].getZoom = function() {
  return this.v3.getZoom();
};

GMap2[$P].getDragObject = function() { return null; };
GMap2[$P].getEarthInstance = function(callback) {}; 

GMap2[$P].setCenter = function(center, zoom, type) {
  var o = {};
  o.center = center;
  o.type = (type ? type : this.v3.getMapTypeId());
  o.zoom = ($T(zoom) ? zoom : this.v3.getZoom());
  this.v3.setOptions(o);
};

GMap2[$P].panTo = function(center) {
  this.v3.panTo(center);
};

GMap2[$P].panBy = function(distance) {
  this.v3.panBy(distance.width, distance.height);
};

GMap2[$P].panDirection = function(dx, dy) {};

GMap2[$P].setZoom = function(level) {
  this.v3.setZoom(level);
};

GMap2[$P].zoomIn = function(latlng, doCenter, doContinuousZoom) {
  var z = this.v3.getZoom();
  this.v3.setZoom(++z);
  if (latlng) this.v3.setCenter(latlng);
};

GMap2[$P].zoomOut = function(latlng, doContinuousZoom) {
  var z = this.v3.getZoom();
  this.v3.setZoom(--z);
  if (latlng) this.v3.setCenter(latlng);
};

GMap2[$P].addOverlay = function(overlay) {
  if (!$I(overlay,GOverlay)) return;
  overlay.setMap(this);
  this.overlays.push(overlay);
};

GMap2[$P].removeOverlay = function(overlay) {
  if (!$I(overlay,GOverlay)) return;
  for (var i in this.overlays) {
    if (this.overlays[i] == overlay) {
      this.overlays[i].setMap(null);
      this.overlays.splice(i, 1);
      break;
    }
  }
};

GMap2[$P].clearOverlays = function() {
  for (var i in this.overlays) {
    this.overlays[i].setMap(null);
  }
  this.overlays.length = 0;
};

GMap2[$P].getPane = function(pane) {
  var p = this._my_overlay.getPanes();
  var n = null;
  switch (pane) {
  case 0: n = p.mapPane; break;
  case 1: n = p.overlayLayer; break;
  case 2: n = p.overlayShadow; break;
  case 4: n = p.overlayImage; break;
  case 5: n = p.floatShadow; break;
  case 6: n = p.overlayMouseTarget; break;
  case 7: n = p.floatPane; break;
  }
  return n;
}; 

GMap2[$P].openInfoWindow = function(latlng, node, opts) {
  var infoWindowOptions = {
    content: node,
    position: latlng
  };
  if (opts) {
    if (opts.maxWidth)
      infoWindowOptions.maxWidth = opts.maxWidth;
    if (opts.pixelOffset)
      infoWindowOptions.pixelOffset = opts.pixelOffset;
  }
  this.info.v3.setOptions(infoWindowOptions);
  var f = (opts && opts.anchor);
  this.info.v3.open(this.v3, (f ? opts.anchor.v3 : null));
};

GMap2[$P].openInfoWindowHtml = function(latlng, html, opts) {
  this.openInfoWindow(latlng, html, opts);
};

GMap2[$P].closeInfoWindow = function() {
  this.info.v3.close();
};

GMap2[$P].getInfoWindow = function() {
  return this.info;
};

GMap2[$P].fromContainerPixelToLatLng = function(pixel) {
  var p = this._my_overlay.getProjection();
  return p.fromContainerPixelToLatLng(pixel);
};

GMap2[$P].fromLatLngToContainerPixel = function(latlng) {
  var p = this._my_overlay.getProjection();
  return p.fromLatLngToContainerPixel(latlng);
};

GMap2[$P].fromLatLngToDivPixel = function(latlng) {
  var p = this._my_overlay.getProjection();
  return p.fromLatLngToDivPixel(latlng);
};

GMap2[$P].fromDivPixelToLatLng = function(pixel) {
  var p = this._my_overlay.getProjection();
  return p.fromDivPixelToLatLng(pixel);
};

GMap2[$P].enableRotation = function(level) {
  this.v3.setOptions({ rotateControl: true });
};
GMap2[$P].disableRotation = function() {
  this.v3.setOptions({ rotateControl: false });
};
GMap2[$P].rotationEnabled = function() {
  var id = this.v3.getMapTypeId();
  return (id == 'satellite' || id == 'hybrid')
};
GMap2[$P].isRotatable = function() {
  var id = this.v3.getMapTypeId();
  return (id == 'satellite' || id == 'hybrid') && this.v3.get('rotateControl');
};
GMap2[$P].changeHeading = function(heading) {
  this.v3.setHeading(heading);
};

/*
 *  GBounds
 *
 */
$W.GBounds = function() {
  var a = [];
  var b = [];
  for (var i in arguments) {
    a.push(i.x);
    b.push(i.y);
  }
  this.minX = a.slice(0).sort()[0];
  this.minY = b.slice(0).sort()[0];
  this.maxX = a.slice(0).sort().reverse()[0];
  this.maxY = b.slice(0).sort().reverse()[0];
};

/*
 *  GBrowserIsCompatible
 *
 */
$W.GBrowserIsCompatible = function() { return true; };

/*
 *  GInfoWindow
 *
 */
$W.GInfoWindow = function() {
  this.v3 = new $G.InfoWindow();
};

GInfoWindow[$P].getPoint = function() {
  return this.v3.getPosition();
};

/*
 *  GLatLng
 *
 */
$W.GLatLng = $G.LatLng;

/*
 *  GLatLngBounds
 *
 */
$W.GLatLngBounds = $G.LatLngBounds;

/*
 *  GMapPane
 *
 */
$W.G_MAP_MAP_PANE                 = 0;
$W.G_MAP_OVERLAY_LAYER_PANE       = 1;
$W.G_MAP_MARKER_SHADOW_PANE       = 2;
$W.G_MAP_MARKER_PANE              = 4;
$W.G_MAP_FLOAT_SHADOW_PANE        = 5;
$W.G_MAP_MARKER_MOUSE_TARGET_PANE = 6;
$W.G_MAP_FLOAT_PANE               = 7;

/*
 *  GPoint
 *
 */
$W.GPoint = $G.Point;

/*
 *  GSize
 *
 */
$W.GSize = $G.Size;
$W.ZERO = new GSize(0, 0);

/*
 *  GUnload
 *
 */
$W.GUnload = function() {};

/*
 *  GControl
 *
 */
$W.GControl = function(printable, selectable) {
  this.map = null;
  this.mapOptions = {};
  this.printable = (printable ? printable : false);
  this.selectable = (selectable ? selectable : false);
  this.anchor = null;
  this.node = null;
};

GControl[$P].setMap = function(map, pos) {
  if (map) { /* addControl */
    if (!$T(pos)) pos = this.getDefaultPosition();
    this.node = this.initialize(map);
    if (pos) {
      this.node.style.margin = pos.offset.height + 'px '
                             + pos.offset.width + 'px';
      map.v3.controls[pos.anchor].push(this.node);
      this.anchor = pos.anchor;
    }
  } else { /* removeControl */
    map.v3.controls[this.anchor].forEach(function(e, i){
      if (e == this.node) {
        map.v3.controls[this.anchor].removeAt(i);
      }
    });
  }
  this.map = map;
};

GControl[$P].printable = function() {
  return this.printable;
};

GControl[$P].selectable = function() {
  return this.selectable;
};

GControl[$P].initialize = function(map) { return null; };
GControl[$P].getDefaultPosition = function() { return null; };

/*
 *  GControlAnchor
 *
 */
$W.G_ANCHOR_TOP_LEFT     = 1;
$W.G_ANCHOR_TOP_RIGHT    = 3;
$W.G_ANCHOR_BOTTOM_LEFT  = 10;
$W.G_ANCHOR_BOTTOM_RIGHT = 12;

/* original */
G_ANCHOR_TOP    = 2;
G_ANCHOR_LEFT   = 5;
G_ANCHOR_RIGHT  = 7;
G_ANCHOR_BOTTOM = 11;

/*
 *  GEvent
 *
 */
$W.GEvent = {
  addListener: function(source, event, handler) {
    var f1 = $G.event.addListener;
    var f2 = $G.event.addListenerOnce;
    if ($I(source,GMap2)) {
      switch (event) {
      case 'load':
        return f2(source.v3, 'tilesloaded', handler);
        break;
      case 'click':
      case 'dblclick':
        return f1(source.v3, event, function(me) {
          handler(null, me.latLng, null);
        });
        break;
      case 'mouseover':
      case 'mouseout':
      case 'mousemove':
        return f1(source.v3, event, function(me) {
          handler(me.latLng);
        });
        break;
      case 'moveend':
        return f1(source.v3, 'center_changed', handler);
        break;
      default:
        return f1(source.v3, event, handler);
        break;
      }
    } else if ($I(source,GMarker)) {
      switch (event) {
      case 'dragstart':
      case 'drag':
      case 'dragend':
        return f1(source.v3, event, function(me) {
          handler(me.latLng);
        });
        break;
      default:
        return f1(source.v3, event, handler);
        break;
      }
    } else if ($I(source,GStreetviewPanorama)) {
      switch (event) {
      case 'yawchanged':
        return f1(source.v3, 'pov_changed', function() {
          handler(source.getPOV().yaw);
        });
        break;
      case 'pitchchanged':
        return f1(source.v3, 'pov_changed', function() {
          handler(source.getPOV().pitch);
        });
        break;
      case 'zoomchanged':
        return f1(source.v3, 'pov_changed', function() {
          handler(source.getPOV().zoom);
        });
        break;
      case 'initialized':
        return f1(source.v3, 'pano_changed', function() {
          handler({
            latlng: source.getLatLng(),
            pov: source.getPOV(),
            description: '',
            panoId: source.getPanoId()
          });
        });
        break;
      }
    }
  },

  addDomListener: function(source, event, handler) {
    return $G.event.addDomListener(source, event, handler);
  },

  trigger: function() {
    var a = arguments;
    a[0] = a[0].v3;
    if ($I(a[0],$G.Map)) {
      switch (a[1]) {
      case 'click':
      case 'dblclick':
        a[2] = { latLng:a[3] };
        break;
      case 'mouseover':
      case 'mouseout':
      case 'mousemove':
        a[2] = { latLng:a[2] };
        break;
      }
    } else if ($I(a[0],$G.Marker)) {
      switch (a[1]) {
      case 'dragstart':
      case 'drag':
      case 'dragend':
        a[2] = { latLng:a[2] };
        break;
      }
    }
    $G.event.trigger.apply(this, a);
  }, 

  bind: function(source, event, object, handler) {
    if ($I(source,GMap2)) {
      switch (event) {
      case 'click':
      case 'dblclick':
        return $G.event.addListener(source.v3, event, function(me) {
          handler.call(object, null, me.latLng, null);
        });
        break;
      default:
        return $G.event.addListener(source.v3, event, function() {
          handler.call(object);
        });
      }
    }
  },

  removeListener: function(handle) {
    $G.event.removeListener(handle);
  }

};

/*
 *  GControl
 *
 */
$W.GSmallMapControl = function() {
  this.mapOptions = {
    navigationControl: true,
    navigationControlOptions: {
      position: G_ANCHOR_TOP_LEFT,
      style: $G.NavigationControlStyle.SMALL
    }
  };
};
GSmallMapControl[$P] = new GControl();

GSmallMapControl[$P].setMap = function(map, pos) {
  var mapOptions;
  if (map) {
    mapOptions = this.mapOptions;
    if (pos) mapOptions.navigationControlOptions.position = pos.anchor;
    map.v3.setOptions(mapOptions);
    this.map = map;
  } else {
    mapOptions = { navigationControl:false };
    this.map.v3.setOptions(mapOptions);
  }
};

$W.GLargeMapControl = function() {
  this.mapOptions = {
    navigationControl: true,
    navigationControlOptions: {
      position: G_ANCHOR_TOP_LEFT,
      style: $G.NavigationControlStyle.ZOOM_PAN
    }
  };
};
GLargeMapControl[$P] = new GControl();

GLargeMapControl[$P].setMap = function(map, pos) {
  var mapOptions;
  if (map) {
    mapOptions = this.mapOptions;
    if (pos) mapOptions.navigationControlOptions.position = pos.anchor;
    map.v3.setOptions(mapOptions);
    this.map = map;
  } else {
    mapOptions = { navigationControl:false };
    this.map.v3.setOptions(mapOptions);
  }
};

$W.GMapTypeControl = function() {
  this.mapOptions = {
    mapTypeControl: true,
    mapTypeControlOptions: {
      position: G_ANCHOR_TOP_RIGHT,
      style: $G.MapTypeControlStyle.HORIZONTAL_BAR,
      mapTypeIds: ['roadmap','satellite','hybrid']
    }
  };
}; 
GMapTypeControl[$P] = new GControl();

GMapTypeControl[$P].setMap = function(map, pos) {
  var mapOptions;
  if (map) {
    mapOptions = this.mapOptions;
    if (pos) mapOptions.mapTypeControlOptions.position = pos.anchor;
    map.v3.setOptions(mapOptions);
    this.map = map;
  } else {
    mapOptions = { mapTypeControl:false };
    this.map.v3.setOptions(mapOptions);
  }
};

$W.GOverviewMapControl = function() {
  this.mapOptions = {
    overviewMapControl: true,
    overviewMapControlOptions: {
      opened: true
    }
  };
};
GOverviewMapControl[$P] = new GControl();

GOverviewMapControl[$P].setMap = function(map) {
  var mapOptions;
  if (map) {
    mapOptions = this.mapOptions;
    map.v3.setOptions(mapOptions);
    this.map = map;
  } else {
    mapOptions = { overviewMapControl:false };
    this.map.v3.setOptions(mapOptions);
  }
};

GOverviewMapControl[$P].getDefaultPosition = function() {
  return new GControlPosition(G_ANCHOR_BOTTOM_RIGHT, ZERO);
};

/*
 *  http://maps.gstatic.com/intl/en_us/mapfiles/openhand_8_8.cur
 *  http://maps.gstatic.com/intl/en_us/mapfiles/closedhand_8_8.cur
 */

/*
 *  GCopyrightCollection
 *
 */
$W.GCopyrightCollection = function(prefix) {};

/*
 *  GControlPosition
 *
 */
$W.GControlPosition = function(anchor, offset) {
  this.anchor = anchor;
  this.offset = offset;
};

/*
 *  GHierarchicalMapTypeControl
 *
 */
$W.GHierarchicalMapTypeControl = function() {};
GHierarchicalMapTypeControl[$P].addRelationship = function(parentType, childType, childText, isDefault) {};
GHierarchicalMapTypeControl[$P].clearRelationships = function() {};

/*
 *  GMapType
 *
 */
$W.GMapType = function(layers, projection, name, opts) {
  this.id = '';
  var o = {
    getTileUrl: layers[0].getTileUrl,
    isPng: layers[0].isPng(),
    maxZoom: layers[0].maxResolution(),
    minZoom: layers[0].minResolution(),
    name: name,
    opacity: layers[0].getOpacity(),
    tileSize: new $G.Size(256,256)
  };
  if (opts) {
    if (opts.alt)
      o.alt = opts.alt;
    if (opts.tileSize)
      o.tileSize = new $G.Size(opts.tileSize,opts.tileSize);
  }
  this.v3 = new $G.ImageMapType(o);
};

$W.G_HYBRID_MAP    = 'hybrid';
$W.G_NORMAL_MAP    = 'roadmap';
$W.G_SATELLITE_MAP = 'satellite';
$W.G_PHYSICAL_MAP  = 'terrain';
$W.G_DEFAULT_MAP_TYPES = [G_NORMAL_MAP,G_SATELLITE_MAP,G_HYBRID_MAP];

/*
 *  GMapUIOptions
 *
 */
$W.GMapUIOptions = function(opt_size) {
  var f = (opt_size.width >= 400 && opt_size.height >= 300);
  this.maptypes = {
    normal:true,
    satellite:true,
    hybrid:true,
    physical:true
  };
  this.zoom = {
    scrollwheel:true,
    doubleclick:true
  };
  this.keyboard = true;
  this.controls = {
    largemapcontrol3d:f,
    smallzoomcontrol3d:!f,
    maptypecontrol:f,
    menumaptypecontrol:!f,
    scalecontrol:f,
    overviewmapcontrol:false
  };
};

/*
 *  GMenuMapTypeControl
 *
 */
$W.GMenuMapTypeControl = function(useShortNames) {
  this.mapOptions = {
    mapTypeControl: true,
    mapTypeControlOptions: {
      position: G_ANCHOR_TOP_RIGHT,
      style: $G.MapTypeControlStyle.DROPDOWN_MENU,
      mapTypeIds: ['roadmap','satellite','hybrid']
    }
  };
}; 
GMenuMapTypeControl[$P] = new GControl();

GMenuMapTypeControl[$P].setMap = function(map, pos) {
  var mapOptions;
  if (map) {
    mapOptions = this.mapOptions;
    if (pos) mapOptions.mapTypeControlOptions.position = pos.anchor;
    map.v3.setOptions(mapOptions);
    this.map = map;
  } else {
    mapOptions = { mapTypeControl:false };
    this.map.v3.setOptions(mapOptions);
  }
};

/*
 *  GNavLabelControl
 *
 */
$W.GNavLabelControl = function() {};
GNavLabelControl[$P] = new GControl();

GNavLabelControl[$P].initialize = function(map) {

  this.map = map.v3;
  var node = map.v3.getDiv();

  var controlUI = document.createElement('div');
  controlUI.id = '_nav_label_control';
  controlUI.style.border = '1px solid #b1b1b1';
  node.appendChild(controlUI);

  var controlText = document.createElement('div');
  controlText.style.backgroundColor = 'white';
  controlText.style.margin = '1px 0';
  controlText.style.padding = '0 6px';

  var geocoder = new $G.Geocoder();
  $G.event.addListener(map.v3, 'tilesloaded', function() {
    var latlng = map.v3.getCenter();
    var zoom = map.v3.getZoom();
    for (var i = controlText.childNodes.length-1; i>=0; i--) {
      $G.event.clearInstanceListeners(controlText.childNodes[i]);
      controlText.removeChild(controlText.childNodes[i]);
    }
    geocoder.geocode({'location':latlng}, function(results, status) {
      if (status == $G.GeocoderStatus.OK) {
        var t = document.createElement('b');
        t.innerHTML = "\u73fe\u5728\u5730\u3000";
        controlText.appendChild(t);
        var a = results[0].address_components.reverse();
        var ctrl = [];
        for (var i = 0, c = 0; i < a.length; i++) {
          var b = a[i].types;
          if ((b && b[0] == 'country') ||
              (zoom >= 7 && b && b[0].match(/^administrative/)) ||
              (zoom >= 10 && b && b[0].match(/^locality/)) ||
              (zoom >= 16 && b && b[0].match(/^sublocality_level_[1-2]/))) {
            if (c > 0) {
              var sep = document.createTextNode(' > ');
              controlText.appendChild(sep);
            }
            ctrl[c] = document.createElement('a');
            ctrl[c].href = 'javascript:void(0)';
            ctrl[c].innerHTML = a[i].short_name;
            if (b[0] == 'country')
              ctrl[c].alt = '3';
            else if (b[0].match(/^administrative/))
              ctrl[c].alt = '7';
            else if (b[0].match(/^locality/))
              ctrl[c].alt = '10';
            else if (b[0].match(/^sublocality/))
              ctrl[c].alt = '16';
            $G.event.addDomListener(ctrl[c], 'click', function() {
              map.v3.setZoom(parseInt(this.alt));
            });
            controlText.appendChild(ctrl[c]);
            c++;
          }
        }
      }
    });
  });
  controlUI.appendChild(controlText);
  return controlUI;
};

GNavLabelControl[$P].getDefaultPosition = function() {
  return new GControlPosition(G_ANCHOR_RIGHT, new GSize(5,0));
};

/*
 *  GOverlay
 *
 */
$W.GOverlay = function() {
  this.map = null;
  this.v3 = new MyOverlay();
};

GOverlay[$P].setMap = function(map) {
  if (map) { /* addOverlay */
    this.initialize(map);
    this.v3.setMap(map.v3);
    var o = this;
    this.mel = $G.event.addListener(map.v3, 'bounds_changed', function() {
      o.redraw(true);
    });
    o.redraw(true);
  } else { /* clearOverlay */
    this.remove();
    $G.event.removeListener(this.mel);
    this.v3.setMap(null);
  }
  this.map = map;
};

GOverlay[$P].initialize = function(map) {};
GOverlay[$P].remove = function() {};
GOverlay[$P].redraw = function(force) {};

/*
 *  GGroundOverlay
 *
 */
$W.GGroundOverlay = function(imageUrl, bounds) {
  this.v3 = new $G.GroundOverlay(imageUrl, bounds);
};
GGroundOverlay[$P] = new GOverlay();

/*
 *  GIcon
 *
 */
$W.GIcon = function(copy, image) {
  this.v3 = {};
  this.image;
  this.shadow;
  this.iconSize;
  this.shadowSize;
  this.iconAnchor;
  this.infoWindowAnchor;
  this.printImage;
  this.mozPrintImage;
  this.printShadow;
  this.transparent;
  this.imageMap;
  this.maxHeight;
  this.dragCrossImage;
  this.dragCrossSize;
  this.dragCrossAnchor;
  if (copy && $I(copy,GIcon)) {
    this.image = copy.image;
    this.shadow = copy.shadow;
    this.iconSize = copy.iconSize;
    this.shadowSize = copy.shadowSize;
    this.iconAnchor = copy.iconAnchor;
    this.infoWindowAnchor = copy.infoWindowAnchor;
    this.printImage = copy.printImage;
    this.mozPrintImage = copy.mozPrintImage;
    this.printShadow = copy.printShadow;
    this.transparent = copy.transparent;
    this.imageMap = copy.imageMap;
    this.maxHeight = copy.maxHeight;
    this.dragCrossImage = copy.dragCrossImage;
    this.dragCrossSize = copy.dragCrossSize;
    this.dragCrossAnchor = copy.dragCrossAnchor;
  }
  if (image) this.image = image;
};

GIcon[$P].init = function() {
  this.v3.icon = new $G.MarkerImage(this.image, this.iconSize, null, this.iconAnchor);
  if (this.shadow)
    this.v3.shadow = new $G.MarkerImage(this.shadow, this.shadowSize, null, this.iconAnchor);
  return this.v3.icon;
};

$W.G_DEFAULT_ICON = new GIcon();
G_DEFAULT_ICON.image = 'http://maps.gstatic.com/intl/ja_ALL/mapfiles/marker.png';
G_DEFAULT_ICON.shadow = 'http://maps.gstatic.com/intl/ja_ALL/mapfiles/shadow50.png';
G_DEFAULT_ICON.iconSize = new GSize(20,34);
G_DEFAULT_ICON.shadowSize = new GSize(37,34);
G_DEFAULT_ICON.iconAnchor = new GPoint(9,34);
G_DEFAULT_ICON.dragCrossImage = 'http://maps.gstatic.com/intl/ja_ALL/mapfiles/drag_cross_67_16.png';
G_DEFAULT_ICON.dragCrossSize = new GSize(16, 16);
G_DEFAULT_ICON.dragCrossAnchor = new GPoint(7, 9);

/*
 *  GMarker
 *
 */
$W.GMarker = function(latlng, opts) {
  this.icon = null;
  if (!$T(opts)) opts = {};
  if (opts.hide) opts.visible = false;
  opts.position = latlng;
  if ($I(opts.icon,GIcon)) {
    this.icon = opts.icon;
    opts.icon = this.icon.init();
    if (this.icon.v3.shadow) {
      opts.shadow = this.icon.v3.shadow;
    }
  }
  this.v3 = new $G.Marker(opts);
};
GMarker[$P] = new GOverlay();

GMarker[$P].setMap = function(map) {
  if (map) {
    this.v3.setMap(map.v3);
  } else {
    this.v3.setMap(null);
  }
  this.map = map;
};

GMarker[$P].openInfoWindow = function(content, opts) {
  this.openInfoWindowHtml(content, opts);
};

GMarker[$P].openInfoWindowHtml = function(html, opts) {
  if (!$T(opts)) opts = {};
  opts.anchor = this;
  var latlng = this.v3.getPosition();
  if (this.map) this.map.openInfoWindowHtml(latlng, html, opts);
};

GMarker[$P].getIcon = function() {
  return this.icon;
};

GMarker[$P].getTitle = function() {
  this.v3.getTitle();
};

GMarker[$P].getLatLng = function() {
  this.v3.getPosition();
};

GMarker[$P].setLatLng = function(latlng) {
  this.v3.setPosition(latlng);
};

GMarker[$P].enableDragging = function() {
  this.v3.setDraggable(true);
};

GMarker[$P].disableDragging = function() {
  this.v3.setDraggable(false);
};

GMarker[$P].draggable = function() {
  this.v3.getDraggable();
};

GMarker[$P].draggingEnabled = function() {
  this.v3.getDraggable();
};

GMarker[$P].setImage = function(url) {
  if (this.icon) {
    this.icon.image = url;
    this.v3.setIcon(this.icon.init());
  } else {
    this.v3.setIcon(url);
  }
};

GMarker[$P].hide = function() {
  this.v3.setVisible(false);
};

GMarker[$P].show = function() {
  this.v3.setVisible(true);
};

GMarker[$P].isHidden = function() {
  return (this.v3.getVisible() == false);
};

/*
 *  GPolygon
 *
 */
$W.GPolygon = function(latlngs, color, weight, opacity, fcolor, fopacity, opts) {
  var o = {
    fillColor: ($T(fcolor) ? fcolor : '#0040FF'),
    fillOpacity: ($T(fopacity) ? fopacity : 0.20),
    paths: latlngs,
    strokeColor: ($T(color) ? color : 'blue'),
    strokeOpacity: ($T(opacity) ? opacity : 0.45),
    strokeWeight: ($T(weight) ? weight : 5)
  };
  if (opts) {
    if ($T(opts.clickable)) o.clickable = opts.clickable;
  } 
  this.v3 = new $G.Polygon(o);
};
GPolygon[$P] = new GOverlay();

/*
 *  GPolyline
 *
 */
$W.GPolyline = function(latlngs, color, weight, opacity, opts) {
  var o = {
    path: latlngs,
    strokeColor: ($T(color) ? color : 'blue'),
    strokeOpacity: ($T(opacity) ? opacity : 0.45),
    strokeWeight: ($T(weight) ? weight : 5)
  };
  if (opts) {
    if ($T(opts.clickable)) o.clickable = opts.clickable;
    if ($T(opts.geodesic)) o.geodesic = opts.geodesic;
  } 
  this.v3 = new $G.Polyline(o);
};
GPolyline[$P] = new GOverlay();

GPolyline[$P].getLength = function() { return 0; };

/*
 *  GTileLayer
 *
 */
$W.GTileLayer = function(copyrights, minResolution, maxResolution, options) {
  this.opacity = 1.0;
  this.isPng = true;
  this.maxZoom = 0;
  this.minZoom = 0;
  if ($T(maxResolution)) this.maxZoom = maxResolution;
  if ($T(minResolution)) this.minZoom = minResolution;
  if (options) {
    if ($T(options.opacity)) this.opacity = options.opacity;
    if ($T(options.isPng)) this.isPng = options.isPng;
  }
};

GTileLayer[$P].minResolution = function() {
  return this.minZoom;
};

GTileLayer[$P].maxResolution = function() {
  return this.maxZoom;
};

/*
 *  GClientGeocoder
 *
 */
$W.GClientGeocoder = function(cache) {
  this.v3 = new $G.Geocoder();
}; 

GClientGeocoder[$P].getLatLng = function(address, handler) {
  this.v3.geocode({ address: address }, function(results, status) {
    if (status == $G.GeocoderStatus.OK) {
      handler(results[0].geometry.location);
    } else if (status == $G.GeocoderStatus.ZERO_RESULTS ||
               status == $G.GeocoderStatus.OVER_QUERY_LIMIT) {
      setTimeout(function() {
        handler(null);
      }, $D);
    }
  });
};

var searchCountry = function(a) {
  for (var i in a) {
    for (var j in a[i].types) {
      if (a[i].types[j] == 'country') {
        return a[i].short_name;
      }
    }
  }
  return '';
};

GClientGeocoder[$P].getLocations = function(latlng, handler) {
  var f = $I(latlng,GLatLng);
  var o = (f ? { location: latlng } : { address: latlng });
  this.v3.geocode(o, function(results, status) {
    if (status == $G.GeocoderStatus.OK) {
       var city = { Status: { code:200, request:'geocode' }, Placemark: [] };
       if (!f) latlng = results[0].geometry.location;
       city.name = latlng.lat().toFixed(6) + ',' + latlng.lng().toFixed(6);
       for (var i in results) {
         city.Placemark[i] = {};
         city.Placemark[i].Point = {};
         city.Placemark[i].Point.coordinates = [];
         city.Placemark[i].Point.coordinates[0] = results[i].geometry.location.lng();
         city.Placemark[i].Point.coordinates[1] = results[i].geometry.location.lat();
         city.Placemark[i].address = results[i].formatted_address;
         city.Placemark[i].AddressDetails = {};
         city.Placemark[i].AddressDetails.Accuracy = '';
         city.Placemark[i].AddressDetails.Country = {};
         city.Placemark[i].AddressDetails.Country.CountryNameCode
           = searchCountry(results[i].address_components);
       }
       handler(city);
    } else if (status == $G.GeocoderStatus.ZERO_RESULTS) {
      handler(null);
    }
  });
};

/*
 *  GDirections
 *
 */
$W.GDirections = function(map, panel) {
  this.service = new $G.DirectionsService();
  this.renderer = new $G.DirectionsRenderer();
  if (map) this.renderer.setMap(map.v3);
  if (panel) this.renderer.setPanel(panel);
};

GDirections[$P].load = function(query, opts) {
  var a = query.indexOf('to:', 0);
  var start = query.substring(5, a);
  var end = query.substring(a+3);
  var disp = this.renderer;
  var request = {
    origin:start,
    destination:end,
    travelMode: $G.DirectionsTravelMode.DRIVING
  };
  if (opts) {
    if ($T(opts.travelMode))
      request.travelMode = opts.travelMode;
    if ($T(opts.avoidHighways))
      request.avoidHighways = opts.avoidHighways;
    if ($T(opts.preserveViewport))
      disp.setOptions({preserveViewport:opts.preserveViewport});
  }
  this.service.route(request, function(result, status) {
    if (status == $G.DirectionsStatus.OK) {
      disp.setDirections(result);
    }
  });
};

/*
 *  GDownloadUrl
 *
 */
$W.GDownloadUrl = function(url, onload, postBody, postContentType) {
  var req = null;
  if (window.XMLHttpRequest &&
      (window.location.protocol !== 'file:' || !window.ActiveXObject)) {
    req = new window.XMLHttpRequest();
  } else {
    try {
      req = new window.ActiveXObject('Microsoft.XMLHTTP');
    } catch(e) {}
  }
  if (req) {
    req.onreadystatechange = function(e) {
      if (req.readyState == 4) {
        if (req.status == 200 || req.status == 304 || req.status == 0) {
          onload(req.responseText);
        }
      }
    };
    req.open('get', url, true);
    req.send(null);
  }
};

/*
 *  GGeoXml
 *
 */
$W.GGeoXml = function(urlOfXml) {
  this.v3 = new $G.KmlLayer(urlOfXml, {
    preserveViewport: true
  });
};
GGeoXml[$P] = new GOverlay();

/*
 *  GStreetviewPanorama
 *
 */
var GP2SVP = function(a) {
  var b = { heading:0,pitch:0,zoom:0 };
  if ($T(a)) {
    if ($T(a.yaw)) b.heading = a.yaw;
    if ($T(a.pitch)) b.pitch = a.pitch;
    if ($T(a.zoom)) b.zoom = a.zoom;
  }
  return b;
};

var SVP2GP = function(a) {
  var b = { yaw:0,pitch:0,zoom:0 };
  if ($T(a)) {
    if ($T(a.heading)) {
      b.yaw = Math.round(a.heading * 100) / 100;
      while (b.yaw < 0) b.yaw += 360;
    }
    if ($T(a.pitch)) b.pitch = a.pitch;
    if ($T(a.zoom)) b.zoom = a.zoom;
  }
  return b;
};

$W.GStreetviewPanorama = function(container, opts) {
  if (!$T(opts)) opts = {};
  if (opts.latlng) {
    opts.position = opts.latlng;
    delete opts.latlng;
  }
  opts.pov = GP2SVP(opts.pov);
  this.v3 = new $G.StreetViewPanorama(container, opts);
}; 

GStreetviewPanorama[$P].setLocationAndPOV = function(latlng, opt_pov) {
  var pov = this.getPOV();
  if ($T(opt_pov.yaw)) pov.yaw = opt_pov.yaw;
  if ($T(opt_pov.pitch)) pov.pitch = opt_pov.pitch;
  if ($T(opt_pov.zoom)) pov.zoom = opt_pov.zoom;
  this.setPOV(pov);
  this.v3.setPosition(latlng);
};

GStreetviewPanorama[$P].remove = function() {};

GStreetviewPanorama[$P].getPOV = function() {
  return SVP2GP(this.v3.getPov());
};

GStreetviewPanorama[$P].setPOV = function(pov) {
  this.v3.setPov(GP2SVP(pov));
};

GStreetviewPanorama[$P].getLatLng = function() {
  return this.v3.getPosition();
};

GStreetviewPanorama[$P].getPanoId = function() {
  return this.v3.getPano();
};

/*
 *  GStreetviewOverlay
 *
 */
$W.GStreetviewOverlay = function() {
  this.v3 = new $G.ImageMapType({
    getTileUrl: function(coord, zoom) {
      var x = coord.x % (1 << zoom);
      return "http://cbk0.google.com/cbk?output=overlay&" +
             "zoom=" + zoom + "&x=" + x + "&y=" + coord.y + "&cb_client=api";
    },
    tileSize: new $G.Size(256, 256),
    isPng: true,
    name: 'streetview_overlay'
  });
};
GStreetviewOverlay[$P] = new GOverlay();

GStreetviewOverlay[$P].setMap = function(map) {
  if (map) {
    map.v3.overlayMapTypes.insertAt(0, this.v3);
  } else {
    map.v3.overlayMapTypes.forEach(function(e, i){
      if (e.name == 'streetview_overlay') {
        map.v3.overlayMapTypes.removeAt(i);
      }
    });
  }
  this.map = map;
};

/*
 *  GStreetviewClient
 *
 */
$W.GStreetviewClient = function() {
  this.v3 = new $G.StreetViewService();
};

GStreetviewClient[$P].getNearestPanorama = function(latlng, callback) {
  this.v3.getPanoramaByLocation(latlng, 50, function(data, status) {
    if (status == $G.StreetViewStatus.OK) {
      for ($i in data.links) {
        $i.yaw = $i.heading;
        delete $i.heading;
        $i.panoId = $i.pano;
        delete $i.pano;
      }
      callback({
        location: {
          description: data.location.description,
          latlng: data.location.latLng,
          panoId: data.location.pano
        },
        copyright: data.copyright,
        links: data.links,
        code: 200
      });
    } else if (status == $G.StreetViewStatus.ZERO_RESULTS) {
      callback({ code:600 });
    } else {
      callback({ code:500 });
    }
  });
};

GStreetviewClient[$P].getPanoramaById = function(panoId, callback) {
  this.v3.getPanoramaById(panoId, function(data, status) {
    if (status == $G.StreetViewStatus.OK) {
      for ($i in data.links) {
        $i.yaw = $i.heading;
        delete $i.heading;
        $i.panoId = $i.pano;
        delete $i.pano;
      }
      callback({
        location: {
          description: data.location.description,
          latlng: data.location.latLng,
          panoId: data.location.pano
        },
        copyright: data.copyright,
        links: data.links,
        code: 200
      });
    } else if (status == $G.StreetViewStatus.ZERO_RESULTS) {
      callback({ code:600 });
    } else {
      callback({ code:500 });
    }
  });
};

/*
 *  GTrafficOverlay
 *
 */
$W.GTrafficOverlay = function(opts) {
  this.v3 = new $G.TrafficLayer();
};
GTrafficOverlay[$P] = new GOverlay();

/*
 *  GTravelModes
 *
 */
$W.G_TRAVEL_MODE_WALKING = $G.DirectionsTravelMode.WALKING;
$W.G_TRAVEL_MODE_DRIVING = $G.DirectionsTravelMode.DRIVING;

/*
 *  GXml
 *
 */
$W.GXml = {
  parse: function(xmltext) {
    var obj = null;
    if (window.ActiveXObject) {
      obj = new ActiveXObject('Microsoft.XMLDOM');
      obj.async = false;
      obj.loadXML(xmltext);
    } else {
      var parser = new DOMParser();
      obj = parser.parseFromString(xmltext, 'text/xml');
    }
    return obj;
  },

  value: function(xmlnode) {
    return (xmlnode.text || xmlnode.textContent || xmlnode.innerHTML);
  }
};

/*
 *  GXmlHttp
 *
 */
$W.GXmlHttp = {
  create: function() {
    if (window.ActiveXObject) {
      try {
        return new ActiveXObject('Msxml2.XMLHTTP');
      } catch (e) {
        try {
          return new ActiveXObject('Microsoft.XMLHTTP');
        } catch (e2) {
          return null;
        }
      }
    } else if (window.XMLHttpRequest) {
      return new XMLHttpRequest();
    } else {
      return null;
    }
  }
};

}})();

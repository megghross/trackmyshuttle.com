define({
	server: {
		baseUrl: "/api.php"
	},
	startPosition: {
		lat: 38.9072,
		lng: -77.0369,
		zoom: 13,
		mapTypeId: "roadmap"
	},
	newProject: {
		name: "Route Map",
		description: "",
		layers: [{
			name: "Route Layer",
			isVisible: true,
			isExpanded: true,
			shapes: [{
        name: "",
        type: "directions",
        destinations: ["", ""],
        editing: true,
        avoidHighways: false,
        avoidTolls: false
      }]
		}],
		selectedLayerId: 0
	},
	newLayer: {
		name: "New Untitled Route Layer",
		isVisible: true,
		isExpanded: true,
		shapes: []
	},
	tools: {
		moveTool: {
			icon: "icon-handdrag",
			title: "Move Tool",
			isSelectable: true
		},
		addMarkerTool: {
			icon: "icon-map-marker",
			title: "Add Marker",
			isSelectable: true
		},
		addLineTool: {
			icon: "icon-line",
			title: "Add Polyline",
			isSelectable: true
		},
		addDirectionTool: {
			icon: "icon-directions",
			title: "Add Route",
			isSelectable: false
		}
	},
	newMarker: {
		type: "marker",
		name: "Untitled Point",
		icon: "icon-map-marker",
		isVisible: true
	},
	newDirections: {
		type: "directions",
		destinations: ["",""],
		editing: true,
		isVisible: true,
		avoidHighways: false,
		avoidTolls: false
	},
	defaultSearchZoom: 17,
	cookiesEnabled: true
});
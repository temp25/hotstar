var app = angular.module("app", ["ui.router"]);
var ipAddr_userAgent = "";

// Enable pusher logging - don't include this in production 
Pusher.logToConsole = true; 
var pusher = new Pusher(
   'a44d3a9ebac525080cf1', 
	{ 
	   cluster: 'ap2', 
	   encrypted: true 
	}
);

var pusherEventCallback = function(event){
	var message = event.message;
	var data = message['data'];
	var videoId = message['videoId'];
	console.log("Event : "+event);
};

var request = new XMLHttpRequest();
request.open('GET', 'https://api.ipify.org/?format=json', true);
request.onload = function() {
  if (request.status >= 200 && request.status < 400) {
    var data = JSON.parse(request.responseText);
	var channel = pusher.subscribe('hotstar-video-download-v1'); 
	ipAddr_userAgent = data.ip+"_"+navigator.userAgent;
	channel.bind(ipAddr_userAgent, pusherEventCallback);
  } else {
    console.error("Error occurred in getting response from ipify.org");
  }
};
request.onerror = function() {
  console.log("Error occurred in connecting to ipify.org");
};
request.send();


app.config(function($stateProvider, $urlRouterProvider) {
  // For any unmatched url, send to /route1
  $urlRouterProvider.otherwise("/route1");
  $stateProvider
    .state('route1', {
        url: "/route1",
        templateUrl: "container1.html",
        controller: "Controller1"
    })
    .state('route2', {
        url: "/route2",
        templateUrl: "container2.html",
        controller: "Controller2",
		params: {
			'url': '',
			'source': '',
			'videoFormats': [],
			'videoId': '',
			'playlistId': ''
		}
    });
});

app.controller("Controller1", function($scope, $state, $http, $timeout) {

  $scope.fetchFormats = function() {
	
	var videoUrl = $scope.urlTextBox;
			
	$http({
		url: '/getAvailableVideoFormats.php',
		method: "POST",
		data: 'url='+videoUrl,
		headers: {'Content-Type': 'application/x-www-form-urlencoded'}
	})
	.then(function(response) {
		//success
		responsePostData = response.data;
		$state.go("route2", {
			url: videoUrl,
			source: response.data.source,
			videoFormats: response.data.availableFormats,
			videoId: response.data.videoId,
			playlistId: response.data.playlistId
		});
	},
	function(response) { // optional
		console.error("Error occured in getting available video formats");
    });
    
    
  };
});


app.controller("Controller2", function($scope, $stateParams, $http, $timeout) {
	$scope.videoFormats = $stateParams.videoFormats;
	
	$scope.onFormatChange = function() {
		var element = document.getElementById("defFormat");
		if (typeof element != "undefined" && element != null)
			element.remove();
	};
	
	$scope.generateVideo = function(){
		console.log("selectedFormat : "+$scope.selectedFormat);
		
		$http({
			url: '/generateVideo.php',
			method: "POST",
			data:  'src=' + $stateParams.source +
			'&videoUrl=' + $stateParams.url + 
			'&playlistId=' + $stateParams.playlistId +
			'&videoId=' + $stateParams.videoId +
			'&videoFormat=' + $scope.selectedFormat,
			headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		})
		.then(function(response) {
			console.log("generateVideo request completed successfully");
		},
		function(response) { // optional
			console.error("Error occured in generateVideo request completion");
		});
	}
	
});
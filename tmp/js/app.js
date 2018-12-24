var app = angular.module("app", ["ui.router"]);
var ipAddr_userAgent = "";

// Enable pusher logging - don't include this in production 
Pusher.logToConsole = true; 
var pusher = new Pusher('a44d3a9ebac525080cf1', {
  cluster: 'ap2',
  forceTLS: true
});

var pusherEventCallback = function(event){
	var message = event.message;
	var data = message['data'];
	var videoId = message['videoId'];
	var consoleElement = document.querySelector('#responseText');
	if (typeof consoleElement != "undefined" && consoleElement != null){
		consoleElement.innerHTML += data+"<br/>";
		consoleElement.scrollTop = consoleElement.scrollHeight;
		var isVideoGenerationComplete = data.indexOf('Video generation complete') > -1;
		if(isVideoGenerationComplete){
			console.log("Download complete");
		}
	}			
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
    })
	.state('route3', {
        url: "/route3",
        templateUrl: "container3.html",
        controller: "Controller3",
		params: {
			'videoId': ''
		}
    });
});

app.controller("Controller1", function($scope, $state, $http, $timeout) {

  $scope.fetchFormats = function() {
	
	var videoUrl = $scope.urlTextBox;
	showLoading();
			
	$http({
		url: 'http://hotstar-test1.herokuapp.com/tmp/getAvailableVideoFormats.php',
		method: "POST",
		data: 'url='+videoUrl,
		headers: {'Content-Type': 'application/x-www-form-urlencoded'}
	})
	.then(function(response) {
		//success
		stopLoading();
		if(response.data.status === "true"){
			showSuccessDialog("Located video in the playlist for the given url"); 
			$state.go("route2", {
				url: videoUrl,
				source: response.data.source,
				videoFormats: response.data.availableFormats,
				videoId: response.data.videoId,
				playlistId: response.data.playlistId
			});			
		}else{
			showErrorDialog(response.data.errorMessage);
		}
		
	},
	function(response) { // optional
		showErrorDialog(response.data);
    });
    
    
  };
});


app.controller("Controller2", function($scope, $state, $stateParams, $http, $timeout) {
	$scope.videoFormats = $stateParams.videoFormats;
	
	$scope.onFormatChange = function() {
		var element = document.getElementById("defFormat");
		if (typeof element != "undefined" && element != null)
			element.remove();
	};
	
	$scope.generateVideo = function(){
		$http({
			url: 'http://hotstar-test1.herokuapp.com/tmp/generateVideo.php',
			method: "POST",
			data:  'src=' + $stateParams.source +
			'&videoUrl=' + $stateParams.url + 
			'&playlistId=' + $stateParams.playlistId +
			'&videoId=' + $stateParams.videoId +
			'&videoFormat=' + $scope.selectedFormat +
			'&uniqueId=' + ipAddr_userAgent,
			headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		})
		.then(function(response) {
			console.log("generateVideo request completed successfully "+response.data);
			$state.go("route3", {
				videoId: $stateParams.videoId
			});
		},
		function(response) { // optional
			console.error("Error occured in generateVideo request completion");
		});
	};
	
});


app.controller("Controller3", function($scope, $stateParams, $http, $timeout) {
	
	$scope.consoleVisibility = false;
	$scope.showHideText = "Show Console";
	
	$scope.showHideConsole = function(){
		$scope.consoleVisibility = !$scope.consoleVisibility;
		$scope.showHideText = $scope.consoleVisibility ? "Hide Console" : "Show Console";
	};
	
});


function showSuccessDialog(successMessage){
	 swal({
		  type: 'success',
		  title: successMessage,
		  allowOutsideClick: () => false,
		  showConfirmButton: false,
		  timer: 2000, //dismiss after 2 seconds
	 });
}

function showErrorDialog(errorMessage){
	swal({
		type: 'error',
		allowOutsideClick: () => false,
		title: 'Error in fetching the video format',
		text: errorMessage,
		footer: 'Try again with valid video URL',
	});
}

function showLoading(){
	swal({
		title: 'Fetching available video formats',
		allowOutsideClick: () => false,
		onOpen: () => {
			   swal.showLoading();
		}
	});
}

function stopLoading(){
	swal.close();
}
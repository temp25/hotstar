var app = angular.module("app", ["ui.router"]);
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
			'videoFormats': []
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
		$state.go("route2", {videoFormats: response.data.availableFormats});
	},
	function(response) { // optional
		// failed
		console.log("status : "+response.status+" data : "+response.data);
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
	}
});
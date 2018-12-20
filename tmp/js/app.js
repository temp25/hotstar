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
			'vidFormat': []
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
		$scope.videoFormats = response.data.availableFormats;
		//SharedLoc.put('container1', $scope);
		console.log("status : "+response.status+" data : "+response.data);
		//$location.path("/route2");
		$state.go("route2", {vidFormat: response.data.availableFormats});
	},
	function(response) { // optional
		// failed
		responsePostData = response.data;
		console.log("status : "+response.status+" data : "+response.data);
		//$location.path("/route2");
    });
    
    
  };
});


app.controller("Controller2", function($scope, $stateParams, $http, $timeout) {
	//SharedLoc.get('container1');
	var vidFormat = stateParams.vidFormat;
	console.log("vidFormat = "+vidFormat);
	$scope.onFormatChange = function() {
		if ($scope.formats != null) {
		  var element = document.getElementById("defFormat");
		  if (typeof element != "undefined" && element != null)
			document.getElementById("defFormat").remove();
		}
	};
  
});
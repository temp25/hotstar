var app = angular.module("app", ["ui.router"]);
var videoFormats = [];
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
		//$scope.videoFormats = response.data.availableFormats;
		//SharedLoc.put('container1', $scope);
		console.log("status : "+response.status+" data : "+response.data);
		//$location.path("/route2");
		$state.go("route2", {videoFormats: response.data.availableFormats});
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
	var vidFormat = $stateParams.vidFormat;
	console.log("typeof vidFormat : "+(typeof vidFormat)+"\nvidFormat:\n"+vidFormat);
	vidFormat.forEach((item) => {
		console.log("item.id: "+item.id+"__item.format_code: "+item.format_code+"__item.format_resolution: "+item.format_resolution);
	});
	/* videoFormats = JSON.parse(JSON.stringify(vidFormat), function(k, v){	
		if(k === "id"){
				if(isNaN(v))
					return v;
				else
					return parseInt(v, 10);
		}
		return v;
	});
	console.log(videoFormats); */
	
	var jsonStringify = JSON.stringify(vidFormat);
	console.log("jsonStringify : "+jsonStringify+"\n\n");
	
	$scope.videoFormats = videoFormats;
	$scope.onFormatChange = function() {
		if ($scope.formats != null) {
		  var element = document.getElementById("defFormat");
		  if (typeof element != "undefined" && element != null)
			document.getElementById("defFormat").remove();
		}
	};
  
});
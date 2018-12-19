var app = angular.module("app", ["ui.router"]);
var responsePostData;
app.config(function($stateProvider, $urlRouterProvider) {
  // For any unmatched url, send to /route1
  $urlRouterProvider.otherwise("/route2");
  $stateProvider
    .state('route1', {
        url: "/route1",
        templateUrl: "container1.html",
        controller: "ContainerController"
    })
    .state('route2', {
        url: "/route2",
        templateUrl: "container2.html",
        controller: "ContainerController"
    });
});

app.controller("ContainerController", function($scope, $location, $timeout) {
  
  $scope.onFormatChange = function() {
    if ($scope.formats != null) {
      var element = document.getElementById("defFormat");
      if (typeof element != "undefined" && element != null)
        document.getElementById("defFormat").remove();
    }
  };

  $scope.fetchFormats = function() {
	
	var videoUrl = $scope.urlTextBox;
	
	$http
		.post('/getAvailableVideoFormats.php', JSON.stringify({url: videoUrl}))
		.then(function(data, status, headers, config){
			//success
			responsePostData = data;
			console.log("status : "+status+" data : "+data);
			$location.path("/route2");
		}, function(data, status, headers, config){
			//failure
			console.log("status : "+status+" data : "+data);
		});
    
    
  };
});
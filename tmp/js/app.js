var app = angular.module("app", ["ngRoute"]);
var videoUrl = "";
app.controller("HelloController", function($scope) {
  $scope.message = "Hello, AngularJS";
});

app.controller("MyController", function($scope) {
  $scope.onFormatChange = function() {
    if ($scope.formats != null) {
      var element = document.getElementById("defFormat");
      if (typeof element != "undefined" && element != null)
        document.getElementById("defFormat").remove();
    }
  };
});

app.controller("ContainerController", function($scope, $location, $http) {
  var _location = $location;

  $scope.data = {
    "hls-121": "320x180",
    "hls-241": "320x180",
    "hls-461": "416x234",
    "hls-861": "640x360",
    "hls-1362": "720x404",
    "hls-2063": "1280x720",
    "hls-3192": "1600x900",
    "hls-4694": "1920x1080"
  };

  $scope.fetchFormats = function() {
    videoUrl = $scope.urlTextBox;
    console.log('value = '+videoUrl);
    
    jQuery.post("/getAvailableVideoFormats.php",{url: videoUrl}, function(data, status, xhr){
      console.log("data : "+data+", status : "+status+", xhr : "+xhr);
      var stringifiedData = JSON.stringify(data);
      console.log("stringifiedData : "+stringifiedData);
    }, "json");
    
    
    
    
    
  };
});

app.config(function($routeProvider) {
  $routeProvider
    .when("/container1", {
      templateUrl: "container1.html",
      controller: "ContainerController"
    })
    .when("/container2", {
      templateUrl: "container2.html",
      controller: "ContainerController"
    })
    .when("/container3", {
      templateUrl: "container3.html",
      controller: "ContainerController"
    })
    .otherwise({
      redirectTo: "/container1"
    });
});

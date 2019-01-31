
    // Enable pusher logging - don't include this in production
    Pusher.logToConsole = true;

    var pusher = new Pusher('a44d3a9ebac525080cf1', {
      cluster: 'ap2',
      forceTLS: true
    });
	var ipAddr_userAgent="";
	
	
	$.getJSON("https://api.ipify.org/?format=json", function(e) { 
		ipAddr_userAgent = e.ip + "_" + navigator.userAgent;
		var channel = pusher.subscribe('gdrive');
		channel.bind(ipAddr_userAgent, function(data) {
			//alert(data.message);
			//console.log("Auth Url : "+data.message);
			//var txtBox = document.getElementById("txtBox");
			//txtBox.value=data.message;
			console.log("Message : \n"+data.message);
		});
	});
	
	function getAuthUrl(){
		$.ajax({ 
		   url: "uploadFileGDrive.php", 
		   type: "POST",
		   data: {
				action: "getAuthUrl"
			},
		})
		.done(function(data) {
			console.log(data);
			//alert(data);
			var txtBox = document.getElementById("txtBox");
			txtBox.value = data;
		})
		.fail(function(response) {
			console.error("Error occured in POST request completion");
		});
	}

  var REDIRECT="https://hotstar-test1.herokuapp.com";

  function authorize() {
    var txtBox = document.getElementById("txtBox");
    var authUrl=txtBox.value;
    alert("authUrl : "+authUrl);
    var count=0;
    /* var win = window.open(authUrl, "windowname1", 'width=800, height=600');
    var pollTimer = window.setInterval(function() { 
        try { 
            var url = win.document.URL; 
			console.log(url);
            var navigationCounter = document.getElementById("navigationCounter");
            var navSpan = document.createElement('span');
            navSpan.innerHtml = "pollTimer counter invoked "+(++count)+" time(s)</br>";
            navigationCounter.appendChild(navSpan);
            } catch(e) { } 
     }, 3000);
	 alert("pollTimer : "+pollTimer); */
	 var pollTimer = window.setInterval(function() { 
        //try {
			var navigationCounter = document.getElementById("navigationCounter");
            var navSpan = document.createElement('span');
            navSpan.innerHtml = "pollTimer counter invoked "+(++count)+" time(s)</br>";
            navigationCounter.appendChild(navSpan);
			console.log(navigationCounter);
          //  } catch(e) { } 
     }, 1000);
  }

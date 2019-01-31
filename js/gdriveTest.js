
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
    //alert("authUrl : "+authUrl);
	console.log("authUrl : "+authUrl);
	//var popup = window.open(authUrl, "windowname1", 'width=800, height=600');
	var popupIntervalLog = "";
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
	 
	 // popup.onLoad = function() {
		// alert('loaded');
		// // do other things
		// var pollTimer = window.setInterval(function() { 
		 
			// sessionStorage.setItem("popupUrl", window.location.href);
			
		 // }, 1000);
	// };
	 
	 // event
	 
	 // var pollTimer = window.setInterval(function() { 
		// var popupUrl = sessionStorage.getItem("popupUrl");
		// console.log("popupUrl : "+popupUrl);
     // }, 2000);
	 
	 
	 
	 
	 
	 function winopen()
{
  var ghtml = "<h1>sasaasas</h1>";
  var gwin = window.open("", "_blank", "location=no,width=1000,height=600");
  if (gwin)
  { gwin.document.open();
    //gwin.document.write(ghtml);
	gwin.location.href = authUrl;

    var oscript = gwin.document.createElement("script");
    oscript.type = "text/javascript";
    oscript.innerHTML = "alert('loaded'); var pollTimer = window.setInterval(function() { console.log('popupUrl '+ window.location.href); }, 1000);";
    gwin.document.getElementsByTagName("head")[0].appendChild(oscript);

    //gwin.document.close();
  }
}
winopen();


  }
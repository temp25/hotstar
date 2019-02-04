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
			console.log("Message : \n"+data.message);
		});
	});
	
	function showAuthUrl(url){
	   var navigationCounter = document.getElementById("navigationCounter");
    var navSpan = document.createElement('span');
    navSpan.innerHtml = "url : "+url+"</br></br>";
    navigationCounter.appendChild
	}
	
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
  
  function uploadFile(authCode) {
    //var txtBox = document.getElementById("txtBox");
    //var authCode=txtBox.value;
    console.log("uploadFile() method called");
    $.ajax({ 
		   url: "uploadFileGDrive.php", 
		   type: "POST",
		   data: {
				action: "uploadFile",
				authCode: authCode,
				uniqueId: ipAddr_userAgent
			},
		})
		.done(function() {
			console.log("Auth code setup success");
   alert("Auth code setup success");
		})
		.fail(function() {
			console.error("Error occured in setting up Auth code");
   alert("Error occured in setting up Auth code");
		});
  }

  function authorize() {
    var txtBox = document.getElementById("txtBox");
    var authUrl=txtBox.value;
    //alert("authUrl : "+authUrl);
    var count=0;
    var popup;

    var pollTimer = window.setInterval(function() {
    	var tmpMsg = "pollTimer counter invoked "+(++count)+" time(s)</br>";
    	//console.log(tmpMsg);
    	if(Cookies.enabled){
       var authCode = Cookies.get('authCode');
	   var authCodeUrl = Cookies.get('authCodeUrl');
       if(authCode!==undefined && authCodeUrl!==undefined){
          //alert("authCode :"+authCode);
          console.log("authCode :"+authCode);
		  console.log("authCodeUrl :"+authCodeUrl);
		  txtBox.value = authCode;
          popup.close();
          clearInterval(pollTimer);
          //uploadFile(authCode);
       }
     }
        //var navigationCounter = document.getElementById("navigationCounter");
        //var navSpan = document.createElement('span');
        //navSpan.innerHtml = tmpMsg;
        //navigationCounter.appendChild(navSpan);
     }, 1000);

	console.log("authUrl : "+authUrl);
	popup = window.open(authUrl, "windowname1", 'width=800, height=600');
  }
<script type="text/javascript" src="https://login.greenjade88.com/jswrapper/integration.js.php?casino=greenjade88"></script>
<script type="text/javascript">
var mobiledomain = "gm175788.com";
var systemidvar = "976";

function getUrlVars() 
{
	var vars = {};
	var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
	vars[key] = value;
	});
	return vars;
}

iapiSetCallout('GetTemporaryAuthenticationToken', calloutGetTemporaryAuthenticationToken);

function askTempandLaunchGame(game)
{
	currentgame = game;
	var realMode = 1;
	iapiRequestTemporaryToken(realMode, systemidvar, 'GamePlay');
}

function launchMobileClient(temptoken) 
{
	var clientUrl = 'https://hub.gm175788.com/igaming/?gameId=gos&real=1&username=TTCTEST010102&lang=ZH-CN&tempToken=' + temptoken + '&lobby=' + location.href.substring(0,location.href.lastIndexOf('/')+1) + 'lobby.html' + '&support=' + location.href.substring(0,location.href.lastIndexOf('/')+1) + 'support.html' + '&logout=' + location.href.substring(0,location.href.lastIndexOf('/')+1) + 'logout.html' + '&deposit=' + location.href.substring(0,location.href.lastIndexOf('/')+1) + 'deposit.html';
	document.location = clientUrl;
}

function calloutGetTemporaryAuthenticationToken(response) 
{
	if (response.errorCode) 
	{
		alert("Token failed. " + response.playerMessage + " Error code: " + response.errorCode);
	}
	else 
	{
		launchMobileClient(response.sessionToken.sessionToken);
	}
}
</script>
</head>
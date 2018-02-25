<!doctype html>
<html lang="ch">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>登录中...</title>
    <?php $playerLoginPageEntity->css && require_once $playerLoginPageEntity->css?>
</head>
<body>
<script type="text/javascript" src="https://login.greenjade88.com/jswrapper/integration.js.php?casino=greenjade88"></script>
<script type="text/javascript">
	var currentgame = '';
    iapiSetCallout('Login', calloutLogin);
    <?php $ptPlayerAccount = new \App\Vendor\GameGateway\PT\PTGameAccount($playerLoginPageEntity->gameAccount) ; ?>

    iapiLogin('<?= $ptPlayerAccount->loginUserName() ?>', '<?=  $ptPlayerAccount->loginPassword() ?>', 1, "ch");
    //Login('<?= $ptPlayerAccount->loginUserName() ?>', '<?=  $ptPlayerAccount->loginPassword() ?>', 1, "ch");
	function calloutLogin(response) {console.log(response);askTempandLaunchGame();}

	function getUrlVars() 
	{
		var vars = {};
		var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {vars[key] = value;});
		return vars;
	}

	iapiSetCallout('GetTemporaryAuthenticationToken', calloutGetTemporaryAuthenticationToken);

	//askTempandLaunchGame();

	function askTempandLaunchGame() 
	{
		currentgame = '<?=$playerLoginPageEntity->gameCode?>';
		var realMode = 1;
		iapiRequestTemporaryToken(realMode, 976, 'GamePlay');
	}

    function calloutGetTemporaryAuthenticationToken(response) 
	{
		document.location = 'https://hub.gm175788.com/igaming/?gameId=<?=$playerLoginPageEntity->gameCode?>&real=1&username=<?= $ptPlayerAccount->loginUserName() ?>&lang=ZH-CN&tempToken=' + response.sessionToken.sessionToken;
	}
</script>
</body>
</html>
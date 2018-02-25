<script type="text/javascript" src="https://login.greenjade88.com/jswrapper/integration.js.php?casino=greenjade88"></script>
<script type="text/javascript">
    iapiSetCallout('Login', calloutLogin);
    <?php $ptPlayerAccount = new \App\Vendor\GameGateway\PT\PTGameAccount($playerLoginPageEntity->gameAccount) ; ?>
    iapiLogin('<?= $ptPlayerAccount->loginUserName() ?>', '<?=  $ptPlayerAccount->loginPassword() ?>', 1, "ch");
    var requestId = iapiRequestIds[0][0];
    function calloutLogin(response) {
        console.log(response);
        if(response.errorCode == 0){
           // alert('errorcode==0');
            window.location.href = 'http://cache.download.banner.greenjade88.com/casinoclient.html?language=ZH-CN&game=<?=$playerLoginPageEntity->gameCode?>'
        }
    }
</script>
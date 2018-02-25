<?php
/**
 * Created by PhpStorm.
 * User: win7
 * Date: 2017/11/25
 * Time: 16:01
 */

$url = 'https://api.adminserv88.com/v1/transaction';
$access_token = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VyX25hbWUiOiJUVENDTllBUEkiLCJjdHgiOjgsInBpZCI6MjAyMSwiYW4iOiJUVENDTllBRE1JTiIsImNsaWVudF9pZCI6IkdhbWluZ01hc3RlcjFDTllfYXV0aCIsImFwIjoiMSwyMzI2MCwyMzI2MiwyNDI1OSw0NDc3MjUsMjg0NDEzOSwyODQ0MjEwIiwidWlkIjoyODEyMDQ1LCJhdCI6NCwic2NvcGUiOlsiYXBwX2luc3RhbGxlZDpyIiwidXNlcjp3Iiwid2FsbGV0OnIiLCJhdWRpdDpyIiwidG9rZW46dyIsInJlcG9ydDpyIiwibGF1bmNoZXJfaXRlbTpyIiwidXNlcjpyIiwidHg6ciIsImFjY2FwcDpyIiwiY2F0ZWdvcnk6ciIsImFjY291bnQ6dyIsIml0ZW06ciIsInR4OnciLCJhY2NvdW50OnIiXSwiZXhwIjoxNTExNzYwMzE0LCJhaWQiOjI4NDQyMTAsInVyIjozLCJqdGkiOiJmMTBjZTkwNy1iZDZjLTQ3YjktYmMzNS1lYWQ4MTNjY2E4ZGEifQ.THDAzoxLH54iFd22KO-JCZluvFy3crNYrn0B6Mp2LdY" ;
$account_ext_ref = "tlt1231" ; //额外参考账号名,由于我在创建玩家时就设置额外参考账号名与账号名一致，所以这里就是账号名
$external_ref = "201711271137" ;
$amount = "100" ;
$type = "DEBIT" ;
$balance_type = "CREDIT_BALANCE，" ;
$category = "TRANSFER，" ;
//设置 Header
$header_data = array();
$header_data[] = 'Authorization: Bearer ' . $access_token;
$header_data[] = 'X-DAS-TZ: UTC+8';
$header_data[] = 'X-DAS-CURRENCY: CNY';
$header_data[] = 'X-DAS-LANG: zh-CN';
$header_data[] = 'X-DAS-TX-ID: TEXT-TX-ID';
$header_data[] = 'Content-Type: application/json;charset=UTF-8';
//设置 body
$body = array
(
    'account_ext_ref' => $account_ext_ref ,
    'external_ref' => $external_ref ,
    'amount' => $amount,
    'type' => $type ,
    'balance_type' => $balance_type ,
    'category' => $category
);
$transactionBody = array($body);
$jsonBody = json_encode($transactionBody );
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HTTPHEADER, $header_data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonBody);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
$output = curl_exec($ch);
//print_r($output);


/*
 * login return data:
 * "{
 * "access_token":"eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VyX25hbWUiOiJUVENDTllBUEkiLCJjdHgiOjksInBpZCI6MjAyMSwiYW4iOiJUVENDTllBRE1JTiIsImNsaWVudF9pZCI6IkdhbWluZ01hc3RlcjFDTllfYXV0aCIsImFwIjoiMSwyMzI2MCwyMzI2MiwyNDI1OSw0NDc3MjUsMjg0NDEzOSwyODQ0MjEwIiwidWlkIjoyODEyMDQ1LCJhdCI6NCwic2NvcGUiOlsiYXBwX2luc3RhbGxlZDpyIiwidXNlcjp3Iiwid2FsbGV0OnIiLCJhdWRpdDpyIiwidG9rZW46dyIsInJlcG9ydDpyIiwibGF1bmNoZXJfaXRlbTpyIiwidXNlcjpyIiwidHg6ciIsImFjY2FwcDpyIiwiY2F0ZWdvcnk6ciIsImFjY291bnQ6dyIsIml0ZW06ciIsInR4OnciLCJhY2NvdW50OnIiXSwiZXhwIjoxNTExNzY0NTE3LCJhaWQiOjI4NDQyMTAsInVyIjozLCJqdGkiOiI5MzQ4MDUxNS1iOTU2LTQwYTEtYWU5YS0xNDJhMTg0ODA0ZTYifQ.acnbuJVTeI-C6O1zLiHZ3di0_dyIiEhlGHfsofmdObk",
 * "token_type":"bearer",
 * "refresh_token":"eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VyX25hbWUiOiJUVENDTllBUEkiLCJjdHgiOjksInBpZCI6MjAyMSwiYW4iOiJUVENDTllBRE1JTiIsImNsaWVudF9pZCI6IkdhbWluZ01hc3RlcjFDTllfYXV0aCIsImFwIjoiMSwyMzI2MCwyMzI2MiwyNDI1OSw0NDc3MjUsMjg0NDEzOSwyODQ0MjEwIiwidWlkIjoyODEyMDQ1LCJhdCI6NCwic2NvcGUiOlsiYXBwX2luc3RhbGxlZDpyIiwidXNlcjp3Iiwid2FsbGV0OnIiLCJhdWRpdDpyIiwidG9rZW46dyIsInJlcG9ydDpyIiwibGF1bmNoZXJfaXRlbTpyIiwidXNlcjpyIiwidHg6ciIsImFjY2FwcDpyIiwiY2F0ZWdvcnk6ciIsImFjY291bnQ6dyIsIml0ZW06ciIsInR4OnciLCJhY2NvdW50OnIiXSwiYXRpIjoiOTM0ODA1MTUtYjk1Ni00MGExLWFlOWEtMTQyYTE4NDgwNGU2IiwiZXhwIjoxNTExNzcxNzE3LCJhaWQiOjI4NDQyMTAsInVyIjozLCJqdGkiOiJiMGJlOTU2OC0zMjlkLTQ3ZmEtOTMwYy1iM2Y0NTVhYmY0NjUifQ.5et5whTt4dv9lzZyMVKUrvxqJINZ2i7MsL7BCCXnNPY",
 * "expires_in":3599,
 * "scope":"app_installed:r user:w wallet:r audit:r token:w report:r launcher_item:r user:r tx:r accapp:r category:r account:w item:r tx:w account:r",
 * "jti":"93480515-b956-40a1-ae9a-142a184804e6"
 * }"
 * */

/*
 * updatePassword
 * "{
 * "meta":{
 *          "currency":"CNY",
 *          "time_zone":"UTC+08:00",
 *          "transaction_id":"TEXT-TX-ID",
 *          "processing_time":129
 *      },
 *  "data":{
 *          "id":3464612,
 *          "my_path":"2844210,3464612",
 *          "version":4,
 *          "user_id":3432358,
 *          "username":"tlt1231:124",
 *          "user_status":"ENABLED",
 *          "parent_id":2844210,
 *          "name":"tlt1231",
 *          "ext_ref":"tlt1231",
 *          "type":"MEMBER",
 *          "currency_unit":"CNY",
 *          "status":"ENABLED",
 *          "ip_whitelist":false,
 *          "created_by":2812045,
 *          "created":"2017-11-27 11:19:11.060",
 *          "updated_by":2812045,
 *          "updated":"2017-11-27 13:39:56.815"
 *         }
 * }"
 * */
function sunbet() {
try
{
    $clientId = "ttc1122";
    $clientSecret = "4LzVCYF8psVIbaog1LP2vM5SYZxAMmoto0hpkJSR2sy";

    //oauth
    $authurl = "https://tgpaccess.com/api/oauth/token";
    $authparam = "client_id=" . $clientId . "&client_secret=" . $clientSecret . "&grant_type=client_credentials&scope=playerapi";


    $post_data = mb_strlen($authparam, 'ASCII');

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $authurl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $authparam);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

    $output = curl_exec($ch);
    curl_close($ch);

    //print_r($output);
}
catch(Exception $e)
{
    echo 'Caught exception: ',  $e->getMessage(), "<br/>";
}

	$access_token = json_decode($output) -> access_token;
	$url = "https://tgpaccess.com/api/history/bets?startdate=2017-12-11T14:30:00&enddate=2017-12-11T15:00:00";

	//有身分認證的get
	$authget = curl_init();

	curl_setopt($authget, CURLOPT_URL, $url);
	curl_setopt($authget, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($authget, CURLOPT_HTTPHEADER, array('Authorization: bearer' . ' ' . $access_token));
	curl_setopt($authget, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($authget, CURLOPT_SSL_VERIFYPEER, 0);

	$authgetoutput = curl_exec($authget);
	curl_close($authget);

	print_r($authgetoutput);


}
sunbet() ;
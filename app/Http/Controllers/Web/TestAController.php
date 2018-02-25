<?php

namespace App\Http\Controllers\Web;

use App\Vendor\GameInterface\GameTools;
use App\Http\Controllers\AppBaseController;
use App\Models\Conf\CarrierInvitePlayerConf;
use App\Models\Log\PlayerDepositPayLog;
use Carbon\Carbon;
use App\Models\Carrier;
use App\Jobs\CarrierInviteRewardHandle;
use App\Models\CarrierTemplate;
use App\Vendor\GameGateway\TTG\TTG;

class TestAController extends AppBaseController
{


    public function test()
    {
    	//$ttg =new TTG();
        //$str =$ttg->betRecord();
        $str='[{"player":{"@attributes":{"playerId":"TTC_1Zr2KiB6","partnerId":"TTC"}},"detail":{"@attributes":{"transactionId":"433608103","transactionDate":"20180123 18:07:14","currency":"CNY","game":"DynastyEmpire","transactionSubType":"Wager","handId":"119443092","amount":"-4.00"}}},{"player":{"@attributes":{"playerId":"TTC_1Zr2KiB6","partnerId":"TTC"}},"detail":{"@attributes":{"transactionId":"433608105","transactionDate":"20180123 18:07:14","currency":"CNY","game":"DynastyEmpire","transactionSubType":"Resolve","handId":"119443092","amount":"0.00"}}},{"player":{"@attributes":{"playerId":"TTC_1Zr2KiB6","partnerId":"TTC"}},"detail":{"@attributes":{"transactionId":"433608106","transactionDate":"20180123 18:07:20","currency":"CNY","game":"DynastyEmpire","transactionSubType":"Wager","handId":"119443093","amount":"-4.00"}}},{"player":{"@attributes":{"playerId":"TTC_1Zr2KiB6","partnerId":"TTC"}},"detail":{"@attributes":{"transactionId":"433608108","transactionDate":"20180123 18:07:20","currency":"CNY","game":"DynastyEmpire","transactionSubType":"Resolve","handId":"119443093","amount":"0.00"}}},{"player":{"@attributes":{"playerId":"TTC_1Zr2KiB6","partnerId":"TTC"}},"detail":{"@attributes":{"transactionId":"433608109","transactionDate":"20180123 18:07:26","currency":"CNY","game":"DynastyEmpire","transactionSubType":"Wager","handId":"119443094","amount":"-4.00"}}},{"player":{"@attributes":{"playerId":"TTC_1Zr2KiB6","partnerId":"TTC"}},"detail":{"@attributes":{"transactionId":"433608111","transactionDate":"20180123 18:07:26","currency":"CNY","game":"DynastyEmpire","transactionSubType":"Resolve","handId":"119443094","amount":"0.00"}}}]';

        $xml=json_decode($str,true);
        foreach($xml as $key => $element)
        {
            dump($element['detail']['@attributes']['transactiondate']);
        }
        
    }

}
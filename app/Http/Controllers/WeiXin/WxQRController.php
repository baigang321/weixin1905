<?php

namespace App\Http\Controllers\WeiXin;

use App\Http\Controllers\Controller;
use App\Model\WxUserModel;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
class WxQRController extends Controller
{
    public function qrcode(){
        $scene_id=$_GET['scene'];
        $access_token='28_XTaVKGq3366rtN70m0MP38wky5QWw-uX_aBJt1s9HsFhRVL1snP64Z8n0wuQWvUWyVYTGl_E0sZF1XGjPt_kNAWDE0xZAQZrcH5faFxj2uk-UqXF1HQc32NJDFKNolWJqRNfLQaqi190GBLlRERcAJADQD';
        $url='https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token='.$access_token;
//      {"expire_seconds": 604800, "action_name": "QR_SCENE", "action_info": {"scene": {"scene_id": 123}}}
        $data1=[
                'expire_seconds'=>604800,
                'action_name'=>'QR_SCENE',
                'action_info'=>[
                    'scene'=>[
                        'scene_id'=>$scene_id,
                    ]
                ]
        ];
        $client=new Client();
        $request=$client->request('POST',$url,['body'=>json_encode($data1)
        ]);
        $json1=$request->getBody();
        $tiket=json_decode($json1,true)['ticket'];
        $url='https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket='.$tiket;
        return redirect($url);
    }
}

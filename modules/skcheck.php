<?php

/*

///==[Stripe SK Key Checker Commands]==///

/key sk_live - Checks the SK Key

*/


include __DIR__."/../config/config.php";
include __DIR__."/../config/variables.php";
include_once __DIR__."/../functions/bot.php";
include_once __DIR__."/../functions/db.php";
include_once __DIR__."/../functions/functions.php";


////////////====[MUTE]====////////////
if(strpos($message, "/key ") === 0 || strpos($message, "!key ") === 0){   
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
          'reply_to_message_id'=> $message_id

        ]);

        $messageidtoedit = capture(json_encode($messageidtoedit1), '"message_id":', ',');
        $sk = substr($message, 5);
        
        if(preg_match_all("/sk_(test|live)_[A-Za-z0-9]+/", $sk, $matches)) {
            $sk = $matches[0][0];
            $skhidden = substr_replace($sk, '',12).preg_replace("/(?!^).(?!$)/", "*", substr($sk, 12));
        

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
            $info = curl_getinfo($ch);
            $time = $info['total_time'];
            $time = substr_replace($time, '',4);

            ###END OF CHECKER PART###
            
            
            if(strpos($result, 'error') == False || stripos($result, '"type": "card_error"')){
              bot('editMessageText',[
                'chat_id'=>$chat_id,
                'message_id'=>$messageidtoedit,
                'text'=>"<b>SK Key:</b> <code>$skhidden</code>
<b> الحالة - »مصادقة المرور ✅
الاستجابة - »المفتاح السري المقدم هو على قيد الحياة
زمن -» <b>$time</b><b>s</b></b>

<b>Checked By <a href='tg://user?id=$userId'>$firstname</a></b>
<b>Bot By: <a href='t.me/MACS31'>MR_MACS</a></b>",
                'parse_mode'=>'html',
                'disable_web_page_preview'=>'true'
                
            ]);}
            else{
              bot('editMessageText',[
                'chat_id'=>$chat_id,
                'message_id'=>$messageidtoedit,
                'text'=>"<b>SK Key:</b> <code>$skhidden</code>
<b> الحالة - »فشل المصادقة ❌
الاستجابة - »المفتاح السري المقدم ميت
زمن<b> الحالة - »فشل المصادقة ❌
الاستجابة - »المفتاح السري المقدم ميت
زمن -» <b>$time</b><b>s</b></b>

<b>Checked By <a href='tg://user?id=$userId'>$firstname</a></b>
<b>Bot By: <a href='t.me/MACS31'>MR_MACS</a></b>",
                'parse_mode'=>'html',
                'disable_web_page_preview'=>'true'
                
            ]);}
          
        }else{
          bot('editMessageText',[
            'chat_id'=>$chat_id,
            'message_id'=>$messageidtoedit,
            'text'=>"<b>لا تستسلم أبدا!

تقديم SK KEYYYY صالح!</b>",
            'parse_mode'=>'html',
            'disable_web_page_preview'=>'true'
            
        ]);

        }
    }
}


?>
<?php


include __DIR__."/config/config.php";
include __DIR__."/config/variables.php";
include __DIR__."/functions/bot.php";
include __DIR__."/functions/functions.php";
include __DIR__."/functions/db.php";


date_default_timezone_set($config['timeZone']);


////Modules
include __DIR__."/modules/admin.php";
include __DIR__."/modules/skcheck.php";
include __DIR__."/modules/binlookup.php";
include __DIR__."/modules/iban.php";
include __DIR__."/modules/stats.php";
include __DIR__."/modules/me.php";
include __DIR__."/modules/apikey.php";


include __DIR__."/modules/checker/ss.php";
include __DIR__."/modules/checker/schk.php";
include __DIR__."/modules/checker/sm.php";



//////////////===[START]===//////////////

if(strpos($message, "/start") === 0){
if(!isBanned($userId) && !isMuted($userId)){

  if($userId == $config['adminID']){
    $messagesec = "<b>Type /admin لمعرفة أوامر المسؤول</b>";
  }

    addUser($userId);
    bot('sendmessage',[
        'chat_id'=>$chat_id,
        'text'=>"<b>مرحبا @$username,

Type /cmds to know all my commands!</b>

$messagesec",
	'parse_mode'=>'html',
	'reply_to_message_id'=> $message_id,
    'reply_markup'=>json_encode(['inline_keyboard' => [
        [
          ['text' => "💠 تعديل MR_MACS 💠", 'url' => "t.me/MACS31"]
        ],
        [
          ['text' => "💎 قناة السورس 💎", 'url' => "t.me/MACS37"]
        ],
      ], 'resize_keyboard' => true])
        
    ]);
  }
}

//////////////===[CMDS]===//////////////

if(strpos($message, "/cmds") === 0 || strpos($message, "!cmds") === 0){

  if(!isBanned($userId) && !isMuted($userId)){
    bot('sendmessage',[
    'chat_id'=>$chat_id,
    'text'=>"<b>الأوامر التي تريد التحقق منها?</b>",
    'parse_mode'=>'html',
    'reply_to_message_id'=> $message_id,
    'reply_markup'=>json_encode(['inline_keyboard'=>[
    [['text'=>"💳 فاحص الفيزات",'callback_data'=>"checkergates"]],[['text'=>"🛠 أوامر أخرى",'callback_data'=>"othercmds"]],
    ],'resize_keyboard'=>true])
    ]);
  }
  
  }
  
  if($data == "back"){
    bot('editMessageText',[
    'chat_id'=>$callbackchatid,
    'message_id'=>$callbackmessageid,
    'text'=>"<b>Which commands would you like to check?</b>",
    'parse_mode'=>'html',
    'reply_markup'=>json_encode(['inline_keyboard'=>[
    [['text'=>"💳 فاحص الفيزات",'callback_data'=>"checkergates"]],[['text'=>"🛠 اوامر اخرى",'callback_data'=>"othercmds"]],
    ],'resize_keyboard'=>true])
    ]);
  }
  
  if($data == "checkergates"){
    bot('editMessageText',[
    'chat_id'=>$callbackchatid,
    'message_id'=>$callbackmessageid,
    'text'=>"<b>━━ اوامر استخدام البوت ━━</b>
  
<b>/ss | !ss - Stripe [Auth]</b>
<b>/sm | !sm - Stripe [Merchant]</b>
<b>/schk | !schk - User Stripe Merchant [Needs SK]</b>

<b>/apikey sk_live_xxx - Add SK Key for /schk gate</b>
<b>/myapikey | !myapikey - View the added SK Key for /schk gate</b>

<b>ϟ انضم الينا <a href='t.me/MACS37'>MR_MACS</a></b>",
    'parse_mode'=>'html',
    'disable_web_page_preview'=>true,
    'reply_markup'=>json_encode(['inline_keyboard'=>[
  [['text'=>"Return",'callback_data'=>"back"]]
  ],'resize_keyboard'=>true])
  ]);
  }
  
  
  if($data == "othercmds"){
    bot('editMessageText',[
    'chat_id'=>$callbackchatid,
    'message_id'=>$callbackmessageid,
    'text'=>"<b>━━ اوامر اخرى ━━</b>
  
<b>/me | !me</b> - معلوماتك
<b>/stats | !stats</b> - احصائيات الفحص
<b>/key | !key</b> - فحص مفتاح SK
<b>/bin | !bin</b> - Bin ابحث عن
<b>/iban | !iban</b> - IBAN فاحص 
  
  <b>ϟ انضم الينا <a href='t.me/MACS37'>MR_MACS</a></b>",
    'parse_mode'=>'html',
    'disable_web_page_preview'=>true,
    'reply_markup'=>json_encode(['inline_keyboard'=>[
  [['text'=>"Return",'callback_data'=>"back"]]
  ],'resize_keyboard'=>true])
  ]);
  }

?>
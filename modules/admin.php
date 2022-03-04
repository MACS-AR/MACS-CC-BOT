<?php

/*

///==[Admin Commands]==///

/mute - Mute a User until the Specified time
    Ex:-  /mute userID 10m - Mutes for 10 min
          /mute userID 10d - Mutes for 10 days

/unmute - Unmute a Muted user
    Ex:-  /unmute userID - Unmutes the User

/ban - Permanently Ban a user
    Ex:-  /ban userID - Bans the User   
    
/unban - Unban a Banned user
    Ex:-  /unban userID - Unbans the User

/mutelist - Sends the List of Currently Muted User

/banlist - Sends the List of Currently Banned User  

/botstats - Returns the Bot's stats

/userstats - Check the Checker Stats of a User
    Ex:-   /userstats userID - Returns the stats
*/


include __DIR__."/../config/config.php";
include __DIR__."/../config/variables.php";
include_once __DIR__."/../functions/bot.php";
include_once __DIR__."/../functions/db.php";
include_once __DIR__."/../functions/functions.php";


////////////====[MUTE]====////////////
if(strpos($message, "/mute ") === 0 and $userId == $config['adminID']){
    $args = explode("|", substr($message, 6));
    $userID = $args[0];
    $timer = $args[1];


    if(stripos($timer,'m')){
      $timerunix = add_minutes(time(),$timer);
    }
    elseif(stripos($timer,'d')){
      $timerunix = add_days(time(),$timer);
    }
    else{
      bot('sendmessage',[
        'chat_id'=>$chat_id,
        'text'=>"Invalid Time!",
	      'parse_mode'=>'html',
	      'reply_to_message_id'=> $message_id,
      ]);
      return;
    }
    
    

    bot('sendmessage',[
        'chat_id'=>$chat_id,
        'text'=>muteUser($userID,$timerunix),
	'parse_mode'=>'html',
	'reply_to_message_id'=> $message_id,
    ]);

}

////////////====[UNMUTE]====////////////
if(strpos($message, "/unmute ") === 0 and $userId == $config['adminID']){
    $userID = substr($message, 8);   

    bot('sendmessage',[
        'chat_id'=>$chat_id,
        'text'=>unmuteUser($userID),
	'parse_mode'=>'html',
	'reply_to_message_id'=> $message_id,
    ]);

}

////////////====[BAN]====////////////
if(strpos($message, "/ban ") === 0 and $userId == $config['adminID']){
  $userID = substr($message, 5);   

  bot('sendmessage',[
      'chat_id'=>$chat_id,
      'text'=>banUser($userID),
'parse_mode'=>'html',
'reply_to_message_id'=> $message_id,
  ]);

}

////////////====[UNBAN]====////////////
if(strpos($message, "/unban ") === 0 and $userId == $config['adminID']){
  $userID = substr($message, 7);   

  bot('sendmessage',[
      'chat_id'=>$chat_id,
      'text'=>unbanUser($userID),
'parse_mode'=>'html',
'reply_to_message_id'=> $message_id,
  ]);

}

////////////====[HELPER]====////////////
if(strpos($message, "/admin") === 0 and $userId == $config['adminID']){
  bot('sendmessage',[
      'chat_id'=>$chat_id,
      'text'=>"<b>مرحبا المشرف! ها هي أوامرك:

/mute - كتم المستخدم حتى الوقت المحدد
    مثال: - <code> / كتم صوت معرف المستخدم 10 م - كتم الصوت لمدة 10 دقائق
       / mute userID 10d - كتم الصوت لمدة 10 أيام</code>

/ unmute - إعادة صوت المستخدم الذي تم كتمه
    مثال: - <code> / unmute userID - إلغاء كتم صوت المستخدم </ code>

/ban - حظر مستخدم بشكل دائم
    مثال: - <code> /ban معرف المستخدم - حظر المستخدم </ code>    
    
/unban - إلغاء حظر مستخدم محظور
    مثال: - <code> / معرف المستخدم Unban - إلغاء حظر المستخدم </ code>   
    
/mutelist - يرسل قائمة بالمستخدم الذي تم كتمه حاليًا

/banlist - يرسل قائمة المستخدمين المحظورين حاليًا

/botstats - إرجاع إحصائيات الروبوت

/userstats - تحقق من إحصائيات الفحص للمستخدم
    مثال: - <code> / userstats userID - إرجاع الإحصائيات </ code>     </b>",
'parse_mode'=>'html',
'reply_to_message_id'=> $message_id,
  ]);

}

////////////====[BANLIST]====////////////
if(strpos($message, "/banlist") === 0 and $userId == $config['adminID']){
   $banlist = fetchBanlist();

    if(!$banlist){
    bot('sendmessage',[
      'chat_id'=>$chat_id,
      'text'=>"<b>لم تقم بحظر أي شخص حتى الآن.</b>",
      'parse_mode'=>'html',
      'reply_to_message_id'=> $message_id]);
   }

    foreach ($banlist as $result){
      $banuser = file_get_contents('Banned.txt');
		  $banuser .= $result."\n";
		  file_put_contents('Banned.txt',$banuser);
    }    

    $gettheuserfile = file_get_contents('Banned.txt');
	  $getmember = explode("\n",$gettheuserfile);
	  $getmem = count($getmember)-1;
	  $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://api.telegram.org/bot'.$config['botToken'].'/sendDocument');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    $post = array(
      'chat_id' => $chat_id,
      'caption' => "*إجمالي الأعضاء المحظورين:* `$getmem`",
      'parse_mode' => "markdown",
      "reply_to_message_id"=> $message_id,
      'document' => new CURLFile(realpath('Banned.txt'))
      );
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
    curl_exec($ch);

    unlink('Banned.txt');

}

////////////====[MUTELIST]====////////////
if(strpos($message, "/mutelist") === 0 and $userId == $config['adminID']){
  $mutelist = fetchMutelist();

   if(!$mutelist){
   bot('sendmessage',[
     'chat_id'=>$chat_id,
     'text'=>"<b>لم تقم بكتم صوت أي شخص حتى الآن.</b>",
     'parse_mode'=>'html',
     'reply_to_message_id'=> $message_id]);
  }

   foreach ($mutelist as $result){
     $timer = fetchMuteTimer($result);
     $banuser = file_get_contents('Muted.txt');
     $banuser .= $result." Muted until ".date("F j, Y, g:i a",$timer['mute_timer'])."\n";
     file_put_contents('Muted.txt',$banuser);
   }    

   $gettheuserfile = file_get_contents('Muted.txt');
   $getmember = explode("\n",$gettheuserfile);
   $getmem = count($getmember)-1;
   $ch = curl_init();
   curl_setopt($ch, CURLOPT_URL, 'https://api.telegram.org/bot'.$config['botToken'].'/sendDocument');
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
   curl_setopt($ch, CURLOPT_POST, 1);
   $post = array(
     'chat_id' => $chat_id,
     'caption' => "*إجمالي الأعضاء الذين تم كتمهم:* `$getmem`",
     'parse_mode' => "markdown",
     "reply_to_message_id"=> $message_id,
     'document' => new CURLFile(realpath('Muted.txt'))
     );
   curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
   curl_exec($ch);

   unlink('Muted.txt');

}

////////////====[STATS]====////////////
if(strpos($message, "/userstats ") === 0 and $userId == $config['adminID']){
  $id = substr($message, 11);
  $uStats = fetchUserStats($id);

  bot('sendmessage',[
    'chat_id'=>$chat_id,
    'text'=>"≡ <b>احصائيات المستخدم <code>$id</code></b>

- <ins>Total Cards Checked:</ins> ".$uStats['total_checked']."
- <ins>Total CVV Cards:</ins> ".$uStats['total_cvv']."
- <ins>Total CCN Cards:</ins> ".$uStats['total_ccn']."",
    'parse_mode'=>'html',
    'reply_to_message_id'=> $message_id

  ]); 

}

if(strpos($message, "/botstats") === 0 and $userId == $config['adminID']){
    $querytotalrows	= 	mysqli_query($conn,"SELECT id FROM users WHERE id = (SELECT MAX(id) FROM users)");
		$fetchtotalrows = $querytotalrows->fetch_assoc();
		$totalrows = $fetchtotalrows['id'];

    $gStats = fetchGlobalStats();

    bot('sendmessage',[
      'chat_id'=>$chat_id,
      'text'=>"≡ <b>Bot Stats</b>
  
- <ins>Total Members:</ins> ".$totalrows."
- <ins>Total Banned:</ins> ".totalBanned()."
- <ins>Total Muted:</ins> ".totalMuted()."

≡ <b>Global Checker Stats</b>

- <ins>Total Cards Checked:</ins> ".$gStats['total_checked']."
- <ins>Total CVV Cards:</ins> ".$gStats['total_cvv']."
- <ins>Total CCN Cards:</ins> ".$gStats['total_ccn']."",
      'parse_mode'=>'html',
      'reply_to_message_id'=> $message_id
  
    ]); 
}
?>
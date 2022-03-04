## 🚀 الميزات

# بوت الفحص الاول يعمل علي [Heroku]

مرحبا بك في بوت القائد ماكس الاول لفحص الفيزات والاقوي.

     
    ```` 
    ≡ احصائيات المستخدم

    - Total Cards Checked: 25
    - Total CVV Cards: 4
    - Total CCN Cards: 2

    ≡ احصائيات المدقق العالمية

    - Total Cards Checked: 30
    - Total CVV Cards: 8
    - Total CCN Cards: 7
    ```` 
- **تاجر الشريط [المستخدم] **
    - يمكن للمستخدمين إضافة مفتاح SK الخاص بهم والتحقق من CC باستخدام مفتاح SK المضاف

## 🛠 الاوامر
- **💳 اوامر الفحص**
    ```
    /ss | !ss - Stripe [Auth]
    /sm | !sm - Stripe [Merchant]
    /schk | !schk - User Stripe Merchant [Needs SK]

    /apikey sk_live_xxx - Add SK Key for /schk gate
    /myapikey | !myapikey - View the added SK Key for /schk gate
    ```

- **📡 اوامر اخري**
    ```
    /me | !me - User's Info
    /stats | !stats - Checker Stats
    /key | !key - SK Key Checker
    /bin | !bin - Bin Lookup
    /iban | !iban - IBAN Checker
    ```

  
## ⚙️ تعيين

### الاستضافة على Heroku
    
 [![تنصيب علي Heroku](https://www.herokucdn.com/deploy/button.svg)](https://dashboard.heroku.com/new?template=https://www.github.com/MACS-AR-MACS-CC-BOT)
 - نجمة وشوكة هذا الريبو
 - انقر فوق الزر `` نشر إلى Heroku '' الموضح أعلاه
 - احصل على قاعدة بيانات مجانية من [هنا](https://freesqldatabase.com)
 - تسجيل الدخول إلى [WebPHPmyAdmin](http://www.phpmyadmin.co) مع أوراق اعتماد DB
 - استيراد ملف checkerbot.sql إلى قاعدة البيانات الخاصة بك من خلال PHPmyAdmin
 - أدخل Vars المطلوبة أثناء نشر التطبيق
 - اضبط Webhook على main.php في تطبيق Heroku
   - https://api.telegram.org/bot[BOTTOKEN]/setWebhook?url=https://[HEROKUAPPNAME].herokuapp.com/main.php   <br />
    افتح عنوان URL مع استبدال "[BOTTOKEN]" باستخدام BotToken و "[HEROKUAPPNAME]" باسم تطبيق Heroku الخاص بك
 <br />


## 🎯 Author

- [@MACS-AR](https://www.github.com/MACS-AR)


  
  

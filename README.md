# Laravel Notifications
###### tags: `Laravel` `php` `Notifications` `telegram`

## 前言

這次使用[Telegram Notifications Channel for Laravel](https://github.com/laravel-notification-channels/telegram)使 Telegram 機器人動起來

利用 artisan 命令行 讓 Telegram 機器人說話

## 安裝
```
$ laravel new laravel-notifications
$ composer require laravel-notification-channels/telegram
```

## 設定你的機器人
[BotFather](https://core.telegram.org/bots#6-botfather)
根據文件取得 Bot API Token


YOUR BOT TOKEN HERE 改成剛剛拿到的 Bot API Token
```
// config/services.php
'telegram-bot-api' => [
    'token' => env('TELEGRAM_BOT_TOKEN', 'YOUR BOT TOKEN HERE')
],
```
把機器人邀進群組跟他說話


利用下方api可取得當前機器人的chat_id
```
https://api.telegram.org/bot<YourBOTToken>/getUpdates
```
```
{
    "ok": true,
    "result": [
        {
            "update_id": 52648305,
            "message": {
                "message_id": 47,
                "from": {
                    "id": 914475195,
                    "is_bot": false,
                    "first_name": "Lin",
                    "last_name": "Bing Hua"
                },
                "chat": {
                    "id": -575994593,
                    "title": "BotTest",
                    "type": "group",
                    "all_members_are_administrators": true
                },
                "date": 1618190009,
                "sticker": {
                    "width": 512,
                    "height": 461,
                    "emoji": "😠",
                    "set_name": "ARU_full",
                    "is_animated": false,
                    "thumb": {
                        "file_id": "AAMCBQADGQEAAy9gc565HZWdgHD1YekfWOvZO7J6xAACLwADwZuBCAeTKdJv0cY88K-zMgAEAQAHbQAD46IBAAEeBA",
                        "file_unique_id": "AQAD8K-zMgAE46IBAAE",
                        "file_size": 3892,
                        "width": 128,
                        "height": 115
                    },
                    "file_id": "CAACAgUAAxkBAAMvYHOeuR2VnYBw9WHpH1jr2TuyesQAAi8AA8GbgQgHkynSb9HGPB4E",
                    "file_unique_id": "AgADLwADwZuBCA",
                    "file_size": 14286
                }
            }
        },
        {
            "update_id": 52648306,
            "message": {
                "message_id": 48,
                "from": {
                    "id": 1338202818,
                    "is_bot": false,
                    "first_name": "Neo",
                    "language_code": "zh-hans"
                },
                "chat": {
                    "id": -575994593,
                    "title": "BotTest",
                    "type": "group",
                    "all_members_are_administrators": true
                },
                "date": 1618190021,
                "sticker": {
                    "width": 512,
                    "height": 461,
                    "emoji": "😃",
                    "set_name": "ARU_full",
                    "is_animated": false,
                    "thumb": {
                        "file_id": "AAMCBQADGQEAAzBgc57FcWA8pJ9utFcjxOMpRTtOswACKwADwZuBCCHkf5lSAAE8xwq4szIABAEAB20AA9UeAAIeBA",
                        "file_unique_id": "AQADCrizMgAE1R4AAg",
                        "file_size": 4532,
                        "width": 128,
                        "height": 115
                    },
                    "file_id": "CAACAgUAAxkBAAMwYHOexXFgPKSfbrRXI8TjKUU7TrMAAisAA8GbgQgh5H-ZUgABPMceBA",
                    "file_unique_id": "AgADKwADwZuBCA",
                    "file_size": 17082
                }
            }
        }
    ]
}
```
## 創建通知

```
$ php artisan make:notification InvoicePaid    
```
在chat_id的位置填上剛剛查到的chat_id
```
//app/Notifications/InvoicePaid.php

    public function __construct(array $message)
    {
        $this->message = $message;
    }

    public function via()
    {
        return TelegramChannel::class;
    }

    public function toTelegram()
    {
        return TelegramMessage::create()->to('chat_id')
            ->content($this->message['message']);
    }
```

## 創建命令行

```
$ php artisan make:command TelegramBot      
```

```
    protected $signature = 'bot:say';
    
    protected $description = 'let telegram bot talk';
    
    public function handle()
    {
        $notiable = [
            'message' => $this->ask('我要說什麼?')
        ];
        Notification::route(TelegramChannel::class, '')
            ->notify(new InvoicePaid($notiable));
    }

```

## 測試
打上指令行開始測試
```
$ php artisan bot:say  
```

![](https://i.imgur.com/xQIc3WK.png)


![](https://i.imgur.com/aOGqEzu.png)


## 備註

如果bot不能講話 可以到 Bot_father 調整設定 
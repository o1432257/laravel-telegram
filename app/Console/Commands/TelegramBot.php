<?php

namespace App\Console\Commands;

use App\Notifications\InvoicePaid;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;
use NotificationChannels\Telegram\TelegramChannel;

class TelegramBot extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bot:say';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'let telegram bot talk';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $notiable = [
            'message' => $this->ask('我要說什麼?')
        ];
        Notification::route(TelegramChannel::class, '')
            ->notify(new InvoicePaid($notiable));
    }
}

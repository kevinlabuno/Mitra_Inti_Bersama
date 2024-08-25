<?php

namespace App\Console\Commands;

use App\Http\Controllers\Api\Ppob\LoginController;
use Illuminate\Console\Command;

class MyAccessTokenPpobCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:my-access-token-ppob-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Token PPOB updated';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        app(LoginController::class)->loginAuhtPpob();
        
        $this->info('Scheduled command executed!');
    }
}


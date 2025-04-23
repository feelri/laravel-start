<?php

namespace App\Console\Commands;

use App\Events\OrderShipmentStatusUpdated;
use App\Models\User\User;
use Illuminate\Console\Command;

class TestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
//		$socketId = '118570228.171161344';
//		$channelName = 'private-App.Models.User.1';
//		$stringToAuth = $socketId . ':' . $channelName;
//		$hashed = hash_hmac('sha256', $stringToAuth, env('REVERB_APP_SECRET'));
//		dd(env('REVERB_APP_KEY') . ':' . $hashed);

		$user = User::find(1);
//		$tokenName = 'auth';
//		$user->tokens()->where('name', $tokenName)->delete();
//		dd($user->createToken($tokenName));

		$res = OrderShipmentStatusUpdated::dispatch($user, 'Hello World，How are you');
		dd($res);
    }
}

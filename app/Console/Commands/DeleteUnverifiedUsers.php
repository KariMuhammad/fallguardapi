<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class DeleteUnverifiedUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:delete-unverified-users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete unverified users after 24 hours of registration.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $users = \App\Models\User::where('email_verified_at', null)->where('created_at', '<', now()->subDay())->get();
        foreach ($users as $user) {
            $user->delete();
        }
    }
}

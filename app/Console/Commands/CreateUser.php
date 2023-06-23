<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create-user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a user';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $userName = $this->ask('Name?');
        $userEmail = $this->ask('Email?');
        $userPass = $this->ask('Password?');

        if (! $userName || ! $userEmail || ! $userPass) {
            $this->error('Not enough details.');

            return Command::FAILURE;
        }
        User::create([
            'name' => $userName,
            'email' => $userEmail,
            'password' => Hash::make($userPass),
        ]);

        $this->info('User created!');

        return Command::SUCCESS;
    }
}

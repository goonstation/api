<?php

namespace App\Console\Commands;

use App\Actions\Fortify\CreateNewUser;
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
        $userAdmin = $this->choice('Admin?', ['Yes', 'No'], 'No');
        $userPass = $this->ask('Password?');

        if (! $userName || ! $userEmail || ! $userPass) {
            $this->error('Not enough details.');

            return Command::FAILURE;
        }

        $action = new CreateNewUser();
        $action->create([
            'name' => $userName,
            'email' => $userEmail,
            'password' => $userPass,
            'password_confirmation' => $userPass,
            'is_admin' => $userAdmin === 'Yes',
        ]);

        $this->info('User created!');

        return Command::SUCCESS;
    }
}

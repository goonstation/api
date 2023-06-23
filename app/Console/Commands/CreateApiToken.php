<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class CreateApiToken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create-api-token {user : The ID of the user}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create an API Token for a user';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $userId = $this->argument('user');
        $tokenName = $this->ask('What name should the token have?');

        $user = User::find($userId);
        if (! $user) {
            $this->error('That user does not exist.');

            return Command::FAILURE;
        }
        $token = $user->createToken($tokenName);

        $this->info('Your new token is: '.$token->plainTextToken);

        return Command::SUCCESS;
    }
}

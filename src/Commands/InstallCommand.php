<?php

namespace Dcodegroup\LaravelMyobOauth\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'laravel-myob:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install all of the Laravel MYOB resources';

    /**
     * @return void
     */
    public function handle()
    {
        if (! Schema::hasTable('myob_tokens') && ! class_exists('CreateMyobTokensTable')) {
            $this->comment('Publishing Laravel MYOB Migrations');
            $this->callSilent('vendor:publish', ['--tag' => 'laravel-myob-oauth-migrations']);
        }

        $this->comment('Publishing Laravel MYOB Configuration...');
        $this->callSilent('vendor:publish', ['--tag' => 'laravel-myob-oauth-config']);

        $this->info('Laravel MYOB scaffolding installed successfully.');
    }
}

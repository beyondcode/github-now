<?php

namespace App\Console\Commands;

use GitWrapper\GitWrapper;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class UpdateRepositoryCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'github-now:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update your personal GitHub repository.';

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
        $this->ensurePrivateKeyExists();

        $gitWrapper = new GitWrapper();

        if (! is_null(config('services.ssh.key'))) {
            $gitWrapper->setPrivateKey(config('services.ssh.key'));
        }

        $repository = 'git@github.com:'.config('services.git.username').'/'.config('services.git.username').'.git';
        $localPath = storage_path('repository/'.config('services.git.username'));

        $this->cleanupRepository($localPath);

        $this->info('Cloning repository.');
        $git = $gitWrapper->cloneRepository($repository, $localPath);

        $this->info('Generating profile');
        $profile = $this->generateProfile();
        file_put_contents($localPath.'/README.md', $profile);

        if ($git->hasChanges()) {
            $this->info('Pushing new profile to GitHub');
            $git->add('README.md');
            $git->commit('Updated profile');
            $git->push(['force' => true]);
        } else {
            $this->info('Profile has not changed. Skipping.');
        }

        $this->info('All done. ✨');

        return 0;
    }

    protected function ensurePrivateKeyExists()
    {
        if (! is_null(config('services.ssh.key')) &&! is_file(config('services.ssh.key'))) {
            $this->error("Your SSH key ".config('services.ssh.key')." can not be found.");
            exit(0);
        }
    }

    protected function cleanupRepository(string $localPath)
    {
        if (is_dir($localPath)) {
            $this->info('Cleaning up existing repository path.');

            File::deleteDirectory($localPath);
        }
    }

    protected function generateProfile()
    {
        return view('profile', []);
    }
}

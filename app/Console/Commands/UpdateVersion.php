<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class UpdateVersion extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'version:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update application version from Git tags';

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
        // Mengambil tag terbaru dari Git
        $version = trim(exec('git describe --tags $(git rev-list --tags --max-count=1)'));

        // Debug: Tampilkan versi yang diambil
        $this->info("Detected version: $version");

        if (empty($version)) {
            $this->error('No version tags found.');
            return 1;
        }

        // Path ke file .env
        $envPath = base_path('.env');

        if (!File::exists($envPath)) {
            $this->error('.env file not found.');
            return 1;
        }

        // Membaca isi file .env
        $envContents = File::get($envPath);

        // Mengganti nilai APP_VERSION di file .env dengan versi terbaru
        $envContents = preg_replace('/^APP_VERSION=.*$/m', "APP_VERSION={$version}", $envContents);

        // Menyimpan perubahan ke file .env
        File::put($envPath, $envContents);

        // Menampilkan pesan sukses
        $this->info("Version updated to: $version");

        return 0;
    }
}
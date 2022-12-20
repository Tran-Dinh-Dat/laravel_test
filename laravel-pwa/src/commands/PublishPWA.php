<?php

namespace Admin\LaravelPwa\commands;

use Illuminate\Support\Facades\File;
use Illuminate\Console\Command;

class PublishPWA extends Command
{
  /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'laravel-pwa:publish';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    public $composer;

    public function __construct() {
      parent::__construct();
      $this->composer = app()['composer'];
    }
    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
      $publicDir = public_path();

      $manifestTemplate = file_get_contents(__DIR__. '/../stubs/manifest.stub');
      $this->createFile($publicDir. DIRECTORY_SEPARATOR, 'manifest.json', $manifestTemplate);
      $this->info('manifest.json file is published');

      $this->info('Generating autoload file');
      $this->composer->dumpOptimized();

      $this->info('Greeting!...Enjoy PWA site');
    }

    public function createFile($path, $fileName, $content)
    {
      if (!file_exists($path)) {
        mkdir($path, 0755, true);
      }

      $path = $path.$fileName;

      file_put_contents($path, $content);
    }
}
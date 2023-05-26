<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use WebPConvert\WebPConvert;

class ConvertWebp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:webp';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Converts all images to webp';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $path = public_path('images');

        $this->generateWebpImages($path);

        $this->info('Webp image generation completed.');
    }

    protected function generateWebpImages($directory)
    {
        $files = File::allFiles($directory);

        foreach ($files as $file) {
            $extension = $file->getExtension();

            if ($extension !== 'webp') {
                $webpPath = $file->getPath() . '/' . $file->getFilename() . '.webp';

                if (!File::exists($webpPath)) {
                    $this->info("Generating webp image for: " . $file->getFilename());

                    WebPConvert::convert($file->getPathname(), $webpPath, ['convert' => [
                      'quality' => 75,  // all convert option can be entered here (ie "quality")
                    ]]);
                }
            }
        }

        $directories = File::directories($directory);

        foreach ($directories as $subdirectory) {
            $this->generateWebpImages($subdirectory);
        }
    }
}

<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;

class ProcessPosterImage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $concert;
    public function __construct($concert)
    {
        $this->concert = $concert;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $imageContent = Storage::disk('public')->get($this->concert->poster_image_path);
        $image = ImageManager::gd()->read($imageContent);

        $image->resize(600, 776)->reduceColors(255);

        Storage::disk('public')->put($this->concert->poster_image_path, $image->encode()->toString());
    }
}

<?php

namespace App\Providers;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class MultipleFileUploadProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        // Make sure multiple files array is below certain size.
        Validator::extend('max_uploaded_file_size', function ($attribute, $value, $parameters, $validator) {
            $total_size = array_reduce(
                $value, function ($sum, $item) {
                // each item is UploadedFile Object
                $sum += filesize($item->path());
                return $sum;
            });
            // $parameters[0] in kilobytes
            return $total_size < $parameters[0] * 1024;
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}

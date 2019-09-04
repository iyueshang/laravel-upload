<?php

/*
 * This file is part of the Yueshang/laravel-uploader.
 *
 * (c) Yueshang <i@Yueshang.me>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Yueshang\LaravelUploader;

use Illuminate\Support\Facades\Facade;

class LaravelUploader extends Facade
{
    public static function routes()
    {
        if (!self::$app->routesAreCached()) {
            self::$app->make('router')->post('files/upload', [
                'uses' => '\Yueshang\LaravelUploader\Http\Controllers\UploadController@upload',
                'as' => 'file.upload',
            ]);
        }
    }
}

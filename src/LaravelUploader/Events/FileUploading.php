<?php

/*
 * This file is part of the Yueshang/laravel-uploader.
 *
 * (c) Yueshang <i@Yueshang.me>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Yueshang\LaravelUploader\Events;

use Illuminate\Http\UploadedFile;

class FileUploading
{
    public $file;

    /**
     * Create a new event instance.
     *
     * @param \Illuminate\Http\UploadedFile $file
     */
    public function __construct(UploadedFile $file)
    {
        $this->file = $file;
    }
}

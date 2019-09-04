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

class FileUploaded
{
    public $file;

    /**
     * The result of the uploaded file.
     *
     * @var array
     */
    public $result;

    /**
     * The strategy of the uploaded file.
     *
     * @var string
     */
    public $strategy;

    /**
     * The config of strategy.
     *
     * @var array
     */
    public $config;

    /**
     * Create a new event instance.
     *
     * @param \Illuminate\Http\UploadedFile           $file
     * @param array                                   $result
     * @param \Yueshang\LaravelUploader\Events\string $strategy
     * @param array                                   $config
     */
    public function __construct(UploadedFile $file, array $result, string $strategy, array $config)
    {
        $this->file = $file;
        $this->result = $result;
        $this->strategy = $strategy;
        $this->config = $config;
    }
}

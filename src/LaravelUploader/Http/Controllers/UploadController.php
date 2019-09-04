<?php

/*
 * This file is part of the Yueshang/laravel-uploader.
 *
 * (c) Yueshang <i@Yueshang.me>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Yueshang\LaravelUploader\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Event;
use Yueshang\LaravelUploader\Events\FileDeleted;
use Yueshang\LaravelUploader\Events\FileUploaded;
use Yueshang\LaravelUploader\Events\FileUploading;
use Yueshang\LaravelUploader\Services\FileUpload;

/**
 * class UploadController.
 */
class UploadController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware(config('uploader.middleware', []));
    }

    /**
     * Handle file upload.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function upload(Request $request)
    {
        $strategy = $request->get('strategy', 'default');
        $config = uploader_strategy($strategy);

        $inputName = Arr::get($config, 'input_name', 'file');
        $directory = Arr::get($config, 'directory', '{Y}/{m}/{d}');
        $disk = Arr::get($config, 'disk', 'public');
        if (!$request->hasFile($inputName)) {
            return [
                'success' => false,
                'error' => 'no file found.',
            ];
        }
        $file = $request->file($inputName);
        $mime = $file->getClientMimeType();

        if (!\in_array($mime, $config['mimes'])) {
            return [
                'success' => false,
                'error' => \sprintf('Invalid mime "%s".', $mime),
            ];
        }

        Event::fire(new FileUploading($file));

        $filename = $this->getFilename($file, $config);

        $result = app(FileUpload::class)->store($file, $disk, $filename, $directory);

        if (!is_null($modified = Event::fire(new FileUploaded($file, $result, $strategy, $config), [], true))) {
            $result = $modified;
        }

        return $result;
    }

    public function getFilename(UploadedFile $file, $config)
    {
        switch (Arr::get($config, 'filename_hash', 'default')) {
            case 'original':
                return $file->getClientOriginalName();
            case 'md5_file':
                return md5_file($file->getRealPath()).'.'.$file->guessExtension();

                break;
            case 'random':
            default:
                return $file->hashName();
        }
    }

    /**
     * Delete file.
     *
     * @param Request $request
     *
     * @return array
     */
    public function delete(Request $request)
    {
        $result = ['result' => app(FileUpload::class)->delete($request->file)];

        Event::fire(new FileDeleted($request->file));

        return $result;
    }
}

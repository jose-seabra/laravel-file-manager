<?php

namespace Alexusmai\LaravelFileManager\Events;

use Illuminate\Http\Request;

class FilesUploaded
{
    /**
     * @var string
     */
    public $disk;

    /**
     * @var string
     */
    public $path;

    /**
     * @var \Illuminate\Http\UploadedFile
     */
    public $files;

    /**
     * @var string|null
     */
    public $overwrite;

    /**
     * FilesUploaded constructor.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->disk = $request->input('disk');
        $this->path = $request->input('path');
        $this->overwrite = $request->input('overwrite');

        foreach ($request->file('files') as $key => $file) {
            $this->files[$key]['path'] = $this->path.'/'.$file->getClientOriginalName();
            $this->files[$key]['name'] = $file->getClientOriginalName();
            $this->files[$key]['mimeType'] = $file->getClientMimeType();
            $this->files[$key]['error'] = $file->getError();
        }
    }

    /**
     * @return string
     */
    public function disk()
    {
        return $this->disk;
    }

    /**
     * @return string
     */
    public function path()
    {
        return $this->path;
    }

    /**
     * @return array
     */
    public function files()
    {
        return array_map(function ($file) {
            return [
                'name'      => $file->getClientOriginalName(),
                'path'      => $this->path.'/'.$file->getClientOriginalName(),
                'extension' => $file->extension(),
            ];
        }, $this->files);
    }

    /**
     * @return bool
     */
    public function overwrite()
    {
        return !!$this->overwrite;
    }
}

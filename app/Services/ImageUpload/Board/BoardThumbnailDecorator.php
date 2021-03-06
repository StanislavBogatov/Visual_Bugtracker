<?php

namespace App\Services\ImageUpload\Board;

use Illuminate\Http\UploadedFile;
use App\Services\ImageUpload\AbstractFileUploadService;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager as Image;

/**
 * Class BoardThumbnailDecorator.
 * Manages thumbnail image upload/deletion.
 *
 * @package App\Services\ImageUpload\Board
 */
class BoardThumbnailDecorator extends AbstractFileUploadService
{

    protected $uploadService;
    protected $prefix;

    public function __construct(string $basePath, AbstractFileUploadService $uploadService, string $prefix = 'thumbnail_')
    {
        parent::__construct($basePath);

        $this->uploadService = $uploadService;
        $this->prefix = $prefix;
    }

    /**
     * @param UploadedFile $file
     * @param string $newName
     * @param string|null $oldName Defines a file name to be deleted/stashed.
     * @return string $newName
     */
    public function upload(UploadedFile $file, string $newName = null, string $oldName = null): string
    {
        /*
         * Upload a full sized image and get it's storage name.
         */
        $newName = $this->uploadService->upload($file, $newName, $oldName);

        $uploadedImage = (new Image)->make($file)->resize(null, 300, function ($constraint) {
            $constraint->aspectRatio();
        });

        Storage::put($this->basePath . '/' . $this->prefix . $newName, (string)$uploadedImage->stream());

        return $newName;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function delete(string $name): bool
    {
        $this->uploadService->delete($name);
        Storage::delete($this->basePath . '/' . $this->prefix . $name );
        return true;
    }
}

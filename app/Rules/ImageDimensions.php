<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Http\UploadedFile;

class ImageDimensions implements Rule
{
    private $minWidth;
    private $maxWidth;
    private $minHeight;
    private $maxHeight;
    private $errorMessage;

    /**
     * Create a new rule instance.
     *
     * @param int $minWidth
     * @param int $maxWidth
     * @param int $minHeight
     * @param int $maxHeight
     */
    public function __construct($minWidth, $maxWidth, $minHeight = null, $maxHeight = null)
    {
        $this->minWidth = $minWidth;
        $this->maxWidth = $maxWidth;
        $this->minHeight = $minHeight ?? $minWidth; // Default to minWidth if not specified
        $this->maxHeight = $maxHeight ?? $maxWidth; // Default to maxWidth if not specified
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (!$value instanceof UploadedFile) {
            return false;
        }

        // Get image dimensions
        $dimensions = getimagesize($value->getRealPath());
        
        if (!$dimensions) {
            $this->errorMessage = 'Unable to determine image dimensions.';
            return false;
        }

        $width = $dimensions[0];
        $height = $dimensions[1];

        // Check width
        if ($width < $this->minWidth) {
            $this->errorMessage = "Image width must be at least {$this->minWidth}px. Current width: {$width}px.";
            return false;
        }

        if ($width > $this->maxWidth) {
            $this->errorMessage = "Image width must not exceed {$this->maxWidth}px. Current width: {$width}px.";
            return false;
        }

        // Check height
        if ($height < $this->minHeight) {
            $this->errorMessage = "Image height must be at least {$this->minHeight}px. Current height: {$height}px.";
            return false;
        }

        if ($height > $this->maxHeight) {
            $this->errorMessage = "Image height must not exceed {$this->maxHeight}px. Current height: {$height}px.";
            return false;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->errorMessage ?? 'The image dimensions are invalid.';
    }
}

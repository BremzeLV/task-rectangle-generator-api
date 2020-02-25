<?php namespace App\Helpers;

use App\Exceptions\ApiException;
use App\ImageGeneratorRectangles;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class ImageGenerator
{
    /**
     * Generated Canvas image
     * @var resource an image resource identifier on success
     */
    private $canvas;

    /**
     * Canvas width
     * @var integer
     */
    private $width;

    /**
     * Canvas height
     * @var integer
     */
    private $height;

    /**
     * Canvas color code in RGB
     * @var array
     */
    private $color;

    /**
     * ImageGenerator constructor.
     * @param $width
     * @param $height
     * @param string $color
     */
    public function __construct($width, $height, $color = '#ffffff')
    {
        //creating canvas and getting RGB color codes from hex
        $this->canvas = imagecreatetruecolor($width, $height);
        $this->color  = self::colorHEXToRGB($color);
        $this->width  = $width;
        $this->height = $height;

        $color = imagecolorallocate($this->canvas, $this->color['r'], $this->color['g'], $this->color['b']);

        imagefill($this->canvas, 0, 0, $color);
    }

    /**
     * Draws rectangles on canvas.
     * @param \App\ImageGeneratorRectangles $rectangles
     * @return resource
     */
    public function drawRectangles($rectangles){
        //drawing rectangles on image
        foreach($rectangles as $rectangle){
            $colorHEX = self::colorHEXToRGB($rectangle->color);
            $color = imagecolorallocate($this->canvas, $colorHEX['r'], $colorHEX['g'], $colorHEX['b']);

            imagefilledrectangle($this->canvas, $rectangle->x, $rectangle->y, $rectangle->x+$rectangle->width, $rectangle->y+$rectangle->height, $color);
        }

        return $this->canvas;
    }

    /**
     * Saves canvas in local storage.
     * @param string|null $filename
     * @return bool
     */
    public function saveCanvas($filename = null){
        //generating image
        ob_start();
        imagepng($this->canvas);
        $image = ob_get_clean();

        //saving memory
        imagedestroy($this->canvas);

        if( empty($filename) ) {
            $filename = rand(0,9999).time();
        }

        //saving image
        return Storage::disk('public')->put($filename, $image);
    }


    /**
     * Validates canvas rectangles if they are going out of canvas or they collide with each other.
     * @param array $canvas
     * @param array $rectangles
     * @return array Returns array of errors where there are problems with rectangles and or canvas. Empty if no errors.
     */
    public static function validateCanvasRectangles($canvas, $rectangles){
        $inputErrors = array();

        foreach($rectangles as $key => $rectangle){
            // checks if positioned in canvas
            if( $canvas['width']  < $rectangle['x'] )
                $inputErrors['rectangles']["rectangles.{$key}.x"]  = ['Rectangle X values must be less than canvas width values.'];

            if( $canvas['height'] < $rectangle['y'] )
                $inputErrors['rectangles']["rectangles.{$key}.y"] = ['Rectangle Y values must be less than canvas height values.'];

            if( $canvas['width']  < $rectangle['x'] + $rectangle['width'] )
                $inputErrors['rectangles']["rectangles.{$key}.width"]  = ['Rectangle width values can\'t exceed canvas width values. Rectangle is outside of Canvas.'];

            if( $canvas['height'] < $rectangle['y'] + $rectangle['height'] )
                $inputErrors['rectangles']["rectangles.{$key}.height"]  = ['Rectangle height values can\'t exceed canvas height values. Rectangle is outside of Canvas.'];

            $rectangleCords = array(
                'x' => $rectangle['x'],
                'y' => $rectangle['y'],
                'x1' => $rectangle['x'] + $rectangle['width'],
                'y1' => $rectangle['y'] + $rectangle['height'],
            );

            foreach($rectangles as $key2 => $rectangle2){
                if($rectangle['id'] !== $rectangle2['id']){
                    $rectangleCords2 = array(
                        'x' => $rectangle2['x'],
                        'y' => $rectangle2['y'],
                        'x1' => $rectangle2['x'] + $rectangle2['width'],
                        'y1' => $rectangle2['y'] + $rectangle2['height'],
                    );

                    if (
                        $rectangleCords['x']   < $rectangleCords2['x1'] &&
                        $rectangleCords['x1']  > $rectangleCords2['x'] &&
                        $rectangleCords['y']   < $rectangleCords2['y1'] &&
                        $rectangleCords['y1']  > $rectangleCords2['y']
                    ){
                        $inputErrors['rectangles']["rectangles.{$key}.height"]   = ["Rectangle with key:{$key}, id: {$rectangle['id']} is intersecting with rectangle key:{$key2}, id: {$rectangle2['id']}"];
                    }
                }
            }
        }

        return $inputErrors;
    }

    /**
     * Calculates RGB values from HEX color code.
     * @param $color String colorCode
     * @return array colors in RGB
     */
    public static function colorHEXToRGB($color){
        list($r, $g, $b) = array_map(
            function($c){
                return hexdec(str_pad($c, 2, $c));
            },
            str_split( ltrim($color, '#'), strlen($color) > 4 ? 2 : 1)
        );

        return array(
            'r' => $r,
            'g' => $g,
            'b' => $b,
        );
    }
}
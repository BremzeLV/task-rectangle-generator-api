<?php

namespace Tests\Unit;

use App\Helpers\ImageGenerator;
use PHPUnit\Framework\TestCase;

class ImageGeneratorTest extends TestCase
{
    public function testValidateCanvasRectangles()
    {
        $canvas = [
            "width" => 1024,
            "height" => 1024,
            "color" => "#fff",
        ];

        //rectangles not collide
        $rectangles = [
            [
                "id"    => "my-id2",
                "x"     => 10,
                "y"     => 10,
                "height" => 100,
                "width" => 200,
                "color" => "#000"
            ],
            [
                "id"    => "my-id3",
                "x"     => 10,
                "y"     => 500,
                "height" => 100,
                "width" => 200,
                "color" => "#000"
            ]
        ];

        //rectangles collide
        $rectanglesCollide = [
            [
                "id"    => "my-id2",
                "x"     => 10,
                "y"     => 10,
                "height" => 100,
                "width" => 200,
                "color" => "#000"
            ],
            [
                "id"    => "my-id3",
                "x"     => 10,
                "y"     => 50,
                "height" => 100,
                "width" => 200,
                "color" => "#000"
            ]
        ];

        //rectangles outsideOfCanvas
        $rectanglesOutsideOfCanvas = [
            [
                "id"    => "my-id2",
                "x"     => 10,
                "y"     => 10,
                "height" => 100,
                "width" => 200,
                "color" => "#000"
            ],
            [
                "id"    => "my-id3",
                "x"     => 10,
                "y"     => 1000,
                "height" => 100,
                "width" => 200,
                "color" => "#000"
            ]
        ];

        $this->assertEmpty(ImageGenerator::validateCanvasRectangles($canvas, $rectangles));
        $this->assertNotEmpty(ImageGenerator::validateCanvasRectangles($canvas, $rectanglesCollide));
        $this->assertNotEmpty(ImageGenerator::validateCanvasRectangles($canvas, $rectanglesOutsideOfCanvas));
    }
}

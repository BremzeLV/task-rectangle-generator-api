<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testStatus()
    {
        $response = $this->get('/generation-status?id=1');

        $response->assertJsonFragment(['status' => 'failed']);

        factory(\App\ImageGeneratorCanvas::class)->create()->each(function ($canvas) {
            $canvas->rectangles()->save(factory(\App\ImageGeneratorRectangles::class)->make());
        });

        $response->assertJsonFragment(['status' => 'pending']);
    }

    public function testStore()
    {
        /*$response = $this->post('/generate-rectangles', [
            "width" => 1024,
            "height"=> 1024,
            "color"=> "#fff",
            "rectangles" => [
                [
                    "id"    => "my-id3",
                    "x"     => 10,
                    "y"     => 10,
                    "height"=> 100,
                    "width" => 200,
                    "color" =>"#000"
                ],
                [
                    "id"    => "my-id4",
                    "x"     => 100,
                    "y"     => 100,
                    "height"=> 100,
                    "width" => 200,
                    "color" => "#000"
                ]
            ]
        ]);

        $response->assertJsonFragment(['status' => 'pending']);*/
    }
}

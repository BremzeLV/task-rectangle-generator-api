<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ImageGeneratorControllerTest extends TestCase
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

        $response = $this->get('/generation-status?id=1');
        $response->assertJsonFragment(['status' => 'pending']);
    }
}

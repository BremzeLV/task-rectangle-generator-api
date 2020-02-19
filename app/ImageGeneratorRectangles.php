<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ImageGeneratorRectangles extends Model
{
    protected $table = 'image_generators_rectangles';

    protected $fillable = [
        'width', 'height', 'x', 'y', 'gen_job_id', 'color'
    ];

    public function canvas()
    {
        return $this->belongsTo('App\ImageGeneratorCanvas', 'gen_job_id');
    }
}

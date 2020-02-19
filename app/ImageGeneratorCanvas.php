<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ImageGeneratorCanvas extends Model
{
    protected $table = 'image_generators_canvas';

    protected $fillable = [
        'width', 'height', 'color', 'status'
    ];

    public function rectangles()
    {
        return $this->hasMany('App\ImageGeneratorRectangles', 'gen_job_id', 'id');
    }
}

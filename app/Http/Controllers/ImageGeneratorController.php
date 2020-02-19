<?php

namespace App\Http\Controllers;

use App\Exceptions\ApiException;
use App\Helpers\ImageGenerator;
use App\ImageGeneratorCanvas;
use App\ImageGeneratorRectangles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ImageGeneratorController extends Controller
{
    /**
     * Generating canvas and rectangles and saving it.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws ApiException
     */
    public function status(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'id' => 'required|integer|min:1',
        ]);

        if( $validator->fails() ) {
            throw new ApiException(['inputError' => $validator->getMessageBag()]);
        }

        $canvas = ImageGeneratorCanvas::find($data['id']);

        if( !empty($canvas) ){
            // if in progress
            if( $canvas->status === 'in_progress' )
                return response()->json(['status' => 'in_progress']);

            // if done
            if( $canvas->status === 'done' )
                return response()->json(['status' => 'done','url' => $canvas->generated_image_url]);

            $queueCount = ImageGeneratorCanvas::where('status', 'pending')
                ->where('id', '!=', $canvas->id)
                ->where('created_at', '<', $canvas->created_at)
                ->count();

            // id in queue
            return response()->json(['status' => 'pending', 'queue_length' => $queueCount]);

        } else {
            // if not found
            return response()->json([
                'status' => 'failed',
                'reason' => 'item_not_found'
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @throws ApiException
     */
    public function store(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        //checks if valid JSON
        if( !(json_last_error() === JSON_ERROR_NONE) ){
            $data = $request->getContent();
        }

        $validator = Validator::make($data, [
            'width'     => 'required|integer|min:640|max:1920',
            'height'    => 'required|integer|min:480|max:1080',
            'color'     => array(
                'required',
                'string',
                'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'
            ),
            'rectangles'=> 'required|array',
            'rectangles.*.id'=> 'required|distinct',
            'rectangles.*.width'=> 'required|integer|min:1',
            'rectangles.*.height'=> 'required|integer|min:1',
            'rectangles.*.x'=> 'required|integer:min:1',
            'rectangles.*.y'=> 'required|integer:min:1',
            'rectangles.*.color'=>  array(
                'required',
                'string',
                'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'
            ),
        ]);

        if( $validator->fails() ) {
            throw new ApiException(['inputError' => $validator->getMessageBag()]);
        }

        $canvas = [
            'width'     => $data['width'],
            'height'    => $data['height'],
            'color'     => $data['color'],
            'status'    => 'pending'
        ];

        $validatorGen = ImageGenerator::validateCanvasRectangles($canvas, $data['rectangles']);

        if( !empty($validatorGen) ) throw new ApiException(['inputError' => $validatorGen], 400);

        $saved_canvas = ImageGeneratorCanvas::create($canvas);

        $rectangles = array();
        foreach($data['rectangles'] as $rectangle){
            $rectangleObject = new ImageGeneratorRectangles();

            $rectangleObject->width     = $rectangle['width'];
            $rectangleObject->height    = $rectangle['height'];
            $rectangleObject->x         = $rectangle['x'];
            $rectangleObject->y         = $rectangle['y'];
            $rectangleObject->color     = $rectangle['color'];
            $rectangleObject->rect_id   = $rectangle['id'];

            $rectangles[] = $rectangleObject;
        }

        $saved_canvas->rectangles()->saveMany($rectangles);

        return response()->json([
            'success' => true,
            'id'      => $saved_canvas->id
        ]);
    }

    /**
     * Generating canvas and rectangles and saving it.
     *
     * @return \Illuminate\Http\Response
     * @throws ApiException
     */
    public function generate()
    {
        $canvasToGenerate = ImageGeneratorCanvas::where('status', 'pending')->with('rectangles')->orderBy('created_at', 'asc')->orderBy('id', 'asc')->first();

        if( !empty($canvasToGenerate) && $canvasToGenerate->count() ) {
            // saving as in progress for api status calls in this time
            $canvasToGenerate->status = 'in_progress';
            $canvasToGenerate->save();

            $fileName = "canvas-{$canvasToGenerate->id}.png";

            $canvas = new ImageGenerator($canvasToGenerate->width, $canvasToGenerate->height, $canvasToGenerate->color);
            $canvas->drawRectangles($canvasToGenerate->rectangles);

            if( !$canvas->saveCanvas($fileName) ) {
                $canvasToGenerate->status = 'pending';
                $canvasToGenerate->save();

                throw new ApiException(['outputError' => ['Can\'t save exception file']], 500);
            } else {
                $canvasToGenerate->status = 'done';
                $canvasToGenerate->generated_image_url = asset(Storage::url($fileName));
                $canvasToGenerate->save();
            }

            return response()->json([
                'success' => true,
                'url'     => asset(Storage::url($fileName))
            ]);
        } else {
            throw new ApiException(['outputError' => ['No canvas left to generate.']], 400);
        }
    }
}

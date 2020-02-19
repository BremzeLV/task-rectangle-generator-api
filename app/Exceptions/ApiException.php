<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Request;

class ApiException extends Exception
{
    /**
     * Array of the API errors passed to this exception
     *
     * @var array
     */
    private $errorData;

    /**
     * ApiException constructor.
     * @param array $errorData
     * @param int $code
     */
    public function __construct( $errorData = array(), $code = 0 )
    {
        parent::__construct('', $code);

        $this->errorData = $errorData;
    }

    /**
     * Report the exception.
     *
     * @return void
     */
    public function report()
    {
        //
    }

    /**
     * Render the exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request
     * @return void
     */
    public function render($request)
    {
        //
    }

    /**
     * @return array
     */
    public function getErrorData() {
        return $this->errorData;
    }
}
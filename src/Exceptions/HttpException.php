<?php


namespace Sczts\Skeleton\Exceptions;


class HttpException extends Exception
{
    private $message;
    private $status_code;

    public function __construct($message,$status_code = 200)
    {
        parent::__construct();
        $this->message = $message;
        $this->status_code = $status_code;
    }

    public function render()
    {
        return response()->json($this->message,$this->status_code);
    }
}

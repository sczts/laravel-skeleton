<?php


namespace Sczts\Skeleton\Exceptions;


class FromValidator extends Exception
{
    private $messages;

    public function __construct($messages = '')
    {
        $this->messages = $messages;
    }

    public function render()
    {
        return response()->json(['message' => $this->messages],422);
    }
}

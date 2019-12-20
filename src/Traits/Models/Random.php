<?php


namespace Sczts\Skeleton\Traits\Models;


trait Random
{
    public function random()
    {
        return self::inRandomOrder()->first();
    }

    public function randomId()
    {
        return self::inRandomOrder()->first()->id;
    }
}

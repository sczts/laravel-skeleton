<?php


namespace Sczts\Skeleton\Traits\ORM;


trait HasChildren
{
    use MakeTree;

    public static function getIdColumn(): string
    {
        return 'id';
    }

    public static function getPidColumn(): string
    {
        return 'pid';
    }

    public function children()
    {
        return $this->hasMany(static::class, static::getPidColumn());
    }

    public function parent()
    {
        return $this->belongsTo(static::class, static::getPidColumn());
    }


}

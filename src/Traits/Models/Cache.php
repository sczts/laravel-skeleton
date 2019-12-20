<?php


namespace Sczts\Skeleton\Traits\Models;


trait Cache
{
    public static function getAll()
    {
        
    }
    
    
    /**
     * 获取缓存前缀
     * @return string
     */
    protected function cacheKey(){
        $update_at_column = $this->getUpdatedAtColumn();
        $update_at = $this->attributes[$update_at_column]->timetamp;

        return sprintf(
            "%s/%s-%s",
            $this->getTable(),
            $this->getKey(),
            $update_at
        );
    }
}

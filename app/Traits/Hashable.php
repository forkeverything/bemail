<?php


namespace App\Traits;


use Vinkla\Hashids\Facades\Hashids;

/**
 * Allow's Hashable models to quickly encode/decode ids.
 *
 * @package App\Traits
 */
trait Hashable
{
    /**
     * Hash connection to use for encoding/decoding.
     * As specified in config/hashids.php. We should get the
     * base name of the class in lower_snake_case.
     *
     * @return string
     */
    protected static function getHashConnection()
    {
        $className = class_basename(get_called_class());
        return snake_case($className);
    }

    /**
     * Find model using it's hash.
     *
     * @param $hash
     * @return mixed
     * @throws \Exception
     */
    public static function findByHash($hash)
    {
        try {
            $id = Hashids::connection(static::getHashConnection())->decode($hash)[0];
        } catch (\Exception $e){
            throw new \Exception("Couldn't decode hash into ID");
        }
        return static::findOrFail($id);
    }

    /**
     * Accessor - Model's hash id.
     *
     * @return mixed
     */
    public function getHashAttribute()
    {
        return Hashids::connection(static::getHashConnection())->encode($this->id);
    }

}
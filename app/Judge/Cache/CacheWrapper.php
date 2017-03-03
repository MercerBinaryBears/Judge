<?php namespace Judge\Cache;

use \Cache;

/**
 * Class that decorates another to add caching on top of it
 */
class CacheWrapper
{
    public function __construct($wrapped, $cache_time = 60)
    {
        $this->wrapped = $wrapped;
        $this->cache_time = $cache_time;
        $this->enabled = true;
    }

    public function enable()
    {
        $this->enabled = true;
    }

    public function disable()
    {
        $this->enabled = false;
    }

    public function __call($name, $arguments)
    {
        // a function that will directly call the wrapped object
        $getter = function () use ($name, $arguments) {
            return call_user_func_array([$this->wrapped, $name], $arguments);
        };

        // if cache is enabled use the cache facade to proxy to the wrapped object. Otherwise, just directly call the
        // wrapped object
        if ($this->enabled) {
            $cache_key = $this->buildCallHashKey($name, $arguments);
            return Cache::remember($cache_key, $this->cache_time, $getter);
        } else {
            return $getter();
        }
    }

    /**
     * Given the items passed to __call builds a unique key for that call
     */
    protected function buildCallHashKey($name, $arguments)
    {
        $unhashed_key = $name;
        foreach ($arguments as $argument) {
            $unhashed_key .= ',' . $this->serializeArgument($argument);
        }
        return md5($unhashed_key);
    }

    /**
     * A weird method for serializing various types of objects. Eloquent Models and Dates are treated as special cases
     * but in general, we simply delegate to serialize
     */
    protected function serializeArgument($argument)
    {
        if ($argument instanceof \Judge\Models\Base) {
            return get_class($argument) . '_' . $argument->id;
        } elseif ($argument instanceof \Carbon\Carbon) {
            return $argument->timestamp;
        } else {
            return serialize($argument);
        }
    }
}

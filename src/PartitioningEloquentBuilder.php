<?php

namespace labs7in0\partitioning;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;

class PartitioningEloquentBuilder extends Builder
{
    private $partitions = [];

    public function combine($partitions, $all = true)
    {
        if (!function_exists('is_iterable')) {
            if (!is_array($arg) &&
                !$arg instanceof \Generator &&
                !$arg instanceof \Iterator) {
                throw new \Exception('The value of $partitions is not iterable');
            }
        } else {
            if (false == is_iterable($this->partitions)) {
                throw new \Exception('The value of $partitions is not iterable');
            }
        }

        foreach ($partitions as $partition) {
            $this->partitions[$partition] = $all;
        }

        return $this;
    }

    public function combineByBounds($all, ...$args)
    {
        return $this->combine($this->getModel()->getFormatter()->toPartitions(...$args), $all);
    }

    public function get($columns = ['*'])
    {
        $query = $this->getQuery();
        $clone = clone $query;

        foreach ($this->partitions as $table => $all) {
            if (!Schema::hasTable($table)) continue;

            $brother = clone $clone;
            $brother->from($table);

            $query->union($brother, $all);
        }

        return parent::get($columns);
    }
}

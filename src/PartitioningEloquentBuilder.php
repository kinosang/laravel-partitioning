<?php

namespace labs7in0\partitioning;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;

class PartitioningEloquentBuilder extends Builder
{
    private $partitions = [];

    public function combine($tables)
    {
        $this->partitions = $tables;

        return $this;
    }

    public function combineByBounds(...$args)
    {
        foreach ($this->getModel()->getFormatter()->toPartitions(...$args) as $partition) {
            $this->partitions[] = $partition;
        }

        return $this;
    }

    public function get($columns = ['*'])
    {
        return $this->_union($this->partitions)->_get();
    }

    private function _get($columns = ['*'])
    {
        return parent::get($columns);
    }

    private function _union($tables, $all = true)
    {
        if (!function_exists('is_iterable')) {
            if (!is_array($arg) &&
                !$arg instanceof \Generator &&
                !$arg instanceof \Iterator) {
                throw new \Exception();
            }
        } else {
            if (false == is_iterable($tables)) {
                throw new \Exception();
            }
        }

        $query = $this->getQuery();
        $clone = clone $query;

        foreach ($tables as $index => $table) {
            if (!Schema::hasTable($table)) continue;

            $brother = clone $clone;
            $brother->from($table);

            $query->union($brother, $all);
        }

        return $this;
    }
}

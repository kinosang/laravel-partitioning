<?php

namespace labs7in0\partitioning;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use labs7in0\partitioning\TableNameFormatters\DateFormatter;

class PartitioningModel extends Model
{
    protected $baseTable;

    public function getBaseTable()
    {
        return $this->baseTable;
    }

    public function getFormatter()
    {
        return new DateFormatter($this);
    }

    public function getTable()
    {
        if (isset($this->table)) {
            return $this->table;
        }
        return $this->getFormatter()->current();
    }

    public function newBaseQueryBuilder()
    {
        $connection = $this->getConnection();
        return new PartitioningQueryBuilder(
            $connection,
            $connection->getQueryGrammar(),
            $connection->getPostProcessor()
        );
    }

    public function newEloquentBuilder($query)
    {
        return new PartitioningEloquentBuilder($query);
    }
}

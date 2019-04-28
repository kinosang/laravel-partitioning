<?php

namespace labs7in0\partitioning;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class PartitioningQueryBuilder extends Builder
{
    public function fromQuery($query, $sub_name = 'lapuda')
    {
        if ($query instanceof \Illuminate\Database\Eloquent\Builder) {
            $query = $query->getQuery();
        }

        $sql = $query->toSql(false);

        return $this->from(DB::raw("({$sql} as {$sub_name})"));
    }

    public function toSql($secret = true)
    {
        $sql = parent::toSql();

        if ($secret === false) {
            $escaped_sql = str_replace("%", "%%", $sql);
            $sql = str_replace("?", "'%s'", $escaped_sql);

            $sql = vsprintf($sql, $this->getBindings());
        }

        return $sql;
    }
}

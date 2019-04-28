<?php

namespace labs7in0\partitioning\TableNameFormatters;

interface ITableNameFormatter
{
    public function current();

    public function toPartitions(...$args);
}

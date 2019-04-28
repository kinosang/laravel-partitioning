<?php

namespace labs7in0\partitioning\TableNameFormatters;

use Carbon\Carbon;
use labs7in0\partitioning\PartitioningModel;

class DateFormatter implements ITableNameFormatter
{
    protected $model = null;

    public function __construct(PartitioningModel $model)
    {
        $this->model = $model;
    }

    public function current()
    {
        $suffix = Carbon::today()->format('Ymd');
        return $this->format($suffix);
    }

    public function format($suffix)
    {
        return $this->getModel()->getBaseTable() . '_' . $suffix;
    }

    public function toPartitions(...$args)
    {
        $start = $args[0];
        $end = $args[1];

        if (!$start instanceof Carbon ||
            !$end instanceof Carbon) {
            throw new \Exception();
        }

        while ($end->gte($start)) {
            yield $this->format($start->format('Ymd'));
            $start->addDay();
        }
    }

    public function getModel()
    {
        return $this->model;
    }
}

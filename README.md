# Laravel Partitioning

**[Deprecated] Use DB-level partitioning instead.**

A horizontal partitioning library for Laravel Eloquent.

## Installation

```bash
$ composer require labs7in0/laravel-partitioning
```

## Usage

### Create Model

```php
class Log extends PartitioningModel
{
    protected $baseTable = 'logs';
}
```

### Run Query

```php
$logs = Log::combineByBounds(Carbon::today()->subDays(30), Carbon::today())->get();
```

## License

The MIT License

More info see [LICENSE](LICENSE)

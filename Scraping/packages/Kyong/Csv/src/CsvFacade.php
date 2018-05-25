<?php
namespace Kyong\Csv;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Kyong\Csv\Csv
 */
class CsvFacade extends Facade
{

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return Csv::class;
    }
}

<?php

namespace Kivicms\LaravelFilterable\Generic;

use Illuminate\Support\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Kivicms\LaravelFilterable\Exceptions\InvalidArgumentException;

class Templater
{

    /** @var \Carbon\Carbon $carbon */
    protected $carbon;


    function __construct(Carbon $carbon)
    {
        $this->carbon = $carbon;
    }


    /**
     * Firstly we check if $template is null and return $value if so,
     * secondly we check if value of $template is a method that we can call and call it,
     * thirdly we just apply "replacing" template that we use in LIKE string.
     *
     * @param $template
     * @param $value
     *
     * @return mixed
     */
    public function apply($template, $value)
    {
        if (is_null($template)) {
            return $value;
        } elseif (method_exists($this, Str::camel($template))) {
            return $this->{Str::camel($template)}($value);
        } else {
            return str_replace('?', $value, $template);
        }
    }


    /**
     * @param $value
     *
     * @return string
     * @throws \Kivicms\LaravelFilterable\Exceptions\InvalidArgumentException
     */
    protected function timestamp($value)
    {
        try {
            return $this->carbon->timestamp($value)->toDateTimeString();
        } catch (\Exception $exception) {
            throw new InvalidArgumentException('Provided timestamp \''.$value.'\' appears to be invalid.');
        }
    }


    /**
     * @param $value
     *
     * @return array
     * @throws \Kivicms\LaravelFilterable\Exceptions\InvalidArgumentException
     */
    protected function timestampRange($value)
    {
        $range = explode(',', $value);

        if (count($range) == 2) {
            return Arr::sort(array_map([$this, 'timestamp'], $range), null);
        }

        throw new InvalidArgumentException('Provide exactly two timestamps.');
    }


    protected function boolean($value)
    {
        if ($value == '1' || $value == 'true' || $value == 'yes') {
            return (int)true;
        } elseif ($value == '0' || $value == 'false' || $value == 'no') {
            return (int)false;
        }

        return null;
    }


    /**
     * @param $value
     *
     * @return array
     * @throws \Kivicms\LaravelFilterable\Exceptions\InvalidArgumentException
     */
    protected function range($value)
    {
        $range = explode(',', $value);

        $range = array_unique($range);

        if (empty($range) or max($range) === '' or (count($range) == 1 && max($range) === '0')) {
            throw new InvalidArgumentException('Provided range appears to be invalid.');
        }

        if (max($range) == min($range)) {
            return Arr::sort(['0', max($range)], null);
        }

        return [min($range), max($range)];
    }


    protected function whereIn($value)
    {
        return explode(config('filterable.in_separator', ','), $value);
    }
}

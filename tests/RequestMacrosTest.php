<?php

namespace Kivicms\LaravelFilterable\Test;

use Illuminate\Http\Request;
use Kivicms\LaravelFilterable\Exceptions\InvalidArgumentException;

class RequestMacrosTest extends TestCase
{

    function test_has_any_filter_returns_true()
    {
        $filter = $this->buildFilter(RequestMacroFilter::class, 'active&new');
        $this->assertTrue(resolve(Request::class)->hasAnyFilter($filter));
    }


    function test_has_any_filter_returns_false()
    {
        $filter = $this->buildFilter(RequestMacroFilter::class, 'page=1');
        $this->assertFalse(resolve(Request::class)->hasAnyFilter($filter));
    }


    function test_has_any_filter_determines_the_filter_and_returns_true()
    {
        $filter = $this->buildFilter(MacroCallingFilter::class, 'active&new');
        $this->assertTrue($filter->callHasAnyFilter());
    }


    function test_has_any_filter_determines_the_filter_and_returns_false()
    {
        $filter = $this->buildFilter(MacroCallingFilter::class, 'page=1');
        $this->assertFalse($filter->callHasAnyFilter());
    }


    function test_has_any_filter_throws_up_when_filter_is_not_provided()
    {
        $this->expectException(InvalidArgumentException::class);
        resolve(Request::class)->hasAnyFilter();
    }


    function test_full_url_with_nice_query()
    {
        $request = resolve(Request::class)->create('http://test.dev');
        $url     = $request->fullUrlWithNiceQuery(['a' => 'b', 'c', 'd' => 'e', 'f', 'g', 'h' => 'i', 'j']);

        $this->assertEquals('http://test.dev/?a=b&c&d=e&f&g&h=i&j', $url);
    }
}

class RequestMacroFilter extends \Kivicms\LaravelFilterable\Filter
{

    /**
     * @return array ex: ['method-name', 'another-method' => 'alias', 'yet-another-method' => ['alias-one', 'alias-two]]
     */
    function filterMap(): array
    {
        return ['active' => 'active', 'recent' => ['new', 'scheduled']];
    }


    function active()
    {
        return $this->builder;
    }


    function recent()
    {
        return $this->builder;
    }

}

class MacroCallingFilter extends RequestMacroFilter
{

    public function callHasAnyFilter()
    {
        return $this->request->hasAnyFilter();
    }
}

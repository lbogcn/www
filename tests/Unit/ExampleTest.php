<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        $var = [33, 44, 55];
//        $arr = ['a' => 'aa', 'b' => 'bb'];

        $keys = array_keys($var);
        $range = range(0, sizeof($var));
        dump($keys, $range, $keys == $range, !!array_diff_assoc(array_keys($var), range(0, sizeof($var))));
        $this->assertTrue(true);
    }
}

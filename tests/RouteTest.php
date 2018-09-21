<?php
declare(strict_types=1);

require_once 'class/Route.class.php';

use PHPUnit\Framework\TestCase;

final class RouteTest extends TestCase
{

    public function testCanBeInstanciated(): void {
        $this->assertInstanceOf(
            Route::class,
            new Route("testPage")
        );
    }

}
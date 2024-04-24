<?php

namespace Igarevv\Tests\Unit;

use Igarevv\Micrame\Session\Session;

class SessionTest extends \PHPUnit\Framework\TestCase
{
    protected function setUp(): void
    {
        unset($_SESSION);
    }

    public function test_get_set_session(): void
    {
        $session = new Session();
        $session->set('name', 'Ihor');
        $name = $session->get('name');
        $this->assertTrue($session->has('name'));
        $this->assertEquals('Ihor', $name);
        $session->setFlash('success', 'Success');
        $this->assertEquals(['Success'], $session->flash('success'));
        $this->assertFalse($session->hasFlash('warning'));
    }
}
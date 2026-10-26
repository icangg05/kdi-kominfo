<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Halaman situs memanggil @vite; pengujian tidak butuh hasil build (public/build).
        $this->withoutVite();
    }
}

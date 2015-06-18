<?php

use Judge\Repositories\LanguageRepository;

class DbLanguageRepositoryTest extends DbTestCase
{
    public function testAll()
    {
        $repo = new LanguageRepository();
        $this->assertNotEquals(0, $repo->all()->count());
    }
}

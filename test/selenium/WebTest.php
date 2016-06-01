<?php

namespace Mike42\WordPuzzles;

class WebTest extends \PHPUnit_Extensions_Selenium2TestCase
{
    protected function setUp()
    {
        $this->setBrowser('firefox');
        $this->setBrowserUrl('http://localhost:8080/words/');
    }

    public function testTitle()
    {
        // Simply access the main page
        $this->url('http://localhost:8080/words/');
        $this->assertEquals('Mike\'s Word-Search Generator', $this->title());
    }
}

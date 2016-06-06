<?php

namespace Mike42\WordPuzzles;

class WebTest extends \PHPUnit_Extensions_Selenium2TestCase
{
    private $base;

    protected function setUp()
    {
        $this->base = 'http://localhost:8080/words';
        $this->setBrowser('firefox');
        $this->setBrowserUrl($this -> base . '/');
    }

    public function testInfoPage()
    {
        // Simply access the main page
        $this->url($this->base . '/index.php?action=info');
        $this->assertEquals('Create Word Search', $this->title());
    }

    public function testWordSearch()
    {
        // Make a word searh with default settings
        $this->url($this->base . '/index.php');
        $this->defaultPuzzle();
        $this->genericPuzzleTest();
    }

    public function testScrambler()
    {
        // Make a word scramble with basic settings
        $this->url($this->base . '/scramble.php');
        $this->defaultPuzzle();
        $this->genericPuzzleTest();
    }

    public function testCipher()
    {
        // Make a cipher with basic settings
        $this->url($this->base . '/cipher.php');
        $this->defaultPuzzle();
        $this->genericPuzzleTest();
    }

    private function defaultPuzzle()
    {
        // Click through default size & word list
        $button=$this->byName('submit')->click();
        $button=$this->byName('submit')->click();
    }

    private function genericPuzzleTest()
    {
        // Show/hide solution, regenerate & repeat
        $this->solutionToggle();
        $this->regenerate();
        $this->solutionToggle();
    }

    private function regenerate()
    {
        // Find regenerate button & click it
        $button=$this->byName('submit')->click();
    }

    private function solutionToggle()
    {
        // Get solution & solution sub
        $puzzle = $this->byId("solution-sub");
        $solution = $this->byId("solution");
        // Solution is hidden by default
        $this->assertFalse($solution->displayed());
        $this->assertTrue($puzzle->displayed());
        // Solution is shown
        $this->byCssSelector("#solution-show input[type='button']")->click();
        $this->assertTrue($solution->displayed());
        $this->assertFalse($puzzle->displayed());
        // Solution is hidden again
        $this->byCssSelector("#solution-hide input[type='button']")->click();
        $this->assertFalse($solution->displayed());
        $this->assertTrue($puzzle->displayed());
    }
}

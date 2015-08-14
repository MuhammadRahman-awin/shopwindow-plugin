<?php

namespace spec;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CSVImporterSpec extends ObjectBehavior
{
    function let($fileName)
    {
        $this->beConstructedWith($fileName);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('CSVImporter');
    }
}

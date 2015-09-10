<?php

namespace spec;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use FileUploadErrorHandler;

class CSVImporterSpec extends ObjectBehavior
{
    function let($file, FileUploadErrorHandler $errorHandler)
    {
        $this->beConstructedWith($file, $errorHandler);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('CSVImporter');
    }
}

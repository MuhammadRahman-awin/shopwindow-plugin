<?php

namespace spec;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class FileUploadErrorHandlerSpec extends ObjectBehavior
{
    function let()
    {
        $fileData['error'] = 1;
        $fileData['type'] = 'text/csv';
        $this->beConstructedWith($fileData);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('FileUploadErrorHandler');
    }
}

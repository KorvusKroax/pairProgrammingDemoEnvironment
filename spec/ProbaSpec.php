<?php

namespace spec;

use Proba;
use PhpSpec\ObjectBehavior;

class ProbaSpec extends ObjectBehavior
{
    function it_is_initializable() : void
    {
        $this->shouldHaveType(Proba::class);
    }

    function it_should_say_hello_to_this_fine_world() : void
    {
        $this->getHelloWorld()->shouldBe('Hello World!');
    }
}

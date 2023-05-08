<?php

namespace spec;

use PhpSpec\ObjectBehavior;
use SajatConnection;
use Proba;

class ContainerSpec extends ObjectBehavior
{
    function it_can_return_a_service_instance_based_on_definition(): void
    {
        $this->beConstructedWith([
            'services' => [
                'primary_mysql_connection' => [
                    'class' => 'SajatConnection'
                ],
            ]
        ]);
        $this->get('primary_mysql_connection')->shouldBeLike(new SajatConnection);
    }

    function it_can_return_a_service_instance_based_on_another_definition(): void
    {
        $this->beConstructedWith([
            'services' => [
                'primary_mysql_connection' => [
                    'class' => 'Proba'
                ],
            ]
        ]);
        $this->get('primary_mysql_connection')->shouldBeLike(new Proba);
    }

    function it_can_return_a_service_instance_based_on_yet_another_definition(): void
    {
        $this->beConstructedWith([
            'services' => [
                'somethingElse' => [
                    'class' => 'Proba'
                ],
            ]
        ]);
        $this->get('somethingElse')->shouldBeLike(new Proba);
    }

    function it_will_always_returns_the_same_object_when_requesting_the_same_service(): void
    {
        $this->beConstructedWith([
            'services' => [
                'rantottHus' => [
                    'class' => 'Proba'
                ],
                'grillHus' => [
                    'class' => 'Proba'
                ],
            ]
        ]);

        $this->get('rantottHus')->shouldBe($this->get('rantottHus'));
        $this->get('rantottHus')->shouldNotBe($this->get('grillHus'));
    }

    function it_can_fill_arguments(): void
    {
        $this->beConstructedWith([
            'services' => [
                'someService' => [
                    'class' => 'SajatConnection',
                    'arguments' => [
                        'bela'
                    ]
                ]
            ]
        ]);
        $this->get('someService')->shouldBeLike(new SajatConnection('bela'));
    }

    function it_can_fill_multiple_arguments() : void
    {
        $this->beConstructedWith([
            'services' => [
                'someService' => [
                    'class' => 'SajatConnection',
                    'arguments' => [
                        'bela',
                        'jelszo1234'
                    ]
                ]
            ]
        ]);
        $this->get('someService')->shouldBeLike(new SajatConnection('bela', 'jelszo1234'));
    }

    function it_can_fill_arguments_that_refer_to_services() : void
    {
        $this->beConstructedWith([
            'services' => [
                'someService' => [
                    'class' => 'SajatConnection',
                    'arguments' => [
                        'juzer',
                        '@proba',
                        'rántotthús',
                    ]
                ],
                'proba' => [
                    'class' => 'Proba',
                ]
            ]
        ]);
        $this->get('someService')->shouldBeLike(
          new SajatConnection('juzer', new Proba, 'rántotthús')
        );
    }

    function it_can_return_parameters() : void
    {
        $this->beConstructedWith([
            'parameters' => [
               'databaseConnection' => 'localhost'
            ]
        ]);
        $this->getParameter('databaseConnection')->shouldBeLike('localhost');
    }

    function it_can_fill_service_definition_arguments_when_referring_to_parameters() : void
    {
        $this->beConstructedWith([
            'parameters' => [
               'databaseConnection' => 'randomHost'
            ],
            'services' => [
                'someService' => [
                    'class' => 'SajatConnection',
                    'arguments' => [
                        '%databaseConnection%'
                    ]
                ]
            ]
        ]);
        $this->get('someService')->shouldBeLike(new SajatConnection('randomHost'));
    }

    function it_can_return_all_services_with_a_certain_tag() : void
    {
        $this->beConstructedWith([
            'services' => [
                'someService' => [
                    'class' => 'SajatConnection',
                    'tags' => ['fontos']
                ],
                'someService2' => [
                    'class' => 'SajatConnection',
                    'arguments' => ['valamiArgumentum']
                ],
                'proba' => [
                    'class' => 'Proba',
                    'tags' => ['fontos']
                ],
            ]
        ]);
        $this->getServicesTagged('fontos')->shouldBeLike([
            new SajatConnection(), new Proba()
        ]);
    }
}

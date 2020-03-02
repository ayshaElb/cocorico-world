<?php

namespace App\EventSubscriber;

use App\Repository\UniversRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;

class HeaderSubscriber implements EventSubscriberInterface
{
    /** @var UniversRepository */
    private $universRepository;

    /** @var \Twig\Environment */
    private $twig;

    public function __construct(UniversRepository $universRepository, \Twig\Environment $twig)
    {
        $this->universRepository = $universRepository;
        $this->twig = $twig;
    }

    public function onControllerEvent(ControllerEvent $event)
    {
        $univers = $this->universRepository->findAllUniversWithCategoriesAndSubcategories();

        $this->twig->addGlobal('universes', $univers);
    }

    public static function getSubscribedEvents()
    {
        return [
            ControllerEvent::class => 'onControllerEvent',
        ];
    }
}

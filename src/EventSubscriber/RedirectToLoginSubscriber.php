<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class RedirectToLoginSubscriber implements EventSubscriberInterface
{
    private $router;
    private $requestStack;

    public function __construct(RouterInterface $router, RequestStack $requestStack)
    {
        $this->router = $router;
        $this->requestStack = $requestStack;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::EXCEPTION => 'onKernelException',
        ];
    }

    public function onKernelException(ExceptionEvent $event)
    {
        // Récupére l'exception
        $exception = $event->getThrowable();

        // Vérifie si c'est une exception d'accès refusé
        if ($exception instanceof AccessDeniedException) {
            // Récupére la requête actuelle
            $request = $this->requestStack->getCurrentRequest();

            // Vérifie si la requête vient de l'API
            if (strpos($request->getPathInfo(), '/api') === 0) {
                return; // Ne pas rediriger les requêtes API
            }

            // Crée une réponse de redirection
            $response = new RedirectResponse($this->router->generate('app_login'));
            $event->setResponse($response);
        }
    }
}
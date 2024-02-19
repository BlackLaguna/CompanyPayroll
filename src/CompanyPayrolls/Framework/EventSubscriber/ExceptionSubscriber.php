<?php

declare(strict_types=1);

namespace CompanyPayrolls\Framework\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final class ExceptionSubscriber implements EventSubscriberInterface
{
    public function __construct()
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => 'onKernelException',
        ];
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $throwable = $event->getThrowable();
        $request = $event->getRequest();

        if ($this->isFromCurrentContext($request)) {
            $response = new Response($throwable->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
            $event->setResponse($response);
        }
    }

    private function isFromCurrentContext(Request $request): bool
    {
        return 1 === preg_match('#^/api/companies/(.*)/payrolls/#', $request->getPathInfo());
    }
}

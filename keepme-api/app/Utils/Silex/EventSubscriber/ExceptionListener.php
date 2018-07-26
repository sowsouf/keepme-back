<?php

namespace KeepMe\Utils\Silex\EventSubscriber;

use Silex\Application;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class ExceptionListener implements EventSubscriberInterface
{
    protected $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();

        if (is_a($exception, "InvalidArgumentException")) {
            $code = 400;
        } else {
            $code = (method_exists($exception, 'getStatusCode')) ?
            $exception->getStatusCode() :
            (method_exists($exception, 'getCode') && $exception->getCode() !== 0 ? $exception->getCode() : 500);
        }
        $event->setResponse(
            new JsonResponse((true === $this->app["debug"] || 500 !== $code) ? $exception->getMessage() : null, $code)
        );
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::EXCEPTION => ['onKernelException', 32],
        ];
    }
}

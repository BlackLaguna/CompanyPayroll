<?php

declare(strict_types=1);

namespace SharedKernel\Framework\Messenger\Bus;

use SharedKernel\Application\Bus\QueryBus;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;

final class MessengerQueryBus implements QueryBus
{
    use HandleTrait;

    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    public function dispatch(object $query): mixed
    {
        return $this->handle($query);
    }
}

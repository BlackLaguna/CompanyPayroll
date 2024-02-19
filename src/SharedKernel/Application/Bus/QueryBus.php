<?php

declare(strict_types=1);

namespace SharedKernel\Application\Bus;

interface QueryBus
{
    public function dispatch(object $object): mixed;
}

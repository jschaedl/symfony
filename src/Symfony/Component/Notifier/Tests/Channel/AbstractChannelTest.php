<?php

declare(strict_types=1);

namespace Symfony\Component\Notifier\Tests\Channel;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Notifier\Channel\AbstractChannel;
use Symfony\Component\Notifier\Exception\LogicException;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\Recipient\Recipient;

/**
 * @author Jan Schädlich <jan.schaedlich@sensiolabs.de>
 */
class AbstractChannelTest extends TestCase
{
    public function test_a_channel_cannot_be_constructed_without_transport_and_bus(): void
    {
        $this->expectException(LogicException::class);

        new DummyChannel();
    }
}

class DummyChannel extends AbstractChannel
{
    public function notify(Notification $notification, Recipient $recipient, string $transportName = null): void
    {
        return;
    }

    public function supports(Notification $notification, Recipient $recipient): bool
    {
        return false;
    }
}

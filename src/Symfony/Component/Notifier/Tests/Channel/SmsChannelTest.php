<?php

declare(strict_types=1);

namespace Symfony\Component\Notifier\Tests\Channel;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Notifier\Channel\SmsChannel;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\Recipient\AdminRecipient;
use Symfony\Component\Notifier\Recipient\NoRecipient;
use Symfony\Component\Notifier\Recipient\Recipient;
use Symfony\Component\Notifier\Recipient\SmsRecipientInterface;
use Symfony\Component\Notifier\Transport\TransportInterface;

/**
 * @author Jan Schädlich <jan.schaedlich@sensiolabs.de>
 */
class SmsChannelTest extends TestCase
{
    /**
     * @dataProvider providerRecipients
     */
    public function test_if_channel_is_supported(Recipient $recipient, bool $isSupported): void
    {
        $smsChannel = new SmsChannel(
            $this->createMock(TransportInterface::class),
            $this->createMock(MessageBusInterface::class),
        );

        $this->assertSame(
            $isSupported,
            $smsChannel->supports(new Notification(), $recipient)
        );
    }

    public function providerRecipients(): \Generator
    {
        yield [new NoRecipient(), false];
        yield [new Recipient(), false];
        yield [new SmsRecipient(), true];
        yield [new AdminRecipient('', '+490815'), true];
    }
}

class SmsRecipient extends Recipient implements SmsRecipientInterface
{
    public function phone(string $phone): SmsRecipientInterface
    {
    }

    public function getPhone(): string
    {
        return '+490815';
    }
}

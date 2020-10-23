<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Bundle\FrameworkBundle\Test;

use PHPUnit\Framework\Constraint\LogicalNot;
use Symfony\Component\Notifier\Event\MessageEvent;
use Symfony\Component\Notifier\Event\NotificationEvents;
use Symfony\Component\Notifier\Test\Constraint as NotifierConstraint;

trait NotifierAssertionsTrait
{
    public static function assertNotificationCount(int $count, string $transport = null, string $message = ''): void
    {
        self::assertThat(self::getNotifierNotificationEvents(), new NotifierConstraint\NotificationCount($count, $transport), $message);
    }

    public static function assertQueuedNotificationCount(int $count, string $transport = null, string $message = ''): void
    {
        self::assertThat(self::getNotifierNotificationEvents(), new NotifierConstraint\NotificationCount($count, $transport, true), $message);
    }

    public static function assertNotificationIsQueued(MessageEvent $event, string $message = ''): void
    {
        self::assertThat($event, new NotifierConstraint\NotificationIsQueued(), $message);
    }

    public static function assertNotificationIsNotQueued(MessageEvent $event, string $message = ''): void
    {
        self::assertThat($event, new LogicalNot(new NotifierConstraint\NotificationIsQueued()), $message);
    }

    public static function getNotificationEvent(int $index = 0, string $transport = null): ?MessageEvent
    {
        return self::getNotificationEvents($transport)[$index] ?? null;
    }

    /**
     * @return MessageEvent[]
     */
    public static function getNotificationEvents(string $transport = null): array
    {
        return self::getNotifierNotificationEvents()->getEvents($transport);
    }

    private static function getNotifierNotificationEvents(): NotificationEvents
    {
        if (self::$container->has('notifier.logger_notification_listener')) {
            return self::$container->get('notifier.logger_notification_listener')->getEvents();
        }

        static::fail('A client must have Notifier enabled to make notification assertions. Did you forget to require symfony/notifier?');
    }
}

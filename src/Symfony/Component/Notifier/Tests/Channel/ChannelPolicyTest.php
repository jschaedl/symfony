<?php

declare(strict_types=1);

namespace Symfony\Component\Notifier\Tests\Channel;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Notifier\Channel\ChannelPolicy;
use Symfony\Component\Notifier\Exception\InvalidArgumentException;

/**
 * @author Jan Schädlich <jan.schaedlich@sensiolabs.de>
 */
class ChannelPolicyTest extends TestCase
{
    /**
     * @dataProvider provideInvalidPolicies
     */
    public function test_cannot_be_constructed_with_invalid_policy(array $invalidPolicy): void
    {
        $this->expectException(InvalidArgumentException::class);

        new ChannelPolicy($invalidPolicy);
    }

    public function provideInvalidPolicies(): \Generator
    {
        yield [[]];
        yield [['urgent']];
        yield [['urgent' => []]];
        yield [[0 => []]];
        yield [[0 => ['test']]];
    }

    public function test_cannot_retrieve_channels_using_not_defined_importance(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $channelPolicy = new ChannelPolicy(['urgent' => ['chat']]);
        $channelPolicy->getChannels('low');
    }

    /**
     * @dataProvider provideValidPolicies
     */
    public function test_can_retrieve_channels(array $policy, string $importance, array $expectedChannels): void
    {
        $channelPolicy = new ChannelPolicy($policy);
        $channels = $channelPolicy->getChannels($importance);

        $this->assertSame($expectedChannels, $channels);
    }

    public function provideValidPolicies(): \Generator
    {
        yield [['urgent' => ['chat']], 'urgent', ['chat']];
        yield [['urgent' => ['chat', 'sms']], 'urgent', ['chat', 'sms']];
        yield [['urgent' => ['chat', 'chat/slack', 'sms']], 'urgent', ['chat', 'chat/slack', 'sms']];
    }
}

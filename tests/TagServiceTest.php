<?php

namespace C0ntax\Aws\Ec2\CheckTag\Tests;

use Aws\Ec2\Ec2Client;
use Aws\MockHandler;
use Aws\Result;
use C0ntax\Aws\Ec2\CheckTag\TagService;
use PHPUnit\Framework\TestCase;

class TagServiceTest extends TestCase
{

    /**
     * @param string $instanceId
     * @param Result $result
     * @param array  $expected
     *
     * @dataProvider createGetTagsTestData
     */
    public function testGetTags(string $instanceId, Result $result, array $expected): void
    {
        $mock = new MockHandler();
        $mock->append($result);

        $ec2Client = new Ec2Client([
            'region' => 'eu-west-1',
            'version' => 'latest',
            'handler' => $mock
        ]);

        $tagService = new TagService($ec2Client);
        self::assertSame($expected, $tagService->getTags('blablabla'));
    }

    public function createGetTagsTestData(): array
    {
        return [
            [
                'instanceId' => 'blablabla',
                'result' => new Result(['Tags' => ['This' => 'That', 'Up' => 'Down']]),
                'expected' => ['This' => 'That', 'Up' => 'Down'],
            ],
        ];
    }
}

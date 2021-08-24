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
            'handler' => $mock,
        ]);

        $tagService = new TagService($ec2Client);
        self::assertSame($expected, $tagService->getTags('blablabla'));
    }

    public function createGetTagsTestData(): array
    {
        return [
            [
                'instanceId' => 'i-029969d5aaefbff22',
                'result' => new Result([
                    'Tags' => [
                        [
                            'Key' => 'This',
                            'ResourceId' => 'i-029969d5aaefbff22',
                            'ResourceType' => 'instance',
                            'Value' => 'That',
                        ],
                        [
                            'Key' => 'Up',
                            'ResourceId' => 'i-029969d5aaefbff22',
                            'ResourceType' => 'instance',
                            'Value' => 'Down',
                        ],
                    ],
                ]),
                'expected' => [
                    'This' => 'That',
                    'Up' => 'Down',
                ],
            ],
        ];
    }
}

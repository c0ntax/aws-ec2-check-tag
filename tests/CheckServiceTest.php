<?php

namespace C0ntax\Aws\Ec2\CheckTag\Tests;

use C0ntax\Aws\Ec2\CheckTag\CheckService;
use C0ntax\Aws\Ec2\CheckTag\Exceptions\KeyNotFoundException;
use C0ntax\Aws\Ec2\CheckTag\InstanceService;
use C0ntax\Aws\Ec2\CheckTag\TagService;
use PHPUnit\Framework\TestCase;

/**
 * Class CheckServiceTest
 *
 * @covers \C0ntax\Aws\Ec2\CheckTag\CheckService
 */
class CheckServiceTest extends TestCase
{

    /**
     * @covers \C0ntax\Aws\Ec2\CheckTag\CheckService::check
     */
    public function testCheckKeyNotFound()
    {
        $instanceService = $this->getMockBuilder(InstanceService::class)
            ->disableOriginalConstructor()
            ->getMock();

        $tagService = $this->getMockBuilder(TagService::class)
            ->disableOriginalConstructor()
            ->getMock();

        $id = uniqid('i-', true);

        $instanceService
            ->method('getInstanceId')
            ->willReturn($id);

        $tagService
            ->method('getTags')
            ->with($id)
            ->willReturn([]);

        $checkService = new CheckService($instanceService, $tagService);
        $this->expectException(KeyNotFoundException::class);
        $this->expectExceptionMessage(sprintf('Cannot find the key %s for %s', 'Sausage', $id));
        $checkService->check('Sausage', 'True');
    }

    /**
     * @param string $key
     * @param string $value
     * @param array  $tags
     * @param bool   $expected
     *
     * @dataProvider createTestCheckTestData
     */
    public function testCheck(string $key, string $value, array $tags, bool $expected): void
    {
        $instanceService = $this->getMockBuilder(InstanceService::class)
            ->disableOriginalConstructor()
            ->getMock();

        $tagService = $this->getMockBuilder(TagService::class)
            ->disableOriginalConstructor()
            ->getMock();

        $id = uniqid('i-', true);

        $instanceService
            ->method('getInstanceId')
            ->willReturn($id);

        $tagService
            ->method('getTags')
            ->with($id)
            ->willReturn($tags);


        $checkService = new CheckService($instanceService, $tagService);
        self::assertSame($expected, $checkService->check($key, $value));
    }

    public function createTestCheckTestData(): array
    {
        return [
            [
                'key' => 'A',
                'value' => 'B',
                'tags' => ['A' => 'B'],
                'expected' => true,
            ],
            [
                'key' => 'A',
                'value' => 'B',
                'tags' => ['A' => 'B', 'C' => 'D'],
                'expected' => true,
            ],
            [
                'key' => 'A',
                'value' => 'B',
                'tags' => ['C' => 'D', 'A' => 'B'],
                'expected' => true,
            ],
            [
                'key' => 'A',
                'value' => 'B',
                'tags' => ['A' => null],
                'expected' => false,
            ],
            [
                'key' => 'A',
                'value' => 'B',
                'tags' => ['A' => 'C'],
                'expected' => false,
            ],
        ];
    }
}

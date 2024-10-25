<?php

namespace C0ntax\Aws\Ec2\CheckTag\Tests;

use AssertionError;
use C0ntax\Aws\Ec2\CheckTag\Exceptions\NotOnEc2InstanceException;
use C0ntax\Aws\Ec2\CheckTag\InstanceService;
use PHPUnit\Framework\TestCase;
use Razorpay\EC2Metadata\Ec2MetadataGetter;
use RuntimeException;
use Throwable;
use TypeError;

/**
 * Class InstanceServiceTest
 *
 * @covers \C0ntax\Aws\Ec2\CheckTag\InstanceService
 */
class InstanceServiceTest extends TestCase
{

    /**
     * @covers \C0ntax\Aws\Ec2\CheckTag\InstanceService::getInstanceId
     * @dataProvider createTestGetInstanceIdNotOnEc2Data
     */
    public function testGetInstanceIdNotOnEc2(Throwable $throws, string $expected): void
    {
        $ec2MetadataGetter = $this->getMockBuilder(Ec2MetadataGetter::class)
            ->disableOriginalConstructor()
            ->addMethods(['getInstanceId'])
            ->getMock();
        $ec2MetadataGetter->method('getInstanceId')
            ->willThrowException($throws);

        $instanceService = new InstanceService($ec2MetadataGetter);
        $this->expectException($expected);
        $instanceService->getInstanceId();
    }

    public function createTestGetInstanceIdNotOnEc2Data(): array
    {
        return [
            'RuntimeException' => ['throws' => new RuntimeException(), 'expected' => NotOnEc2InstanceException::class],
            'AssertionError' => ['throws' => new AssertionError(), 'expected' => NotOnEc2InstanceException::class],
            'AnyOtherException' => ['throws' => new TypeError(), 'expected' => TypeError::class],
        ];
    }

    /**
     * @covers \C0ntax\Aws\Ec2\CheckTag\InstanceService::getInstanceId
     */
    public function testGetInstanceId(): void
    {
        $ec2MetadataGetter = $this->getMockBuilder(Ec2MetadataGetter::class)
            ->disableOriginalConstructor()
            ->addMethods(['getInstanceId'])
            ->getMock();

        $id = uniqid('i-', true);

        $ec2MetadataGetter->method('getInstanceId')
            ->willReturn($id);

        $instanceService = new InstanceService($ec2MetadataGetter);
        self::assertSame($id, $instanceService->getInstanceId());
    }
}

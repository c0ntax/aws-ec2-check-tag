<?php

namespace C0ntax\Aws\Ec2\CheckTag\Tests;

use C0ntax\Aws\Ec2\CheckTag\Exceptions\NotOnEc2InstanceException;
use C0ntax\Aws\Ec2\CheckTag\InstanceService;
use PHPUnit\Framework\TestCase;
use Razorpay\EC2Metadata\Ec2MetadataGetter;

/**
 * Class InstanceServiceTest
 *
 * @covers \C0ntax\Aws\Ec2\CheckTag\InstanceService
 */
class InstanceServiceTest extends TestCase
{

    /**
     * @covers \C0ntax\Aws\Ec2\CheckTag\InstanceService::getInstanceId
     */
    public function testGetInstanceIdNotOnEc2(): void
    {
        $ec2MetadataGetter = $this->getMockBuilder(Ec2MetadataGetter::class)
            ->disableOriginalConstructor()
            ->addMethods(['getInstanceId'])
            ->getMock();
        $ec2MetadataGetter->method('getInstanceId')
            ->willThrowException(new NotOnEc2InstanceException());

        $instanceService = new InstanceService($ec2MetadataGetter);
        $this->expectException(NotOnEc2InstanceException::class);
        $instanceService->getInstanceId();
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

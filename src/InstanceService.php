<?php
declare(strict_types=1);

namespace C0ntax\Aws\Ec2\CheckTag;

use AssertionError;
use C0ntax\Aws\Ec2\CheckTag\Exceptions\NotOnEc2InstanceException;
use Razorpay\EC2Metadata\Ec2MetadataGetter;
use RuntimeException;

class InstanceService
{
    private Ec2MetadataGetter $ec2MetadataGetter;

    /**
     * @param Ec2MetadataGetter $ec2MetadataGetter
     */
    public function __construct(Ec2MetadataGetter $ec2MetadataGetter)
    {
        $this->setEc2MetadataGetter($ec2MetadataGetter);
    }

    /**
     * @throws NotOnEc2InstanceException
     */
    public function getInstanceId(): string
    {
        try {
            return $this->getEc2MetadataGetter()->getInstanceId();
        } catch (RuntimeException | AssertionError $exception) {
            throw new NotOnEc2InstanceException($exception->getMessage(), $exception->getCode(), $exception);
        }
    }

    /**
     * @return Ec2MetadataGetter
     */
    private function getEc2MetadataGetter(): Ec2MetadataGetter
    {
        return $this->ec2MetadataGetter;
    }

    /**
     * @param Ec2MetadataGetter $ec2MetadataGetter
     */
    private function setEc2MetadataGetter(Ec2MetadataGetter $ec2MetadataGetter): void
    {
        $this->ec2MetadataGetter = $ec2MetadataGetter;
    }
}

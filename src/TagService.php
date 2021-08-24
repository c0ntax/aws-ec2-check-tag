<?php
declare(strict_types = 1);

namespace C0ntax\Aws\Ec2\CheckTag;

use Aws\Ec2\Ec2Client;

class TagService
{
    private Ec2Client $ec2Client;

    /**
     * @param Ec2Client $ec2Client
     */
    public function __construct(Ec2Client $ec2Client)
    {
        $this->setEc2Client($ec2Client);
    }

    public function getTags(string $instanceId): array
    {
        $result = $this->getEc2Client()->describeTags([
            'Filters' => [
                [
                    'Name' => 'resource-id',
                    'Values' => [
                        $instanceId,
                    ],
                ],
            ],
        ]);

        $data = $result->toArray();

        $out = [];
        foreach ($data['Tags'] as $tagArray) {
            $out[$tagArray['Key']] = $tagArray['Value'];
        }

        return $out;
    }

    /**
     * @return Ec2Client
     */
    private function getEc2Client(): Ec2Client
    {
        return $this->ec2Client;
    }

    /**
     * @param Ec2Client $ec2Client
     */
    private function setEc2Client(Ec2Client $ec2Client): void
    {
        $this->ec2Client = $ec2Client;
    }
}

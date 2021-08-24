<?php
declare(strict_types=1);

namespace C0ntax\Aws\Ec2\CheckTag;

use C0ntax\Aws\Ec2\CheckTag\Exceptions\KeyNotFoundException;
use C0ntax\Aws\Ec2\CheckTag\Exceptions\NotOnEc2InstanceException;

class CheckService
{
    private InstanceService $instanceService;

    private TagService $tagService;

    /**
     * @param InstanceService $instanceService
     * @param TagService      $tagService
     */
    public function __construct(InstanceService $instanceService, TagService $tagService)
    {
        $this->setInstanceService($instanceService);
        $this->setTagService($tagService);
    }

    /**
     * Check to see if a particular tag has been set to a specific value
     *
     * @param string $key
     * @param string $value
     *
     * @return bool
     * @throws NotOnEc2InstanceException
     * @throws KeyNotFoundException
     */
    public function check(string $key, string $value): bool
    {
        $instanceId = $this->getInstanceService()->getInstanceId();
        $tags = $this->getTagService()->getTags($instanceId);
        if (!array_key_exists($key, $tags)) {
            throw new KeyNotFoundException(sprintf('Cannot find the key %s for %s', $key, $instanceId));
        }

        return $tags[$key] === $value;
    }

    /**
     * @return InstanceService
     */
    private function getInstanceService(): InstanceService
    {
        return $this->instanceService;
    }

    /**
     * @param InstanceService $instanceService
     */
    private function setInstanceService(InstanceService $instanceService): void
    {
        $this->instanceService = $instanceService;
    }

    /**
     * @return TagService
     */
    private function getTagService(): TagService
    {
        return $this->tagService;
    }

    /**
     * @param TagService $tagService
     */
    private function setTagService(TagService $tagService): void
    {
        $this->tagService = $tagService;
    }
}

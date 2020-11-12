<?php

namespace ComponentBundle\Entity\RemoteUrl;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @author Design studio origami <https://origami.ua>
 */
trait RemoteUrlTrait
{
    /**
     * @var string
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="remote_url", type="text", nullable=true)
     */
    private $remoteUrl;

    /**
     * @return null|string
     */
    public function getRemoteUrl(): ?string
    {
        return $this->remoteUrl;
    }

    /**
     * @param null|string $remoteUrl
     */
    public function setRemoteUrl(?string $remoteUrl): void
    {
        $this->remoteUrl = $remoteUrl;
    }
}

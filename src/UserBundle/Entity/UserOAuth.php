<?php

namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use ComponentBundle\Entity\Id\IdTrait;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * UserOAuth
 *
 * @Gedmo\Loggable
 * @ORM\Entity
 * @ORM\Table(name="`user_o_auth_table`")
 * @author Design studio origami <https://origami.ua>
 */
class UserOAuth implements UserOAuthInterface
{
    use IdTrait;

    /**
     * @var string
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="provider", type="string", length=255)
     */
    private $provider;

    /**
     * @var string
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="identifier", type="string", length=255)
     */
    private $identifier;

    /**
     * @var string
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="accessToken", type="string", length=255)
     */
    private $accessToken;

    /**
     * @var string
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="refreshToken", type="string", length=255)
     */
    private $refreshToken;

    /**
     * @var \UserBundle\Entity\User
     *
     * @Gedmo\Versioned
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User", inversedBy="oauthAccounts")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user", referencedColumnName="id")
     * })
     */
    private $user;

    /**
     * {@inheritdoc}
     */
    public function getProvider(): ?string
    {
        return $this->provider;
    }

    /**
     * {@inheritdoc}
     */
    public function setProvider(?string $provider): void
    {
        $this->provider = $provider;
    }

    /**
     * {@inheritdoc}
     */
    public function getIdentifier(): ?string
    {
        return $this->identifier;
    }

    /**
     * {@inheritdoc}
     */
    public function setIdentifier(?string $identifier): void
    {
        $this->identifier = $identifier;
    }

    /**
     * {@inheritdoc}
     */
    public function getAccessToken(): ?string
    {
        return $this->accessToken;
    }

    /**
     * {@inheritdoc}
     */
    public function setAccessToken(?string $accessToken): void
    {
        $this->accessToken = $accessToken;
    }

    /**
     * {@inheritdoc}
     */
    public function getUser(): ?UserInterface
    {
        return $this->user;
    }

    /**
     * {@inheritdoc}
     */
    public function setUser(?UserInterface $user): void
    {
        $this->user = $user;
    }

    /**
     * {@inheritdoc}
     */
    public function getRefreshToken(): ?string
    {
        return $this->refreshToken;
    }

    /**
     * {@inheritdoc}
     */
    public function setRefreshToken(?string $refreshToken): void
    {
        $this->refreshToken = $refreshToken;
    }
}

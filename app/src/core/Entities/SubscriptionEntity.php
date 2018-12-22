<?php

namespace Core\Entities;

/**
 * Class SubscriptionEntity.
 *
 * @package Core\Entities
 */
final class SubscriptionEntity extends AbstractEntity
{
    private $id;
    private $email;
    private $token;

    /**
     * SubscriptionEntity constructor.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes)
    {
        $this->setId($attributes['id'] ?? null);
        $this->setEmail($attributes['email'] ?? null);
        $this->setToken($attributes['token'] ?? null);
    }

    /**
     * @param int $id
     * @return $this
     */
    private function setId(int $id): SubscriptionEntity
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param string $email
     * @return $this
     */
    private function setEmail(string $email): SubscriptionEntity
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $token
     * @return $this
     */
    private function setToken(string $token): SubscriptionEntity
    {
        $this->token = $token;

        return $this;
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @inheritdoc
     */
    public function __toString(): string
    {
        return $this->getToken();
    }

    /**
     * @inheritdoc
     */
    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'email' => $this->getEmail(),
            'token' => $this->getToken(),
        ];
    }
}

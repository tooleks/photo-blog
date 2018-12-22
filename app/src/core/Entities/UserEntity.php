<?php

namespace Core\Entities;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use InvalidArgumentException;

/**
 * Class UserEntity.
 *
 * @package Core\Entities
 */
final class UserEntity extends AbstractEntity
{
    public const ROLE_ADMINISTRATOR = 'Administrator';
    public const ROLE_CUSTOMER = 'Customer';

    private $id;
    private $name;
    private $email;
    private $passwordHash;
    private $role;
    private $createdAt;
    private $updatedAt;

    /**
     * UserEntity constructor.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes)
    {
        $this->setId($attributes['id'] ?? null);
        $this->setName($attributes['name'] ?? null);
        $this->setEmail($attributes['email'] ?? null);
        $this->setPasswordHash($attributes['password_hash'] ?? null);
        $this->setRole($attributes['role'] ?? null);
        $this->setCreatedAt(isset($attributes['created_at']) ? new Carbon($attributes['created_at']) : null);
        $this->setUpdatedAt(isset($attributes['updated_at']) ? new Carbon($attributes['updated_at']) : null);
    }

    /**
     * @return Collection
     */
    public static function getRoles(): Collection
    {
        return collect([static::ROLE_ADMINISTRATOR, static::ROLE_CUSTOMER]);
    }

    /**
     * @param int $id
     * @return $this
     */
    private function setId(int $id): UserEntity
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
     * @param string $name
     * @return $this
     */
    private function setName(string $name): UserEntity
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $email
     * @return $this
     */
    private function setEmail(string $email): UserEntity
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
     * @param string $passwordHash
     * @return $this
     */
    private function setPasswordHash(string $passwordHash): UserEntity
    {
        $this->passwordHash = $passwordHash;

        return $this;
    }

    /**
     * @return string
     */
    public function getPasswordHash(): string
    {
        return $this->passwordHash;
    }

    /**
     * @param string $role
     * @return $this
     */
    private function setRole(string $role): UserEntity
    {
        if (!static::getRoles()->contains($role)) {
            throw new InvalidArgumentException('Invalid role value.');
        }

        $this->role = $role;

        return $this;
    }

    /**
     * @return string
     */
    public function getRole(): string
    {
        return $this->role;
    }

    /**
     * @param Carbon $createdAt
     * @return $this
     */
    private function setCreatedAt(Carbon $createdAt): UserEntity
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return Carbon
     */
    public function getCreatedAt(): Carbon
    {
        return $this->createdAt;
    }

    /**
     * @param Carbon $updatedAt
     * @return $this
     */
    private function setUpdatedAt(Carbon $updatedAt): UserEntity
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Carbon
     */
    public function getUpdatedAt(): Carbon
    {
        return $this->updatedAt;
    }

    /**
     * @return bool
     */
    public function isAdministrator(): bool
    {
        return $this->getRole() === static::ROLE_ADMINISTRATOR;
    }

    /**
     * @return bool
     */
    public function isCustomer(): bool
    {
        return $this->getRole() === static::ROLE_CUSTOMER;
    }

    /**
     * @inheritdoc
     */
    public function __toString(): string
    {
        return $this->getEmail();
    }

    /**
     * @inheritdoc
     */
    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'email' => $this->getEmail(),
            'role' => $this->getRole(),
            'created_at' => $this->getCreatedAt()->toAtomString(),
            'updated_at' => $this->getUpdatedAt()->toAtomString(),
        ];
    }
}

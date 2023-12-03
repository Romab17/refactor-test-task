<?php

declare(strict_types=1);

namespace App\Models\Traits;

use DateTimeImmutable;

trait CreatedUpdatedTrait
{
    private DateTimeImmutable $createdAt;

    private DateTimeImmutable $updatedAt;

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(): void
    {
        $this->updatedAt = new DateTimeImmutable();
    }

    public function setCreatedAt(): void
    {
        $this->createdAt = new DateTimeImmutable();
        $this->updatedAt = new DateTimeImmutable();
    }

    public function setCreatedAtManual(DateTimeImmutable $createdAt): void
    {
        $this->createdAt = $createdAt;
    }
}

<?php

namespace AppBundle\Entity\Traits;

trait TimestampableTrait
{
    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @var \DateTime
     */
    private $deletedAt;

    /**
    * Triggered on insert
    * @ORM\PrePersist
    */
    public function onPrePersist(): void
    {
        $this->createdAt = new \DateTime("now");
    }

    /**
     * Triggered on update
     * @ORM\PreUpdate
     */
    public function onPreUpdate(): void
    {
        $this->updatedAt = new \DateTime("now");
    }

    /**
     * Please note, the deleted timestamp assumes your database is retaining the data in a “deleted” state,
     * using triggers or other such database functionality to handle Doctrine’s instruction to delete the row.
     * If you are not using this, either ignore the code or remove it.
     *
    /**

     /**
     * Triggered on update
     * @ORM\PreRemove
     */
    public function onPreRemove(): void
    {
        $this->deletedAt = new \DateTime("now");
    }

    public function getDeletedAt(): \DateTime
    {
        return $this->deletedAt;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }
}

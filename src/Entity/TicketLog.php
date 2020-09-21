<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TicketLog
 *
 * @ORM\Table(name="ticket_log", indexes={@ORM\Index(name="ticket_id", columns={"ticket_id"}), @ORM\Index(name="updated_by", columns={"updated_by"})})
 * @ORM\Entity(repositoryClass="App\Repository\TicketLogReposiroty")
 * @ORM\Entity
 */
class TicketLog
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=false)
     */
    private $updatedAt;

    /**
     * @var string
     *
     * @ORM\Column(name="changed_value", type="string", length=50, nullable=false)
     */
    private $changedValue;

    /**
     * @var string
     *
     * @ORM\Column(name="old_value", type="text", length=0, nullable=false)
     */
    private $oldValue;

    /**
     * @var string
     *
     * @ORM\Column(name="new_value", type="text", length=0, nullable=false)
     */
    private $newValue;

    /**
     * @var \Ticket
     *
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="Ticket")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ticket_id", referencedColumnName="id")
     * })
     */
    private $ticket;

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="updated_by", referencedColumnName="id")
     * })
     */
    private $updatedBy;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getChangedValue(): ?string
    {
        return $this->changedValue;
    }

    public function setChangedValue(string $changedValue): self
    {
        $this->changedValue = $changedValue;

        return $this;
    }

    public function getOldValue(): ?string
    {
        return $this->oldValue;
    }

    public function setOldValue(string $oldValue): self
    {
        $this->oldValue = $oldValue;

        return $this;
    }

    public function getNewValue(): ?string
    {
        return $this->newValue;
    }

    public function setNewValue(string $newValue): self
    {
        $this->newValue = $newValue;

        return $this;
    }

    public function getTicket(): ?Ticket
    {
        return $this->ticket;
    }

    public function setTicket(?Ticket $ticket): self
    {
        $this->ticket = $ticket;

        return $this;
    }

    public function getUpdatedBy(): ?User
    {
        return $this->updatedBy;
    }

    public function setUpdatedBy(?User $updatedBy): self
    {
        $this->updatedBy = $updatedBy;

        return $this;
    }


}

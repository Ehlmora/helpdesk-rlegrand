<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Ticket
 *
 * @ORM\Table(name="ticket", indexes={@ORM\Index(name="user_id", columns={"user_id"}), @ORM\Index(name="ticket_state_id", columns={"ticket_state_id"})})
 * @ORM\Entity
 */
class Ticket
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=60, nullable=false)
     */
    public $title;

    /**
     * @var int
     *
     * @ORM\Column(name="priority", type="smallint", nullable=false, options={"default"="1"})
     */
    private $priority = '1';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_start", type="datetime", nullable=false)
     */
    public $dateStart;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="date_end", type="datetime", nullable=true, options={"default"="NULL"})
     */
    public $dateEnd = NULL;

    /**
     * @var string|null
     *
     * @ORM\Column(name="description", type="text", length=0, nullable=true, options={"default"="NULL"})
     */
    public $description = NULL;

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    public $user;

    /**
     * @var \TicketState
     *
     * @ORM\ManyToOne(targetEntity="TicketState")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ticket_state_id", referencedColumnName="id")
     * })
     */
    public $ticketState;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getPriority(): ?int
    {
        return $this->priority;
    }

    public function setPriority(int $priority): self
    {
        $this->priority = $priority;

        return $this;
    }

    public function getDateStart(): ?\DateTimeInterface
    {
        return $this->dateStart;
    }

    public function setDateStart(\DateTimeInterface $dateStart): self
    {
        $this->dateStart = $dateStart;

        return $this;
    }

    public function getDateEnd(): ?\DateTimeInterface
    {
        return $this->dateEnd;
    }

    public function setDateEnd(?\DateTimeInterface $dateEnd): self
    {
        $this->dateEnd = $dateEnd;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getTicketState(): ?TicketState
    {
        return $this->ticketState;
    }

    public function setTicketState(?TicketState $ticketState): self
    {
        $this->ticketState = $ticketState;

        return $this;
    }


}


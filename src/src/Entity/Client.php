<?php

declare(strict_types=1);

namespace App\Entity;

use App\Enum\EducationType;
use App\Repository\ClientRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ClientRepository::class)]
#[UniqueEntity('email', message: 'client.email.unique')]
class Client
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer', unique: true)]
    private int $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(message: 'client.firstname.not_blank')]
    private string $firstname;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(message: 'client.lastname.not_blank')]
    private string $lastname;

    #[ORM\Column(type: 'string', length: 11)]
    #[Assert\Regex(pattern: '/^7\d{10}$/', message: 'client.phone.format')]
    #[Assert\NotBlank(message: 'client.phone.not_blank')]
    private string $phone;

    #[ORM\Column(type: 'string', length: 255, unique: true)]
    #[Assert\NotBlank(message: 'client.email.not_blank')]
    #[Assert\Email(message: 'client.email.email')]
    private string $email;

    #[ORM\Column(type: 'string', enumType: EducationType::class)]
    #[Assert\NotBlank(message: 'client.education.not_blank')]
    private EducationType $education;

    #[ORM\Column(type: 'boolean', options: ['default' => '0'])]
    private bool $consent = false;

    #[ORM\OneToOne(mappedBy: 'client', targetEntity: ClientScore::class, cascade: ['persist', 'remove'])]
    private $score;

    public function __toString(): string
    {
        return sprintf('%s', $this->email);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getEducation(): ?EducationType
    {
        return $this->education;
    }

    public function setEducation(EducationType $education): self
    {
        $this->education = $education;

        return $this;
    }

    public function getConsent(): ?bool
    {
        return $this->consent;
    }

    public function setConsent(bool $consent): self
    {
        $this->consent = $consent;

        return $this;
    }

    public function getScore(): ?ClientScore
    {
        return $this->score;
    }

    public function setScore(ClientScore $score): self
    {
        if ($score->getClient() !== $this) {
            $score->setClient($this);
        }

        $this->score = $score;

        return $this;
    }

    public function scoring(int $score): void
    {
        $clientScore = $this->getScore();
        if (!$clientScore) {
            $clientScore = new ClientScore();
            $this->setScore($clientScore);
        }
        $clientScore->setScore($score);
    }
}

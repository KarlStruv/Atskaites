<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\KpdcRegistrsIndRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: KpdcRegistrsIndRepository::class)]
class KpdcRegistrsInd
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: KpdcRegistrs::class, inversedBy: 'indTpGalvaId')]
    #[ORM\JoinColumn(name: 'tp_galva_id', referencedColumnName: 'id', nullable: true)]
    private ?KpdcRegistrs $registrs = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $datumsUzm = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRegistrs(): ?KpdcRegistrs
    {
        return $this->registrs;
    }

    public function getDatumsUzm(): ?\DateTime
    {
        return $this->datumsUzm;
    }

    public function setDatumsUzm(?\DateTime $datumsUzm): static
    {
        $this->datumsUzm = $datumsUzm;

        return $this;
    }
}

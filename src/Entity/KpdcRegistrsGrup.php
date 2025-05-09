<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\KpdcRegistrsGrupRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: KpdcRegistrsGrupRepository::class)]
class KpdcRegistrsGrup
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: KpdcRegistrs::class, inversedBy: 'grupTpGalvaId')]
    #[ORM\JoinColumn(name: 'tp_galva_id', referencedColumnName: 'id', nullable: true)]
    private ?KpdcRegistrs $registrs = null;

    #[ORM\Column(type: Types::BIGINT, nullable: true)]
    private ?string $sortiments = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 7, scale: 3, nullable: true)]
    private ?string $tilpums_bruto = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 7, scale: 3, nullable: true)]
    private ?string $tilpums_neto = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 7, scale: 3, nullable: true)]
    private ?string $tilpums_brakis = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRegistrs(): ?KpdcRegistrs
    {
        return $this->registrs;
    }

    public function getSortiments(): ?string
    {
        return $this->sortiments;
    }

    public function setSortiments(?string $sortiments): static
    {
        $this->sortiments = $sortiments;

        return $this;
    }

    public function getTilpumsBruto(): ?string
    {
        return $this->tilpums_bruto;
    }

    public function setTilpumsBruto(?string $tilpums_bruto): static
    {
        $this->tilpums_bruto = $tilpums_bruto;

        return $this;
    }

    public function getTilpumsNeto(): ?string
    {
        return $this->tilpums_neto;
    }

    public function setTilpumsNeto(?string $tilpums_neto): static
    {
        $this->tilpums_neto = $tilpums_neto;

        return $this;
    }

    public function getTilpumsBrakis(): ?string
    {
        return $this->tilpums_brakis;
    }

    public function setTilpumsBrakis(?string $tilpums_brakis): static
    {
        $this->tilpums_brakis = $tilpums_brakis;

        return $this;
    }
}

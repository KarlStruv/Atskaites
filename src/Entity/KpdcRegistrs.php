<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\KpdcRegistrsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: KpdcRegistrsRepository::class)]
class KpdcRegistrs
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 11)]
    private ?string $uzm_tips_vmf = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 7, scale: 3, nullable: true)]
    private ?string $tilp_bruto = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 7, scale: 3, nullable: true)]
    private ?string $tilp_neto = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 7, scale: 3, nullable: true)]
    private ?string $tilp_brakis = null;

    #[ORM\OneToMany(targetEntity: KpdcRegistrsGrup::class, mappedBy: 'registrs')]
    private Collection $grupTpGalvaId;

    #[ORM\OneToMany(targetEntity: KpdcRegistrsInd::class, mappedBy: 'registrs')]
    private Collection $kpdcRegistrsInd;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $vietaNosaukums = null;

    public function __construct()
    {
        $this->grupTpGalvaId = new ArrayCollection();
        $this->kpdcRegistrsInd = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUzmTipsVmf(): ?string
    {
        return $this->uzm_tips_vmf;
    }

    public function setUzmTipsVmf(string $uzm_tips_vmf): static
    {
        $this->uzm_tips_vmf = $uzm_tips_vmf;
        return $this;
    }

    public function getTilpBruto(): ?string
    {
        return $this->tilp_bruto;
    }

    public function setTilpBruto(?string $tilp_bruto): static
    {
        $this->tilp_bruto = $tilp_bruto;
        return $this;
    }

    public function getTilpNeto(): ?string
    {
        return $this->tilp_neto;
    }

    public function setTilpNeto(?string $tilp_neto): static
    {
        $this->tilp_neto = $tilp_neto;
        return $this;
    }

    public function getTilpBrakis(): ?string
    {
        return $this->tilp_brakis;
    }

    public function setTilpBrakis(?string $tilp_brakis): static
    {
        $this->tilp_brakis = $tilp_brakis;
        return $this;
    }

    /**
     * @return Collection<int, KpdcRegistrsGrup>
     */
    public function getGrupTpGalvaId(): Collection
    {
        return $this->grupTpGalvaId;
    }

    /**
     * @return Collection<int, KpdcRegistrsInd>
     */
    public function getKpdcRegistrsInd(): Collection
    {
        return $this->kpdcRegistrsInd;
    }

    public function getVietaNosaukums(): ?string
    {
        return $this->vietaNosaukums;
    }

    public function setVietaNosaukums(?string $vietaNosaukums): static
    {
        $this->vietaNosaukums = $vietaNosaukums;

        return $this;
    }
}

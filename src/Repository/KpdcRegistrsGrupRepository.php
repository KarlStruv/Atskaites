<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

declare(strict_types=1);

namespace App\Repository;

use App\Entity\KpdcRegistrsGrup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<KpdcRegistrsGrup>
 */
class KpdcRegistrsGrupRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, KpdcRegistrsGrup::class);
    }

    public function getTilpPaSortimentiem(): array
    {
        return $this->createQueryBuilder('krg')
            ->select('krg.sortiments AS sortiments')
            ->addSelect('SUM(krg.tilpums_bruto) AS bruto')
            ->addSelect('SUM(krg.tilpums_neto) AS neto')
            ->addSelect('SUM(krg.tilpums_brakis) AS brakis')
            ->groupBy('sortiments')
            ->orderBy('sortiments', 'ASC')
            ->getQuery()
            ->getArrayResult();
    }
}

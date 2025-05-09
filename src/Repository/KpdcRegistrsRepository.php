<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

declare(strict_types=1);

namespace App\Repository;

use App\Entity\KpdcRegistrs;
use Carbon\Carbon;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

class KpdcRegistrsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, KpdcRegistrs::class);
    }

    private function createFilteredQueryBuilder(string $searchValue, array $filters): QueryBuilder
    {
        $qb = $this->createQueryBuilder('k');

        if ($searchValue) {
            $qb->andWhere('k.uzm_tips_vmf LIKE :search')
                ->setParameter('search', '%' . $searchValue . '%');
        }

        $filterConfig = [
            'uzm_tips_vmf' => ['field' => 'k.uzm_tips_vmf', 'operator' => '=', 'param' => 'uzm_tips_vmf'],
            'tilp_bruto_min' => ['field' => 'k.tilp_bruto', 'operator' => '>=', 'param' => 'tilp_bruto_min'],
            'tilp_bruto_max' => ['field' => 'k.tilp_bruto', 'operator' => '<=', 'param' => 'tilp_bruto_max'],
            'tilp_neto_min' => ['field' => 'k.tilp_neto', 'operator' => '>=', 'param' => 'tilp_neto_min'],
            'tilp_neto_max' => ['field' => 'k.tilp_neto', 'operator' => '<=', 'param' => 'tilp_neto_max'],
            'tilp_brakis_min' => ['field' => 'k.tilp_brakis', 'operator' => '>=', 'param' => 'tilp_brakis_min'],
            'tilp_brakis_max' => ['field' => 'k.tilp_brakis', 'operator' => '<=', 'param' => 'tilp_brakis_max'],
        ];

        foreach ($filterConfig as $key => $config) {
            if (!empty($filters[$key])) {
                $qb->andWhere(sprintf('%s %s :%s', $config['field'], $config['operator'], $config['param']))
                    ->setParameter($config['param'], $filters[$key]);
            }
        }

        return $qb;
    }

    public function getFilteredData(?int $start, ?int $length, string $orderColumn, string $orderDirection, string $searchValue, array $filters): array
    {
        return $this->createFilteredQueryBuilder($searchValue, $filters)
            ->orderBy('k.' . $orderColumn, $orderDirection)
            ->setFirstResult($start ?? 0)
            ->setMaxResults($length)
            ->getQuery()
            ->getArrayResult();
    }

    public function countTotalRecords(): int
    {
        return (int)$this->createQueryBuilder('k')
            ->select('COUNT(k.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function countFilteredRecords(string $searchValue, array $filters): int
    {
        return (int)$this->createFilteredQueryBuilder($searchValue, $filters)
            ->select('COUNT(k.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function getGroupedDataByType(): array
    {
        return $this->createQueryBuilder('k')
            ->select('k.uzm_tips_vmf AS veids')
            ->addSelect('SUM(k.tilp_bruto) AS bruto')
            ->addSelect('SUM(k.tilp_neto) AS neto')
            ->addSelect('SUM(k.tilp_brakis) AS brakis')
            ->groupBy('veids')
            ->orderBy('veids', 'ASC')
            ->getQuery()
            ->getArrayResult();
    }

    public function getLocationDataWithFilters(?string $startDate = null, ?string $endDate = null): array
    {
        $qb = $this->createQueryBuilder('k')
            ->select('kri.datumsUzm AS datums', 'k.vietaNosaukums AS vieta', 'COUNT(kri.id) AS skaits')
            ->innerJoin('k.kpdcRegistrsInd', 'kri', 'WITH', 'kri.registrs = k.id')
            ->groupBy('datums', 'vieta')
            ->orderBy('datums', 'ASC')
            ->addOrderBy('vieta', 'ASC');

        if ($startDate) {
            $qb->andWhere('datums >= :startDate')
                ->setParameter('startDate', $startDate);
        }

        if ($endDate) {
            $qb->andWhere('datums <= :endDate')
                ->setParameter('endDate', $endDate);
        }

        $results = $qb->getQuery()->getArrayResult();

        $groupedResults = [];
        foreach ($results as $result) {
            $date = Carbon::parse($result['datums'])->toDateString();
            $vieta = $result['vieta'];
            $key = "$date|$vieta";

            $groupedResults[$key] ??= [
                'datums' => $date,
                'vieta' => $vieta,
                'skaits' => 0,
            ];
            $groupedResults[$key]['skaits'] += (int)$result['skaits'];
        }

        $finalResults = array_values($groupedResults);
        usort($finalResults, fn($a, $b) => ($a['datums'] <=> $b['datums']) ?: ($a['vieta'] <=> $b['vieta']));

        return $finalResults;
    }
}

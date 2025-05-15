<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\DTO\FilterParamsDTO;
use App\Repository\KpdcRegistrsGrupRepository;
use App\Repository\KpdcRegistrsRepository;
use App\Service\KpdcRegistrsService;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;

class KpdcRegistrsServiceTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testGetFilteredDataReturnsExpectedStructure(): void
    {
        $mockRepository = $this->createMock(KpdcRegistrsRepository::class);
        $mockRepository->method('getFilteredData')->willReturn([['id' => 1]]);
        $mockRepository->method('countTotalRecords')->willReturn(10);
        $mockRepository->method('countFilteredRecords')->willReturn(5);

        $mockGrupRepo = $this->createMock(KpdcRegistrsGrupRepository::class);
        $service = new KpdcRegistrsService($mockRepository, $mockGrupRepo);

        $dto = new FilterParamsDTO(0, 10, 1, 'id', 'asc', '', []);
        $result = $service->getFilteredData($dto);

        $this->assertSame(10, $result['recordsTotal']);
        $this->assertSame(5, $result['recordsFiltered']);
        $this->assertSame([['id' => 1]], $result['data']);
    }

    /**
     * @throws Exception
     */
    public function testGetGroupedDataByTypeReturnsStructuredResult(): void
    {
        $mockRepository = $this->createMock(KpdcRegistrsRepository::class);
        $mockRepository->method('getGroupedDataByType')->willReturn([['type' => 'ABC']]);
        $mockGrupRepo = $this->createMock(KpdcRegistrsGrupRepository::class);

        $service = new KpdcRegistrsService($mockRepository, $mockGrupRepo);
        $result = $service->getGroupedDataByType(1);

        $this->assertSame(1, $result['draw']);
        $this->assertSame(1, $result['recordsTotal']);
        $this->assertSame([['type' => 'ABC']], $result['data']);
    }

    /**
     * @throws Exception
     */
    public function testGetTilpPaSortimentiemReturnsStructuredResult(): void
    {
        $mockRepository = $this->createMock(KpdcRegistrsRepository::class);
        $mockGrupRepo = $this->createMock(KpdcRegistrsGrupRepository::class);
        $mockGrupRepo->method('getTilpPaSortimentiem')->willReturn([['sort' => 'X']]);

        $service = new KpdcRegistrsService($mockRepository, $mockGrupRepo);
        $result = $service->getTilpPaSortimentiem(1);

        $this->assertSame(1, $result['draw']);
        $this->assertSame(1, $result['recordsTotal']);
        $this->assertSame([['sort' => 'X']], $result['data']);
    }

    /**
     * @throws Exception
     */
    public function testGroupedLocationDataGroupsAndSumsCorrectly(): void
    {
        $mockRepository = $this->createMock(KpdcRegistrsRepository::class);
        $mockRepository->method('getLocationDataWithFilters')->willReturn([
            ['datums' => new \DateTime('2023-01-16 08:00:00'), 'vieta' => 'Rīga', 'skaits' => 1],
            ['datums' => new \DateTime('2023-01-16 15:00:00'), 'vieta' => 'Rīga', 'skaits' => 2],
            ['datums' => new \DateTime('2023-01-17 09:00:00'), 'vieta' => 'Liepāja', 'skaits' => 5],
        ]);

        $mockGrupRepo = $this->createMock(KpdcRegistrsGrupRepository::class);
        $service = new KpdcRegistrsService($mockRepository, $mockGrupRepo);

        $result = $service->getGroupedLocationData();

        $this->assertCount(2, $result);
        $this->assertEquals('2023-01-16', $result[0]['datums']);
        $this->assertEquals('Rīga', $result[0]['vieta']);
        $this->assertEquals(3, $result[0]['skaits']);
        $this->assertEquals('2023-01-17', $result[1]['datums']);
        $this->assertEquals('Liepāja', $result[1]['vieta']);
        $this->assertEquals(5, $result[1]['skaits']);
    }

    /**
     * @throws Exception
     */
    public function testGroupedLocationDataHandlesEmptyResults(): void
    {
        $mockRepository = $this->createMock(KpdcRegistrsRepository::class);
        $mockRepository->method('getLocationDataWithFilters')->willReturn([]);
        $mockGrupRepo = $this->createMock(KpdcRegistrsGrupRepository::class);
        $service = new KpdcRegistrsService($mockRepository, $mockGrupRepo);
        $result = $service->getGroupedLocationData();

        $this->assertIsArray($result);
        $this->assertCount(0, $result);
    }

    /**
     * @throws Exception
     */
    public function testGroupedLocationDataRespectsDateFiltering(): void
    {
        $mockRepository = $this->createMock(KpdcRegistrsRepository::class);
        $mockRepository->expects($this->once())
            ->method('getLocationDataWithFilters')
            ->with('2023-01-01', '2023-01-31')
            ->willReturn([
                ['datums' => new \DateTime('2023-01-05 12:00:00'), 'vieta' => 'Valmiera', 'skaits' => 4],
            ]);

        $mockGrupRepo = $this->createMock(KpdcRegistrsGrupRepository::class);
        $service = new KpdcRegistrsService($mockRepository, $mockGrupRepo);

        $result = $service->getGroupedLocationData('2023-01-01', '2023-01-31');

        $this->assertCount(1, $result);
        $this->assertEquals('2023-01-05', $result[0]['datums']);
        $this->assertEquals('Valmiera', $result[0]['vieta']);
        $this->assertEquals(4, $result[0]['skaits']);
    }
}

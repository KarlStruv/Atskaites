<?php

declare(strict_types=1);

namespace App\Service;

use App\DTO\FilterParamsDTO;
use App\Repository\KpdcRegistrsGrupRepository;
use App\Repository\KpdcRegistrsRepository;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

final readonly class KpdcRegistrsService
{
    public function __construct(
        private KpdcRegistrsRepository     $registrsRepository,
        private KpdcRegistrsGrupRepository $grupRepository
    )
    {
    }

    public function getFilteredData(FilterParamsDTO $params): array
    {
        $data = $this->registrsRepository->getFilteredData(
            $params->start,
            $params->length,
            $params->orderColumn,
            $params->orderDirection,
            $params->searchValue,
            $params->filters
        );

        $total = $this->registrsRepository->countTotalRecords();
        $filtered = $this->registrsRepository->countFilteredRecords($params->searchValue, $params->filters);

        return [
            'draw' => $params->draw,
            'recordsTotal' => $total,
            'recordsFiltered' => $filtered,
            'data' => $data,
        ];
    }

    public function exportToExcel(FilterParamsDTO $params): string
    {
        $records = $this->registrsRepository->getFilteredData(
            null,
            null,
            $params->orderColumn,
            $params->orderDirection,
            $params->searchValue,
            $params->filters
        );

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->fromArray(['ID', 'Veids', 'Bruto', 'Neto', 'Brāķis']);

        $row = 2;
        foreach ($records as $record) {
            $sheet->setCellValue('A' . $row, $record['id']);
            $sheet->setCellValue('B' . $row, $record['uzm_tips_vmf']);
            $sheet->setCellValue('C' . $row, $record['tilp_bruto']);
            $sheet->setCellValue('D' . $row, $record['tilp_neto']);
            $sheet->setCellValue('E' . $row, $record['tilp_brakis']);
            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        $tempFile = tempnam(sys_get_temp_dir(), 'export_');
        $writer->save($tempFile);

        return $tempFile;
    }

    public function getGroupedDataByType(int $draw): array
    {
        $data = $this->registrsRepository->getGroupedDataByType();

        return [
            'draw' => $draw,
            'recordsTotal' => count($data),
            'recordsFiltered' => count($data),
            'data' => $data,
        ];
    }

    public function getTilpPaSortimentiem(int $draw): array
    {
        $data = $this->grupRepository->getTilpPaSortimentiem();

        return [
            'draw' => $draw,
            'recordsTotal' => count($data),
            'recordsFiltered' => count($data),
            'data' => $data,
        ];
    }

    public function getGroupedLocationData(?string $startDate = null, ?string $endDate = null): array
    {
        $results = $this->registrsRepository->getLocationDataWithFilters($startDate, $endDate);
        $grouped = [];

        foreach ($results as $row) {
            $date = Carbon::parse($row['datums'])->toDateString();
            $vieta = trim($row['vieta']);
            $key = "$date|$vieta";

            if (!isset($grouped[$key])) {
                $grouped[$key] = [
                    'datums' => $date,
                    'vieta' => $vieta,
                    'skaits' => 0,
                ];
            }

            $grouped[$key]['skaits'] += (int)$row['skaits'];
        }

        $finalResults = array_values($grouped);

        usort($finalResults, fn($a, $b) => ($a['datums'] <=> $b['datums']) ?: strcmp($a['vieta'], $b['vieta']));

        return $finalResults;
    }
}
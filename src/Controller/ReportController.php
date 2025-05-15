<?php

declare(strict_types=1);

namespace App\Controller;

use App\DTO\FilterParamsDTO;
use App\Service\KpdcRegistrsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReportController extends AbstractController
{
    public function __construct(
        private readonly KpdcRegistrsService $service
    )
    {
    }

    #[Route('', name: 'registrs', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('reports/KpdcRegistrs.html.twig');
    }

    #[Route('registrs/data', name: 'registrs_data', methods: ['GET'])]
    public function data(Request $request): JsonResponse
    {
        $params = FilterParamsDTO::fromRequest($request);
        $response = $this->service->getFilteredData($params);

        return $this->json($response);
    }

    #[Route('registrs/export', name: 'report_registrs_export', methods: ['GET'])]
    public function export(Request $request): Response
    {
        $params = FilterParamsDTO::fromRequest($request);
        $filePath = $this->service->exportToExcel($params);

        return $this->file($filePath, 'KpdcRegistrs.xlsx');
    }

    #[Route('kopejais-uzmeritais-tilpums', name: 'kop_uzm_tilpums', methods: ['GET'])]
    public function uzmTilpumsIndex(): Response
    {
        return $this->render('reports/UzmTilpums.html.twig');
    }

    #[Route('kopejais-uzmeritais-tilpums/data', name: 'kop_uzm_tilpums_data', methods: ['GET'])]
    public function uzmTilpumsData(Request $request): JsonResponse
    {
        $draw = $request->query->getInt('draw', 1);
        $response = $this->service->getGroupedDataByType($draw);

        return $this->json($response);
    }

    #[Route('tilpums-pa-sortimentiem', name: 'tilp_pa_sortimentiem', methods: ['GET'])]
    public function tilpPaSortimentiemIndex(): Response
    {
        return $this->render('reports/TilpPaSortimentiem.html.twig');
    }

    #[Route('tilpums-pa-sortimentiem/data', name: 'tilp_pa_sortimentiem_data', methods: ['GET'])]
    public function tilpPaSortimentiemData(Request $request): JsonResponse
    {
        $draw = $request->query->getInt('draw', 1);
        $response = $this->service->getTilpPaSortimentiem($draw);

        return $this->json($response);
    }

    #[Route('uzmerito-balku-skaits', name: 'uzm_balku_skaits', methods: ['GET'])]
    public function uzmBalkuSkaitsIndex(): Response
    {
        return $this->render('reports/UzmBalkuSkaits.html.twig');
    }

    #[Route('uzmerito-balku-skaits/data', name: 'uzm_balku_skaits_data', methods: ['GET'])]
    public function uzmBalkuSkaitsData(Request $request): JsonResponse
    {
        $startDate = $request->query->get('startDate');
        $endDate = $request->query->get('endDate');
        $data = $this->service->getGroupedLocationData($startDate, $endDate);

        return $this->json([
            'data' => $data,
        ]);
    }
}

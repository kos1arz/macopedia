<?php 
declare(strict_types=1);

namespace App\Controller;

use App\Form\CsvImportFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\CsvFile;

class CsvImportController extends AbstractController
{
    public CsvFile $csvFileService;

    public function __construct(CsvFile $csvFileService) {
        $this->csvFileService = $csvFileService;
    }

    /**
     * @Route("/csv-import", name="csv_import")
     */
    public function index(Request $request)
    {
        $form = $this->createForm(CsvImportFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $csvFile = $form->get('file')->getData();
            if ($csvFile) {
                $this->csvFileService->upload($csvFile);
            }

            return $this->redirectToRoute('csv_import');
        }

        return $this->render('csv_import/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}

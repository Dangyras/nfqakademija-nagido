<?php

namespace App\Controller;

use App\Entity\Document;
use App\Service\DataService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Validator\Constraints as Assert;

class DocumentSearchController extends Controller
{
    /**
     * @param Request $request
     * @param DataService $dataService
     * @return JsonResponse
     */
    public function index(Request $request, DataService $dataService)
    {
        $input = $request->request->get('id');

        $documents = $this->getDoctrine()->getRepository(Document::class)
            ->search($input, $this->getUser());

        return $dataService->processData($documents);
    }
}

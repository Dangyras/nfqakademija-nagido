<?php
/**
 * Created by PhpStorm.
 * User: dangis
 * Date: 18.6.2
 * Time: 14.26
 */

namespace App\Controller;

use App\Entity\Document;
use App\Service\DataService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ReminderController extends Controller
{
    public function index(DataService $dataService)
    {
        $documents = $this->getDoctrine()->getRepository(Document::class)
            ->reminderDates($this->getUser());
        return $dataService->processData($documents);
    }
}

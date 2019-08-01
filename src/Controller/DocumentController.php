<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Document;
use App\Entity\Tag;
use App\Service\DataService;
use App\Service\Google\CalendarService;
use App\Service\Google\DriveService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @property DriveService drive
 * @property CalendarService calendar
 */
class DocumentController extends Controller
{
    /**
     * DocumentController constructor.
     * @param DriveService $driveService
     */
    public function __construct(DriveService $driveService, CalendarService $calendarService)
    {
        $this->drive = $driveService;
        $this->calendar = $calendarService;
    }

    /**
     * @param Request $request
     * @param DataService $dataService
     * @return JsonResponse
     */
    public function index(Request $request, DataService $dataService)
    {
        $documents = $this->getUser()->getDocuments();
        return $dataService->processData($documents);
    }

    /**
     * @param Request $request
     * @param DataService $dataService
     * @return JsonResponse
     */
    public function edit(Request $request, DataService $dataService)
    {
        $input = $request->request->get('id');

        $documents = $this->getDoctrine()->getRepository(Document::class)->findOneBy(["id" => $input]);

        return $dataService->processData($documents);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function update(Request $request)
    {
        $id = $request->request->get('documentId');
        $name = $request->request->get('documentName');
        $category = $request->request->get('documentCategory');
        $date = $request->request->get('documentDate');
        $expires = $request->request->get('documentExpires');
        $reminder = $request->request->get('documentReminder');
        $notes = $request->request->get('documentNotes');
        $tags = $request->request->get('checkbox');
        $files = $request->files->get('files');

        $document = $this->getDoctrine()->getRepository(Document::class)
            ->findOneBy(["id" => $id]);
        $category = $this->getDoctrine()->getRepository(Category::class)
            ->findOneBy(["id" => $category]);

        if (sizeof($files) > 0) {
            $this->drive->storageInit();
            foreach ($files as $file) {
                $originalName = $file->getClientOriginalName();
                $filePath = $file->getpathName();
                $this->drive->saveFiles($filePath, $name, $originalName);
            }
            if ($document->getDocumentPath() === null) {
                $document->setDocumentPath($this->drive->getFolderLink($name));
            }
        }

        $document->setDocumentName($name);
        $document->setCategory($category);
        $document->setDocumentNotes($notes);

        if ($tags !== null) {
            foreach ($tags as $tagId) {
                $tagRep = $this->getDoctrine()->getRepository(Tag::class)
                    ->findOneBy(["id" => $tagId]);
                if ($tagRep) {
                    $document->removeTag($tagRep);
                } else {
                    $tagName = $this->getDoctrine()->getRepository(Tag::class)
                        ->findOneBy(["tagName" => $tagId]);
                    if ($tagName) {
                        $tagName->addDocument($document);
                        $document->addTag($tagName);
                    } else {
                        $tag = new Tag();
                        $tag->setTagName($tagId);
                        $tag->addDocument($document);
                        $document->addTag($tag);
                    }
                }
            }
        }

        if ($date === "") {
            $document->setDocumentDate(null);
        } else {
            $document->setDocumentDate(\DateTime::createFromFormat('Y-m-d', $date));
        }
        if ($expires === "") {
            $document->setDocumentExpires(null);
        } else {
            $document->setDocumentExpires(\DateTime::createFromFormat('Y-m-d', $expires));
        }
        if ($reminder === "") {
            $document->setDocumentReminder(null);
        } else {
            $document->setDocumentReminder(\DateTime::createFromFormat('Y-m-d', $reminder));
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($document);
        $entityManager->flush();
        return $this->redirect("/");
    }

    public function test(Request $request)
    {
        $id = $request->request->get('id');

        $file = $this->getDoctrine()->getRepository(Document::class)->findOneBy(["id" => $id]);
        $documentPath = $file->getDocumentPath();
        $documentReminder = $file->getDocumentReminder();

        if ($documentPath) {
            $this->calendar->deleteReminder($documentReminder);
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($file);
        $entityManager->flush();

        return new Response();
    }
}

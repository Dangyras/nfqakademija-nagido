<?php

namespace App\Service;

use App\Entity\Tag;
use App\Service\Google\CalendarService;
use App\Service\Google\DriveService;
use DateInterval;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;

class SaveDocument
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var DriveService
     */
    private $drive;

    /**
     * SaveDocument constructor.
     * @param DriveService $driveService
     * @param EntityManagerInterface $entityManager
     * @param CalendarService $calendarService
     */
    public function __construct(
        DriveService $driveService,
        EntityManagerInterface $entityManager,
        CalendarService $calendarService
    ) {
        $this->em = $entityManager;
        $this->drive = $driveService;
        $this->calendar = $calendarService;
    }

    /**
     * @param $form
     * @param $user
     * @throws \Exception
     */
    public function createDocument($form, $user)
    {
        $documentName = $form["documentName"]->getData();
        $files = $form["files"]->getData();
        $tags = $form["tag"]->getData();
        $document = $form->getData();
        $documentReminder = $form["documentReminder"]->getData();
        $documentNotes = $form["documentNotes"]->getData();

        $document->setUser($user);
        $this->addTags($document, $tags);

        if (sizeof($files) > 0) {
            $this->drive->storageInit();
            foreach ($files as $file) {
                $originalName = $file->getClientOriginalName();
                $filePath = $file->getpathName();
                $this->drive->saveFiles($filePath, $documentName, $originalName);
            }
            $document->setDocumentPath($this->drive->getFolderLink($documentName));
        }

        if ($documentReminder) {
            $datetime = $documentReminder->sub(new DateInterval('PT0H'));
            $datetime = $datetime->format(DateTime::ATOM);
            $this->calendar->setDate($datetime, $documentNotes, $documentName);
        }

        


        $this->em->persist($document);
        $this->em->flush();
    }

    /**
     * @param $document
     * @param $tags
     */
    private function addTags($document, $tags)
    {
        foreach ($tags as $tag) {
            $entityManager = $this->em;
            $user = $entityManager
                ->getRepository(Tag::class)
                ->findOneBy(array('tagName' => $tag->getTagName()));
            if ($user) {
                $user->addDocument($document);
                $document->addTag($user);
            } else {
                $newTag = new Tag();
                $newTag->setTagName($tag->getTagName());
                $newTag->addDocument($document);
                $document->addTag($newTag);
            }
        }
    }
}

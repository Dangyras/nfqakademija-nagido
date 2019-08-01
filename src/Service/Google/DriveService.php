<?php
/**
 * Created by PhpStorm.
 * User: dangis
 * Date: 18.5.30
 * Time: 15.25
 */

namespace App\Service\Google;

use Google_Client;
use Google_Service_Drive;
use Google_Service_Drive_DriveFile;
use Google_Service_Drive_Permission;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class DriveService
{
    private $folderId;

    private $folderName;

    private $tokenStorage;

    /**
     * DriveService constructor.
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
        //Set Storage Name
        $this->folderName = "Nagido-Files";
    }

    public function getClient()
    {
        $user = $this->tokenStorage->getToken()->getUser();

        //Create google client
        $client = new Google_Client();
        $client->setAccessToken($user->getGoogleAccessToken());

        //Set Drive Service
        return new Google_Service_Drive($client);
    }

    public function storageInit()
    {
        //Get User

        $service = $this->getClient();
        $listFolder = $service->files->listFiles(['q' => "name='$this->folderName'"]);
        if (empty($listFolder->getFiles())) {
            //Create new folder
            $fileMetadata = new Google_Service_Drive_DriveFile(array(
                'name' => $this->folderName,
                'mimeType' => 'application/vnd.google-apps.folder',
                'folderColorRgb' => '#ff7f2a'
            ));
            //Insert folder
            $newFolder = $service->files->create($fileMetadata, array(
                'fields' => 'id'));
            $newFolder->setFolderColorRgb("#ff7f2a");
            $this->folderId = $newFolder->getId();

            $newPermission = new Google_Service_Drive_Permission();
            $newPermission->setType('anyone');
            $newPermission->setRole('reader');
            $service->permissions->create($this->folderId, $newPermission);
        } else {
            $this->folderId = $listFolder->getFiles()[0]->getId();
        }
    }

    public function saveFiles($filePath, $documentName, $originalName)
    {
        $Id = $this->createFolder($documentName);
        $service = $this->getClient();
        //Get file mimeType
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        //$file_path = '../public/uploads/brochures/'.$fileName;
        $mime_type = finfo_file($finfo, $filePath);

        //New file
        $file = new Google_Service_Drive_DriveFile(array(
            'name' => $originalName,
            'mimeType' => $mime_type,
            'description' => 'This is a '.$mime_type.' document',
            'parents' => array($Id)
        ));
        //Insert new file
        $service->files->create($file, array(
            'data' => file_get_contents($filePath),
            'mimeType' => $mime_type
        ));
    }

    /**
     * @param $folderName
     * @return mixed
     */
    public function createFolder($folderName)
    {
        $service = $this->getClient();
        $listFolder = $service->files->listFiles(['q' => "name='$folderName'"]);
        if (empty($listFolder->getFiles())) {
            //Create new folder
            $fileMetadata = new Google_Service_Drive_DriveFile(array(
                'name' => $folderName,
                'mimeType' => 'application/vnd.google-apps.folder',
                'folderColorRgb' => '#ff7f2a',
                'parents' => array($this->folderId)
            ));
            //Insert folder
            $newFolder = $service->files->create($fileMetadata, array(
                'fields' => 'id'));
            $newFolder->setFolderColorRgb("#ff7f2a");
            return $newFolder->getId();
        } else {
            return $listFolder->getFiles()[0]->getId();
        }
    }

    /**
     * @param $documentName
     * @return string
     */
    public function getFolderLink($documentName) : String
    {
        $service = $this->getClient();
        $listFolder = $service->files->listFiles(['q' => "name='$documentName'"]);
        $link = "https://drive.google.com/drive/folders/" . $listFolder->getFiles()[0]->getId();
        return $link;
    }

    public function deleteFiles($id)
    {
        $id = substr($id, strpos($id, "folders/") + 8);
        $service = $this->getClient();
        $service->files->delete($id);
    }
}

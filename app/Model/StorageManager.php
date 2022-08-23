<?php

namespace App\Model;

use Google\Cloud\Storage\StorageClient;

class StorageManager
{


    private $projectId;
    private $storage;

    public function __construct()
    {
        putenv("GOOGLE_APPLICATION_CREDENTIALS=../credentials/red-truck-329213-751511f34edb.json");
        $this->projectId = $_ENV['PROJECT_ID'];
        $this->storage = new StorageClient([
            'projectId' => $this->projectId
        ]);
    }

    public function createBucket($bucketName)
    {

        $bucket = $this->storage->createBucket($bucketName);

        return $bucketName;
    }

    function upload_object($bucketName, $objectName, $source)
    {

        $storage = new StorageClient();
        $file = fopen($source, 'r');
        $bucket = $storage->bucket($bucketName);
        $object = $bucket->upload($file, [
            'name' => $objectName
        ]);
    }

    function download_object($bucketName, $objectName)
    {


        $storage = new StorageClient();
        $bucket = $storage->bucket($bucketName);
        $object = $bucket->object($objectName);
        $url = $object->signedUrl(
            # This URL is valid for 15 minutes
            new \DateTime('15 min'),
            [
                'version' => 'v4',
            ]
        );

        return $url;
    }


    /**
     * Delete an object.
     *
     * @param string $bucketName The name of your Cloud Storage bucket.
     * @param string $objectName The name of your Cloud Storage object.
     */
    function delete_object($bucketName, $objectName)
    {

        $storage = new StorageClient();
        $bucket = $storage->bucket($bucketName);
        $object = $bucket->object($objectName);
        $object->delete();
    }


    function image_download($bucketName, $objectName, $destination)
    {


        $storage = new StorageClient();
        $bucket = $storage->bucket($bucketName);
        $object = $bucket->object($objectName);
        $object->downloadToFile($destination);
    }
}

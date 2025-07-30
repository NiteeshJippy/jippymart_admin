<?php

use Google\Cloud\Firestore\FirestoreClient;

if (!function_exists('firestore')) {
    function firestore()
    {
        return new FirestoreClient([
            'projectId' => config('firestore.project_id'),
            'keyFilePath' => config('firestore.credentials'),
        ]);
    }
} 
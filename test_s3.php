<?php
require __DIR__ . '/vendor/autoload.php';
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

// Crée l'application Laravel pour utiliser Storage
$app = require __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$localFile = storage_path('app/test.jpg');
$file = new UploadedFile($localFile, 'test.jpg', null, null, true);
$filename = time() . '_test.jpg';

var_dump($file->isValid());    // doit être true
var_dump(file_exists($localFile)); // doit être true

$path = $file->storeAs('projets', $filename, 's3');

echo "Chemin relatif : $path\n";
echo "URL S3 : " . Storage::disk('s3')->url($path) . "\n";

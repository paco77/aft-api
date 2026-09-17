<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\ClientProgressLog;

echo "Iniciando pruebas de S3...\n\n";

// 1. Crear una imagen falsa localmente
$imageContent = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII=');
file_put_contents('test_dummy.png', $imageContent);
$file = new UploadedFile('test_dummy.png', 'test_dummy.png', 'image/png', null, true);

// 2. Probar el Trait de subida
class TestUploader {
    use \App\Traits\ImageUploadTrait;
    public function testUpload($file) {
        return $this->processAndStoreImage($file, 'clients/9999/progress/8888', 'front');
    }
}

$uploader = new TestUploader();
try {
    $path = $uploader->testUpload($file);
    echo "[EXITO] Imagen subida y comprimida correctamente en: " . $path . "\n";
    
    // Verificar que existe
    if (Storage::exists($path)) {
        echo "[EXITO] Verificacion: El archivo se encontro en DigitalOcean Spaces.\n";
    } else {
        echo "[ERROR] El archivo no se encontro en S3 tras la subida.\n";
    }
} catch (\Exception $e) {
    echo "[ERROR] Excepcion durante la subida: " . $e->getMessage() . "\n";
}

// 3. Probar la simulación de eliminación de cliente
try {
    Storage::put('clients/9999/test-deletion.txt', 'test content');
    Storage::deleteDirectory('clients/9999');
    
    if (!Storage::exists('clients/9999/test-deletion.txt') && !Storage::exists($path)) {
        echo "[EXITO] Eliminacion en cascada exitosa: Toda la carpeta 'clients/9999' fue borrada correctamente de S3.\n";
    } else {
        echo "[ERROR] Fallo la eliminacion en cascada.\n";
    }
} catch (\Exception $e) {
    echo "[ERROR] Excepcion durante la eliminacion: " . $e->getMessage() . "\n";
}

@unlink('test_dummy.png');
echo "\nPruebas finalizadas.\n";

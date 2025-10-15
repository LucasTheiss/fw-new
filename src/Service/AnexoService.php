<?php
namespace src\Service;

use Exception;

class AnexoService
{
    private $uploadDir;

    public function __construct(string $targetSubdir = 'uploads/')
    {
        $this->uploadDir = dirname(__DIR__, 2) . '/assets/' . $targetSubdir;
        
        if (!file_exists($this->uploadDir)) {
            mkdir($this->uploadDir, 0777, true);
        }
    }

    public function salvar(array $file): string
    {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new Exception("Erro no upload do ficheiro: " . $file['error']);
        }

        $fileName = uniqid() . '_' . basename($file["name"]);
        $targetPath = $this->uploadDir . $fileName;

        if (!move_uploaded_file($file["tmp_name"], $targetPath)) {
            throw new Exception("Não foi possível mover o ficheiro enviado.");
        }

        return 'assets/uploads/' . $fileName;
    }
}

<?php
namespace src\Service;

use Exception;

class AnexoService
{
    private $uploadDir;

    public function __construct(string $targetSubdir = 'uploads/')
    {
        // Garante que o caminho seja relativo à raiz do projeto
        $this->uploadDir = dirname(__DIR__, 2) . '/assets/' . $targetSubdir;
        
        if (!file_exists($this->uploadDir)) {
            mkdir($this->uploadDir, 0777, true);
        }
    }

    /**
     * Salva um ficheiro enviado e retorna o seu caminho relativo.
     *
     * @param array $file O ficheiro do array $_FILES.
     * @return string O caminho relativo do ficheiro salvo.
     * @throws Exception Se ocorrer um erro no upload.
     */
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

        // Retorna o caminho a partir da raiz do projeto para ser guardado na DB
        return 'assets/uploads/' . $fileName;
    }
}

<?php
namespace src\Service;

use src\Model\Transportadora;
use src\Model\Gerente;
use src\Model\Solicitacao;
use src\Repository\TransportadoraRepository;
use src\Repository\UsuarioRepository;
use src\Repository\SolicitacaoRepository;
use src\Service\AnexoService;
use Exception;

class RegistrationService
{
    private $transportadoraRepo;
    private $usuarioRepo;
    private $solicitacaoRepo;
    private $anexoService;

    public function __construct()
    {
        $this->transportadoraRepo = new TransportadoraRepository();
        $this->usuarioRepo = new UsuarioRepository();
        $this->solicitacaoRepo = new SolicitacaoRepository();
        $this->anexoService = new AnexoService('anexos_solicitacao/');
    }

    /**
     * Orquestra o registo completo de uma transportadora e do seu gerente.
     * Atua como uma Facade para simplificar este processo complexo.
     *
     * @param array $transportadoraData Dados do formulário da transportadora.
     * @param array $gerenteData Dados do formulário do gerente.
     * @param array $files Ficheiros de anexo do $_FILES.
     * @return bool Retorna verdadeiro em caso de sucesso, falso caso contrário.
     */
    public function register(array $transportadoraData, array $gerenteData, array $files): bool
    {
        // A base de dados deve suportar transações para garantir a consistência dos dados.
        // Assumindo que a classe Database tem métodos para isso.
        $db = \Config\Database::getInstance()->getConnection();
        $db->beginTransaction();

        try {
            // Passo 1: Criar a Transportadora com estado 'pendente'
            $transportadora = new Transportadora();
            $transportadora->razao_social = $transportadoraData['razao_social'];
            $transportadora->nome_fantasia = $transportadoraData['nome_fantasia'];
            $transportadora->cnpj = $transportadoraData['cnpj'];
            // ... preencher outros campos da transportadora
            $transportadora->status = 'pendente';
            $transportadoraId = $this->transportadoraRepo->create($transportadora);

            if (!$transportadoraId) {
                throw new Exception("Falha ao criar a transportadora.");
            }

            // Passo 2: Criar o Utilizador Gerente
            $gerente = new Gerente();
            $gerente->nome = $gerenteData['nome'];
            $gerente->email = $gerenteData['email'];
            $gerente->senha = $gerenteData['senha'];
            // ... preencher outros campos do gerente
            $gerenteId = $this->usuarioRepo->create($gerente, $transportadoraId);

            if (!$gerenteId) {
                throw new Exception("Falha ao criar o utilizador gerente.");
            }

            // Passo 3: Processar e guardar os anexos
            $caminhosAnexos = [];
            foreach ($files['tmp_name'] as $key => $tmpName) {
                if (!empty($tmpName)) {
                    $file = [
                        'name' => $files['name'][$key],
                        'type' => $files['type'][$key],
                        'tmp_name' => $tmpName,
                        'error' => $files['error'][$key],
                        'size' => $files['size'][$key]
                    ];
                    $caminhosAnexos[] = $this->anexoService->salvar($file);
                }
            }
            
            // Passo 4: Criar a Solicitação de aprovação
            $solicitacao = new Solicitacao();
            $solicitacao->idtransportadora = $transportadoraId;
            $solicitacao->idgerente = $gerenteId;
            // Guardar os caminhos dos ficheiros como uma string JSON
            $solicitacao->anexos = json_encode($caminhosAnexos);
            
            if (!$this->solicitacaoRepo->create($solicitacao)) {
                throw new Exception("Falha ao criar a solicitação de registo.");
            }

            // Se tudo correu bem, confirma as alterações na base de dados
            $db->commit();
            return true;

        } catch (Exception $e) {
            // Se algo falhou, reverte todas as operações
            $db->rollBack();
            // Opcional: logar o erro $e->getMessage() para depuração
            return false;
        }
    }
}

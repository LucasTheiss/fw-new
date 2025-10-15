<?php
namespace src\Service;

use src\Model\Solicitacao;
use src\Repository\SolicitacaoRepository;

class RegistrationService
{
    private $solicitacaoRepo;

    public function __construct()
    {
        $this->solicitacaoRepo = new SolicitacaoRepository();
    }

    public function registerFromForm(array $formData): bool
    {
        if (empty($formData['nomeEmpresa']) || empty($formData['cnpj']) || empty($formData['email']) || empty($formData['senha'])) {
            $_SESSION['alert'] = ['title' => 'Erro!', 'text' => 'Por favor, preencha todos os campos obrigatórios.', 'icon' => 'warning'];
            return false;
        }

        $solicitacao = new Solicitacao();
        $solicitacao->nomeTransportadora = $formData['nomeEmpresa'];
        $solicitacao->endereco = $formData['endereco'];
        $solicitacao->cep = preg_replace('/\D/', '', $formData['cep']);
        $solicitacao->cidade = $formData['cidade'];
        $solicitacao->estado = strtoupper($formData['estado']);
        $solicitacao->telefoneEmpresa = $formData['telefoneEmpresa'];
        $solicitacao->cnpj = preg_replace('/\D/', '', $formData['cnpj']);
        $solicitacao->nomeUsuario = $formData['nomePessoal'];
        $solicitacao->sobrenome = $formData['sobrenome'];
        $solicitacao->emailUsuario = $formData['email'];
        $solicitacao->cpf = preg_replace('/\D/', '', $formData['cpf']);
        $solicitacao->senha = password_hash($formData['senha'], PASSWORD_DEFAULT);
        $solicitacao->telefoneUsuario = $formData['telefonePessoal'];
        $solicitacao->status = 0; 

        return $this->solicitacaoRepo->create($solicitacao);
    }
}
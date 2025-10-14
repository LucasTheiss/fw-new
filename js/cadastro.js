const validations = {
    "textarea[name='endereco']": [
        {
            validate: (value) => value.trim().length > 0,
            message: "Campo obrigatório."
        }
    ],
    "input[name='nomeEmpresa']": [
        {
            validate: (value) => value.trim().length > 1,
            message: "O nome deve ter ao menos 2 caracteres."
        }
    ],
    "input[name='telefoneEmpresa']": [
        {
            validate: (value) => /^\(?\d{2}\)?\s?\d{4,5}-?\d{4}$/.test(value),
            message: "Formato de telefone inválido. Ex: (11) 98765-4321"
        }
    ],
    "input[name='telefonePessoal']": [
        {
            validate: (value) => /^\(?\d{2}\)?\s?\d{4,5}-?\d{4}$/.test(value),
            message: "Formato de telefone inválido. Ex: (11) 98765-4321"
        }
    ],
    "input[name='cnpj']": [
        {
            validate: (value) => validarCNPJ(value),
            message: "CNPJ inválido."
        }
    ],
    "input[name='email']": [
        {
            validate: (value) => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value),
            message: "E-mail inválido."
        }
    ],
    "input[name='senha']": [
        {
            validate: (value) => value.length >= 8,
            message: "A senha deve ter pelo menos 8 caracteres."
        }
    ],
    "input[name='cidade']": [
        {
            validate: (value) => value.length >= 2,
            message: "Formato inválido."
        }
    ],
    "input[name='estado']": [
        {
            validate: (value) => value.length == 2,
            message: "Formato de estado inválido."
        }
    ],
    "input[name='cep']": [
        {
            validate: (value) => value.replace(/\D/g, '').length == 8,
            message: "Formato de CEP inválido."
        }
    ],
    "input[name='nomePessoal']":[
        {
            validate: (value) => value.length >= 2,
            message: "Nome muito Curto."
        }
    ],
    "input[name='sobrenome']":[
        {
            validate: (value) => value.length >= 2,
            message: "Sobrenome muito Curto."
        }
    ],
    "input[name='senha']":[
        {
            validate: (value) => value.length >= 8,
            message: "Senha muito curta."
        },
        {
            validate: (value) => /[!@#$%^&*()\-_=+\[\]{};:,.?\/|~`]/.test(value),
            message: "Sua senha deve conter ao menos um caractere especial."
        },
    ],
    "input[name='confirmarSenha']":[
        {
            validate: (value) => value == document.querySelector('[name="senha"]').value,
            message: "As senhas não coincidem."
        }
    ],
    "input[name='cpf']":[
        {
            validate: (value) => validarCPF(value.replace(/\D/g, '')),
            message: "CPF inválido."
        }
    ],
}

function validarCampo(field, selector) {
    const rules = validations[selector]
    if (!rules) return

    const hint = field.parentElement.querySelector("p.hint")
    const erro = rules.find(rule => !rule.validate(field.value))

    if (erro) {
        hint.textContent = erro.message
        hint.classList.remove("hidden")
        hint.classList.add("input-error")
    } else {
        hint.textContent = ""
        hint.classList.remove("input-error")

        switch (field.name) {
            case 'cnpj':
                hint.textContent = 'Número de Identificação Fiscal da Empresa'
                break
            case 'email':
                hint.textContent = 'Este será seu usuário para login'
                break
            case 'senha':
                hint.textContent = 'A senha deve ter ao menos 8 caracteres'
                break
            default:
                hint.textContent = ''
        }
    }
}

document.addEventListener("DOMContentLoaded", () => {
    // adiciona as regras conforme o elemento
    Object.keys(validations).forEach(name => {
        const field = document.querySelector(`${name}`);
        if (!field) return
        field.addEventListener("blur", () => {
            validarCampo(field, name);
        });
    })

    // autocomplete do cep
    let elementoCep = document.querySelector('[name="cep"]')
    elementoCep.addEventListener('blur', () =>{
        const cep = elementoCep.value.replace(/\D/g, '')
        if (cep.length === 8) {
            buscarCEP(cep)
        }
    })

    document.querySelectorAll('.tab-btn').forEach(button => {
        button.addEventListener('click', (e) => {
            e.preventDefault()
            const alvo = button.getAttribute('data-tab')
            const ativo = document.querySelector('.tab-btn.active').getAttribute('data-tab')

            // só executa se clicar em uma aba diferente
            if (alvo === ativo) return

            // se a aba atual for "empresa", exige validação antes de mudar
            if (ativo === 'empresa') {
                alterarAba('empresa', 'pessoal')
            } else {
                alterarAba('pessoal', 'empresa', false) // permite voltar sem validação
            }
        });
    })

    // verificacao de formularios nos envios
    // TODO
    // document.querySelector('#pessoal form').addEventListener('submit', (e) => {
    //     if (!validarFormularioCompleto()) {
    //         e.preventDefault();
    //         alert('Corrija os erros no formulário antes de enviar.')
    //     }
    // })

    // document.querySelector('#empresa form').addEventListener('submit', (e) => {
    //     if (!validarFormularioCompleto()) {
    //         e.preventDefault();
    //         alert('Corrija os erros no formulário antes de enviar.')
    //     }
    // })
})

function validarFormularioCompleto(event, button) {
  event.preventDefault(); // impede envio automático

  let valido = true;
  const form = button.closest("form"); // forma mais robusta

  Object.keys(validations).forEach(selector => {
    const field = form.querySelector(selector); // escopado ao form correto
    if (!field) return;

    const rules = validations[selector];
    const hint = field.parentElement.querySelector("p.hint");

    const erro = rules.find(rule => !rule.validate(field.value));

    if (erro) {
      hint.textContent = erro.message;
      hint.classList.remove("hidden");
      hint.classList.add("input-error");
      valido = false;
    } else {
        hint.textContent = "";
        // hint.classList.add("hidden");
        hint.classList.remove("input-error");
        switch (field.name) {
            case 'cnpj':
                hint.textContent = 'Número de Identificação Fiscal da Empresa';
                break;
            case 'email':
                hint.textContent = 'Este será seu usuário para login';
                break;
            case 'senha':
                hint.textContent = 'A senha deve ter ao menos 8 caracteres';
                break;
            default:
                hint.textContent = '';
        }
    }
  });

  if (valido) {
    form.submit();
  } else {
    Swal.fire({
        title:'Preencha corretamente os dados', 
        text:'Há dados que precisam de alteração.', 
        icon:'question', 
        iconColor: "#fedf00"
    })
  }
}

function validarCNPJ(cnpj){
    cnpj = cnpj.replace(/[^\d]+/g,'');

  if(cnpj.length !== 14)
    return false;

  let tamanho = 12;
  let numeros = cnpj.substring(0, tamanho);
  let digitos = cnpj.substring(tamanho);
  let soma = 0;
  let pos = tamanho - 7;

  for(let i = tamanho; i >= 1; i--) {
    soma += numeros.charAt(tamanho - i) * pos--;
    if(pos < 2) pos = 9;
  }

  let resultado = soma % 11 < 2 ? 0 : 11 - (soma % 11);
  if(resultado != digitos.charAt(0))
    return false;

  tamanho = 13;
  numeros = cnpj.substring(0, tamanho);
  soma = 0;
  pos = tamanho - 7;

  for(let i = tamanho; i >= 1; i--) {
    soma += numeros.charAt(tamanho - i) * pos--;
    if(pos < 2) pos = 9;
  }

  resultado = soma % 11 < 2 ? 0 : 11 - (soma % 11);
  if(resultado != digitos.charAt(1))
    return false;

  return true;
}

async function buscarCEP(cep) {
    try {
        const response = await fetch(`https://viacep.com.br/ws/${cep}/json/`)
        const data = await response.json()
        if (data.erro) return

        const cidade = document.querySelector('[name="cidade"]')
        const estado = document.querySelector('[name="estado"]')
        const endereco = document.querySelector('[name="endereco"]')

        cidade.value = data.localidade
        estado.value = data.uf
        endereco.value = data.logradouro

        validarCampo(cidade, "input[name='cidade']")
        validarCampo(estado, "input[name='estado']")
        validarCampo(endereco, "textarea[name='endereco']")

    } catch (error) {
        console.error(error)
    }
}

function alterarAba(de, para, validar=true){
    const abaAtual = document.getElementById(de);
    const inputs = abaAtual.querySelectorAll('input, textarea');
    
    if (validar) {
        let valido = true;
        inputs.forEach(input => {
            const hint = input.nextElementSibling;
            if (input.value.trim() === '') {
                    valido = false;
                    hint.textContent = 'Campo obrigatório';
                    hint.classList.remove('hidden');
                    hint.classList.add('input-error');
            } else {
                hint.classList.add('hidden');
                switch (input.name) {
                    case 'cnpj':
                        hint.textContent = 'Número de Identificação Fiscal da Empresa';
                        break;
                    case 'email':
                        hint.textContent = 'Este será seu usuário para login';
                        break;
                    case 'senha':
                        hint.textContent = 'A senha deve ter ao menos 8 caracteres';
                        break;
                    default:
                        hint.textContent = '';
                }
            }
        });
        
        if (!valido) return;
    }

    document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
    document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));

    document.getElementById(para).classList.remove('hidden');
    document.querySelector(`.tab-btn[data-tab="${para}"]`).classList.add('active');
    document.querySelector(`.tab-btn[data-tab="${para}"]`).disabled = false;
}

function validarCPF(cpf) {
    if (cpf.length !== 11 || /^(\d)\1{10}$/.test(cpf)) {
        return false; 
    }

    for (let t = 9; t < 11; t++) {
        let soma = 0;
        for (let i = 0; i < t; i++) {
            soma += parseInt(cpf.charAt(i)) * ((t + 1) - i);
        }
        let digito = (soma * 10) % 11;
        if (digito === 10) digito = 0;
        if (parseInt(cpf.charAt(t)) !== digito) {
            return false;
        }
    }

    return true;
}
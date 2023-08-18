# Teste Houer

Olá.
Me desculpem, mas não consegui entregar o projeto completo até o prazo estabelecido. Mas como foi informado no documento do **Teste Prático**, estou enviando o conteúdo que consegui produzir até agora.
Peço que, caso eu ainda possa finalizar o projeto, entrem em contato para terminar esse trabalho por favor.

## Tecnologias utilizadas

- Laravel (API para o painel administrativo e páginas para o cadastro dos candidatos, login e busca das vagas)
- SvelteKit (esse não deu tempo de implementar, mas iria consumir a API do Laravel para gerenciar o sistema)
- PHPUnit (para os testes automatizados)
## Feito até o momento

Até a publicação do projeto, consegui adicionar as seguintes funções

### API

A API foi criada para alimentar o painel administrativo que seria feito com SvelteKit  (framework JavaScript).
Na API é possível:

- Login/logout na API
- Consultar e deletar candidatos
- CRUD das vagas
- CRUD dos usuários com acesso ao painel administrativo

Ficou faltando:

- Consultar as vagas as quais um candidato se candidatou
- Recurso de pausar a vaga

### WEB

Essa parte seria responsável pelo acesso do candidato, bem como seu cadastro no sistema e escolha das vagas disponíveis.
Nela é possível:

- Cadastrar um candidato e seu currículo
- Acessar o sistema através do cadastro informado

Ficou faltando:

- O candidato conseguir alterar os seus dados e currículo (faltou só o front end)
- Listar as vagas disponíveis
- O candidato entrar em uma vaga disponível
- O candidato excluir a própria conta (ficou faltando só o front end)

### Painel administrativo

Essa parte seria feita com o SvelteKit como mencionado acima, mas infelizmente não deu tempo.
Ele iria ser o responsável pelo front end que iria interagir com a API do Laravel

## Execução

Para executar o projeto basta:
- Clonar o repositório na branch `develop`
- Entrar no diretório criado
- Instalar as dependências com o comando `composer install`
- Instalar o Laravel Sail com o comando `php artisan sail:install`
- Ao executar o comando, apertar `Enter` para prosseguir com as configurações padrão
- Após a conclusão basta rodar o comando `./vendor/bin/sail up`
- Você pode acessar `http://localhost` para testar os recursos do front end
- Você pode executar os testes automatizados para verificar o restante das funcionalidades com o comando `php artisan test` (lembrando que deve estar dentro do diretório clonado)

A explicação acima leva em consideração que você em um ambiente `Linux` com o `Docker` instalado.
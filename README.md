# Movie API

## Desafio

Construir uma API Simples em Laravel(PHP) que permitisse Criar, Ler, Atualizar e Deletar 2 entidades diferentes e a minha escolha 
foi a criação de uma lista de Filmes.

## Descrição

A API de Filmes com Comentários permite que usuários logados possam criar, listar, ler, editar e deletar filmes, porém, somente o usuário dono do filme 
pode deletar e se, tentar deletar um filme que não seja dele, será retornando um erro, bem como criar e ler comentários com nota.

Para ter acesso aos recursos da API, o usuário precisa primeiro se autenticar por meio de um token de acesso. Caso contrário, 
a API irá retornar um erro de acesso negado.

A criação de um novo filme requer que o usuário envie um objeto JSON contendo as informações do filme, como nome, descrição e imagem. O campo user_id será 
preenchido com o ID do usuário logado.
Depois de criado, o filme será atribuído a um ID único e retornado como resposta.A listagem de filmes disponíveis pode ser feita por 
meio de uma solicitação GET. 

Para ler as informações de um filme específico, o usuário deve enviar uma solicitação GET contendo o ID do filme desejado. 
As informações do filme serão retornadas em formato JSON.

![Screenshot from 2023-03-20 12-09-27](https://user-images.githubusercontent.com/57235071/226382952-71b02897-21c8-4544-bce8-c649421964e7.png)

Para editar as informações de um filme específico, o usuário deve enviar uma solicitação POST contendo o ID do filme desejado e o _method=PUT no body
da requisição.

**Exemplo: Usuário não autorizado**

![Screenshot from 2023-03-20 12-05-46](https://user-images.githubusercontent.com/57235071/226381986-5f5ff2ee-2c60-4c2d-aff1-c3ba65e6e7c8.png)

Para excluir um filme, o usuário deve enviar uma solicitação DELETE contendo o ID do filme desejado. Se o filme não puder ser encontrado, 
a API retornará um erro correspondente.

Para criar um novo comentário, o usuário deve enviar um objeto JSON contendo a nota atribuída ao filme e o comentário 
propriamente dito. O ID do filme e ID do usuário será preenchido no metodo store. 
Depois de criado, o comentário será atribuído a um ID único e retornado como resposta.

Para ler os comentários de um filme específico, o usuário deve enviar uma solicitação GET contendo o ID do filme desejado junto o 'reviews' no endpoint.
Os comentários do filme serão retornadas em formato JSON.

![Screenshot from 2023-03-20 12-03-40](https://user-images.githubusercontent.com/57235071/226381446-34253724-9623-47ce-b76c-36c6c4ecaae4.png)

Essas ações estõ cobertos com Testes usando PHPUnit.

### Como executar o projeto
Clone Repositório
```sh
git clone https://github.com/EuclidesKinto/movie_api.git
```

Entre na pasta
```sh
cd movie_api
```

Remova a pasta .git
```sh
rm -rf .git/
```

Crie o Arquivo .env
```sh
cp .env.example .env
```
Atualize as variáveis de ambiente do arquivo .env
```dosini
APP_NAME=Pontue
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=movie_api
DB_USERNAME=pontue
DB_PASSWORD=pontue
```
Suba os containers do projeto
```sh
docker compose up -d
```

Acessar o container
```sh
docker compose exec movie_app bash
```

Instalar as dependências do projeto
```sh
composer install
```

Gerar a key do projeto Laravel
```sh
php artisan key:generate
```

Criar o banco de dados com os seeders
```sh
php artisan migrate --seed
```

Acessar no insomnia ou POstman
```sh
http://localhost:8180/api/movies
```

### Como Logar

Você deve esta logado para acessar os endpoints. Acesse o phpMyAdmin no endereço ```http://localhost:8081/```, 
entre na tabela users e escolha um usuário. Copie o email e acesse o endereço ```http://localhost:8180/api/auth/login```
a senha de todos os usuário criados é ```password```. 
Se preferir, crie um usuário no endpoint ```http://localhost:8180/api/auth/register```.

![Screenshot from 2023-03-20 11-59-41](https://user-images.githubusercontent.com/57235071/226380296-8f398347-64d6-4d9d-b9d6-fbfb0f589d93.png)


Rodar os Testes
```sh
php artisan test
```

### Route List

![Screenshot from 2023-03-20 11-53-14](https://user-images.githubusercontent.com/57235071/226379308-970271ce-912f-41b9-9013-9bf0d4f3255e.png)


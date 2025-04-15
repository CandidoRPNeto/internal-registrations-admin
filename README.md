# 🏫 Internal Registrations Admin

Projeto administrativo para **gestão de alunos, turmas e matrículas** em uma instituição de ensino. Criado com PHP puro e MySQL, utilizando `.phtml` no front-end e Bootstrap para estilização.

---

## Tecnologias Utilizadas

- **PHP** `8.3`
- **MySQL** `8.2`
- **Bootstrap** (via CDN)
- Arquitetura baseada em `MVC` com organização por camadas (Controller, Model, Repository)

---

## Configuração do Projeto

### Banco de Dados

Você pode rodar o banco de dados localmente de duas formas:

### Opção 1: MySQL Local
Crie um banco de dados com as seguintes credenciais:

```bash
    host: localhost
    user: root
    password: root
    database: meubanco
```
Importe o script SQL: 
```bash
sql/db.sql;
```

### Opção 2: Usando Docker
Execute o comando abaixo para subir o banco MySQL 8.2 com as configurações corretas:

```bash
docker run -d --name mysql-dev -e MYSQL_ROOT_PASSWORD=root -e MYSQL_DATABASE=meubanco -p 3306:3306 mysql:8.3
```

Fazendo por esse metodo e tentando conectar com o DBeaver e possivel que encontre o problema: Public Key Retrieval is not allowed

para resolver de forma rapida 

Use a seguinte URL de conexão:

```bash
jdbc:mysql://localhost:3306/meubanco?allowPublicKeyRetrieval=true&useSSL=false
```

### Modificando credenciais
Se quiser usar credenciais diferentes, edite o arquivo:
```bash
src/config/database.php
```

## Rodando o projeto
Com o PHP instalado, basta rodar o servidor embutido:
```bash
php -S localhost:8000
```
Acesse em: http://localhost:8000
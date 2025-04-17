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

Crie um banco de dados com as seguintes credenciais:

```bash
    host: localhost
    user: root
    password: root
    database: meubanco
```
Importe o script SQL: 
```bash
dump.sql;
```

### Modificando credenciais
Se quiser usar credenciais diferentes, edite o arquivo:
```bash
src/db/MySQLClient.php
```

## Rodando o projeto
Com o PHP instalado, basta rodar o servidor embutido:
```bash
php -S localhost:8000
```
Acesse em: http://localhost:8000
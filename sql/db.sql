-- RODE CADA ETAPA INDIVIDUALMENTE 

-- ETAPA 1: Criando o banco (Opcional caso o banco ja esteja criado)
CREATE DATABASE meubanco;

-- ETAPA 2: Selecionando o banco
USE meubanco;

-- ETAPA 3: Criando as tabelas (Ps: talvez seja necessario rodar uma por uma)
CREATE TABLE users (
    id INT PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    password VARCHAR(255),
    role VARCHAR(3)
);

CREATE TABLE classrooms (
    id INT PRIMARY KEY,
    name VARCHAR(100),
    description TEXT
);

CREATE TABLE students (
    id INT PRIMARY KEY,
    birth_date DATE,
    cpf VARCHAR(14),
    user_id INT,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE enrollments (
    id INT PRIMARY KEY,
    student_id INT,
    classroom_id INT,
    enrollment_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id),
    FOREIGN KEY (classroom_id) REFERENCES classrooms(id)
);

-- ETAPA 4: Inserindo o admin

INSERT INTO users (id, name, email, password, role) VALUES
(1, 'Carla Admin', 'carla.admin@example.com', '$2y$10$X.ZW6TXCMoUH7pnE1UOD2e3brtK32N9Fhi7kJc6MpLjvz4AdP8hS6', 'ADM');

-- ETAPA 5: Semeando as tabelas (Apenas se quiser dados pre cadasrados para facilitar os testes)

INSERT INTO users (id, name, email, password, role) VALUES
(2, 'Ana Souza', 'ana@example.com', '$2y$10$X.ZW6TXCMoUH7pnE1UOD2e3brtK32N9Fhi7kJc6MpLjvz4AdP8hS6', 'STD'),
(3, 'Bruno Lima', 'bruno@example.com', '$2y$10$X.ZW6TXCMoUH7pnE1UOD2e3brtK32N9Fhi7kJc6MpLjvz4AdP8hS6', 'STD');

INSERT INTO classrooms (id, name, description) VALUES
(1, 'Cauculo 1', 'Primeira materia de cauculo do curso'),
(2, 'Logica de programação e algoritimos', 'Primeira materia de programação do curso');

INSERT INTO students (id, birth_date, cpf, user_id) VALUES
(1, '2005-05-10', '123.456.789-00', 1),
(2, '2004-11-22', '987.654.321-00', 2);

INSERT INTO enrollments (id, student_id, classroom_id) VALUES
(1, 1, 1),
(2, 2, 2);
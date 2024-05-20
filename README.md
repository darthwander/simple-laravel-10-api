# laravelApi

## Projeto: Laravel API

### Características
- **Linguagem**: PHP v8.1
- **Framework**: Laravel Framework 10.48.10
- **Testes**: Pest 2.34
- **Banco de Dados**: PostgreSql v16 (latest)

### Requisitos
- PHP ^8.1
- Composer
- Docker
- Docker Compose

### Instalação e Configuração

**Premissa**: Este guia pressupõe que você já possui as ferramentas listadas em "Requisitos" instaladas em seu ambiente.

1. **Instalar Dependências**:
    ```sh
    composer install
    ```

2. **Iniciar Banco de Dados (PostgreSQL)**:
    ```sh
    docker-compose up -d
    ```

3. **Executar Migração**:
    ```sh
    php artisan migrate
    ```

4. **Popular Tabelas com Dados**:
    ```sh
    php artisan db:seed
    ```

5. **Iniciar Servidor**:
    ```sh
    php artisan serve
    ```

### Criação Manual da Pasta de Testes Unit (se necessário)

Caso a pasta `tests/Unit` não seja criada durante o processo, crie-a manualmente:
```sh
cd tests
mkdir Unit
```

### Testes Manuais

   - Um arquivo para testes manuais no Insomnia está disponível na raiz do projeto (`Insomnia_desafio_wander`). Importe-o no Insomnia para realizar os testes.



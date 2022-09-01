Projeto

Instalar dependencias 

    composer install

Executar migrations

    php artisan migrate

Criar registros teste

    php artisan db:seed

Rodar jobs

    php artisan queue:work

Rodar testes

    ./vendor/bin/phpunit
    

Testes qualidade

    docker run -it --rm -v ${PWD}:/app -w /app jakzal/phpqa phpmd app text cleancode,codesize,controversial,design,naming,unusedcode
    

No projeto temos Contas e Transferencias.

Existem 2 tipos de contas: comum e lojista. 

Centralizei a funcionalidade apenas na rota /transfer

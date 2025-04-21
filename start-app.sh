#!/bin/bash

# Inicia o container do Laravel Sail
./vendor/bin/sail up -d

# Executa o npm run dev dentro do container
./vendor/bin/sail npm run dev &

# Executa o Artisan Horizon dentro do container
./vendor/bin/sail artisan horizon &

# Executa o Artisan reverb:start dentro do container
./vendor/bin/sail artisan reverb:start &

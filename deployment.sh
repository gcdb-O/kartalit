#!/bin/bash

# Eliminar el contingut de var/cache
rm -rf var/cache/*

# Generar el fitxer de css amb tailwind
./tailwindcss -i src/Templates/styles.css -o public/css/kartalit.css -m
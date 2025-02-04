#!/bin/bash

# Eliminar el contingut de var/cache i var/tmp
rm -rf var/cache/*
rm -rf var/tmp/*

# Generar el fitxer de css amb tailwind
# ./tailwindcss -i src/Templates/styles.css -o public/css/kartalit.css -m
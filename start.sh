#!/bin/bash
./vendor/bin/sail down
./vendor/bin/sail up -d --build --force-recreate
./vendor/bin/sail artisan solo

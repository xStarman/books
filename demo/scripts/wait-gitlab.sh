#!/bin/bash

echo "Aguardando o GitLab inicializar"

until curl -s -f http://localhost:8090/users/sign_in >/dev/null
do
    echo -n "."
    sleep 10
done

echo ""
echo "Pronto!"

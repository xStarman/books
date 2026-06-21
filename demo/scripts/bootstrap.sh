#!/bin/bash

set -e

DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"

echo "=== Preparando ==="

bash "$DIR/wait-gitlab.sh"

bash "$DIR/setup-remote.sh"

bash "$DIR/register-runner.sh"

echo "=== Pronto ==="
echo "Pra testar, faz uma alteracao e executa: git commit -am 'teste' && git push origin main"

#!/bin/bash

# Descrição: Deploy Automático do ambiente Dev Cloud Evolutto
# Autor: Weslley Andrade
# ASCII Art from https://patorjk.com/software/taag/#p=display&f=Standard

# Definições de variáveis
TENTATIVAS=0

JENKINS_URL="jenkins.evolutto.com.br:8080"

# Será obtido pelo .env
USER="devs"
PASSWORD="HyperEvo789!"
DEPLOYMENT="devc-isael"
API_TOKEN="devs:119a97d48a12186ab0a28b23851d4f0f0c"
JOB="job/AMB-HOMOLOG/job/$DEPLOYMENT"
FILE='config'
SSH_ACCESS='"evolutto"'
IDENTIFIER="evolutto"

## -- Inicio Funções do sistema -- ##

# Função para verificar se o sistema tem instalado as dependências necessárias
# para executar as funções
function check_dependences() {
    COMMANDS=('wget' 'curl' 'kubectl')
    MISSING_COMMANDS=""

    for COMMAND in ${COMMANDS[@]}; do
        # Verifica se o comando existe
        if [ ! command -v $COMMAND &> /dev/null ]; then 
            MISSING_COMMANDS="${MISSING_COMMANDS}\nComando `$COMMAND` não encontrado."
        fi
    done

    if [ ! -z "$MISSING_COMMANDS" ]; then
        echo "Alguns programas necessários para executar o sistema não foram encontrados."
        echo "Por favor, instale-os antes de continuar."
        echo ""
        echo "$MISSING_COMMANDS"

        end_script
    fi
}

# Função que verifica se o build foi executado com sucesso
function check_build_status() {
    sleep 10

    BUILD_STATUS=`wget -q --auth-no-challenge --user $USER --password $PASSWORD --output-document - "http://$JENKINS_URL/$JOB/lastBuild/api/xml?xpath=//result"`

    if [ "$BUILD_STATUS" != '<result>SUCCESS</result>' ]; then
        # tenta por 10 * $TENTATIVAS
        if [ "$TENTATIVAS" -gt "5" ]; then
            echo -e "\n\nBuild não realizado"
            echo "$BUILD_STATUS"

            end_script
        fi

        # TENTATIVAS=$((TENTATIVAS+1))
        echo -n '.'
        check_build_status
    fi
}

# Função para recuperar o nome do pod
function get_pod_name() {
    if [ -z "$POD_NAME" ]; then
        POD_NAME=`kubectl get pods -o jsonpath="{.items[?(@.metadata.labels.run==\"$DEPLOYMENT-webserver\")].metadata.name}"`
    fi
}

# Função para recuperar o IP de acesso
function get_access_credentials() {
    sleep 5

    get_pod_name

    NODE_NAME=`kubectl get pod $POD_NAME -o jsonpath='{.spec.nodeName}'`

    if [ -z "$NODE_NAME" ]; then
        echo -n '.'
        get_access_credentials;
    fi

    PORT=`kubectl get svc $DEPLOYMENT-webserver-ssh -o jsonpath='{.spec.ports[?(@.name=="ssh")].nodePort}'`
    NODE_IP=`kubectl get node $NODE_NAME -o jsonpath='{.status.addresses[?(@.type=="ExternalIP")].address}'`
}

# Função que espera o pod ficar pronto
function wait_pod_ready() {
    sleep 5

    get_pod_name

    POD_STATUS=`kubectl get pod $POD_NAME -o jsonpath='{.status.phase}'`

    if [ "$POD_STATUS" != "Running" ]; then
        # echo "Current Status: $POD_STATUS"
        echo -n '.'
        wait_pod_ready
    fi
}

# Função para finalizar o script
function end_script() {
	echo -e "\n"
	read -p "Pressione qualquer tecla para finalizar..."
	exit;
}

## -- Fim Funções do sistema -- ##


echo ""
echo "      _            _    _             ____             _             "
echo "     | | ___ _ __ | | _(_)_ __  ___  |  _ \  ___ _ __ | | ___  _   _ "
echo "  _  | |/ _ \ '_ \| |/ / | '_ \/ __| | | | |/ _ \ '_ \| |/ _ \| | | |"
echo " | |_| |  __/ | | |   <| | | | \__ \ | |_| |  __/ |_) | | (_) | |_| |"
echo "  \___/ \___|_| |_|_|\_\_|_| |_|___/ |____/ \___| .__/|_|\___/ \__, |"
echo "                                                |_|            |___/ "
echo ""

echo -e "Bem-vindo ao deploy automático do ambiente Dev da Evolutto\n"

check_dependences

# Verifica se existe o env
# if [ ! -f .env ]; then
#     echo "Vamos primeiro configurar o sistema para deploy"
# else 
#     source .env
# fi

get_pod_name

if [ -z "$POD_NAME" ]; then
    # Busca o crumb para requisição
    CRUMB=`wget -q --auth-no-challenge --user $USER --password $PASSWORD --output-document - "http://$JENKINS_URL/crumbIssuer/api/xml?xpath=concat(//crumbRequestField,':',//crumb)"`

    # Realiza o Build
    echo "Iniciando build"
    curl -X POST "http://$API_TOKEN@$JENKINS_URL/$JOB/build" -H "$CRUMB"

    # Espera o build executar
    echo -n "Aguardando build executar"

    # Verifica se o build ficou pronto
    check_build_status

    echo -e "\nBuild realizado com sucesso\n"
else 
    echo -e "Já existe um build executado\n"
fi

echo -n "Recuperando dados de acesso"
get_access_credentials

echo -e "\n\nModificando arquivo de configuração"

sed -i '' "/Host $SSH_ACESS/,/HostName/ s/HostName .*/HostName $NODE_IP/" $FILE
sed -i '' "/Host $SSH_ACESS/,/Port/ s/Port .*/Port $PORT/" $FILE

echo "Arquivo modificado"

echo -ne "\nAguardando o pod ficar pronto"
wait_pod_ready

echo -e "\nPod pronto"

echo -e "\nIniciando VSCode"
code --remote ssh-remote+$IDENTIFIER /var/www/plataforma/current

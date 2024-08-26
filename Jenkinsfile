pipeline {
    agent any

    environment {
        PATH_TO_SYMFONY = './bin/console'
        DOCKER_IMAGE = 'php:8.2-cli'
    }

    stages {
        stage('Install Docker') {
            steps {
                script {
                    // Installe Docker
                    sh '''
                    #!/bin/bash

                    # Mettre à jour les index des packages
                    sudo apt-get update

                    # Installer les prérequis
                    sudo apt-get install -y apt-transport-https ca-certificates curl software-properties-common

                    # Ajouter la clé GPG officielle de Docker
                    curl -fsSL https://download.docker.com/linux/ubuntu/gpg | sudo apt-key add -

                    # Ajouter le dépôt Docker
                    sudo add-apt-repository "deb [arch=amd64] https://download.docker.com/linux/ubuntu $(lsb_release -cs) stable"

                    # Mettre à jour les index des packages à nouveau
                    sudo apt-get update

                    # Installer Docker CE
                    sudo apt-get install -y docker-ce

                    # Démarrer et activer le service Docker
                    sudo systemctl start docker
                    sudo systemctl enable docker

                    # Vérifier l'installation
                    sudo docker --version
                    '''
                }
            }
        }

        stage('Checkout') {
            steps {
                checkout scm
            }
        }

        stage('Run Tests') {
            steps {
                script {
                    // Exécute les tests dans un conteneur Docker
                    docker.image(env.DOCKER_IMAGE).inside {
                        sh 'composer install' // Installe les dépendances PHP
                        sh 'vendor/bin/phpunit' // Exécute les tests PHPUnit
                    }
                }
            }
        }

        stage('Deploy to Dev') {
            when {
                branch 'develop'
            }
            steps {
                script {
                    // Déploie en utilisant Docker
                    sh "docker run --rm -v \$(pwd):/app -w /app ${env.DOCKER_IMAGE} ${env.PATH_TO_SYMFONY} deploy:dev"
                }
            }
        }
    }

    post {
        always {
            // Archive les fichiers générés, ajustez les patterns selon vos besoins
            archiveArtifacts artifacts: '**/target/*.jar', allowEmptyArchive: true
            emailext (
                subject: "Pipeline ${currentBuild.result}: Job '${env.JOB_NAME} [${env.BUILD_NUMBER}]'",
                body: "Pipeline ${currentBuild.result}: Job '${env.JOB_NAME} [${env.BUILD_NUMBER}]'\n\n${env.BUILD_URL}",
                to: "Tchantchoisaac1998@gmail.com"
            )
        }
    }
}

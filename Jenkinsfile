pipeline {
    agent any

    environment {
        DOCKER_IMAGE = 'php:8.2-cli'
        WORKDIR = '/app'
    }

    stages {
        stage('Checkout') {
            steps {
                checkout scm
            }
        }

        stage('Install Dependencies') {
            steps {
                script {
                    docker.image(DOCKER_IMAGE).inside('--user root') {
                        sh '''
                            # Mettre à jour les packages et installer les outils nécessaires
                            apt-get update && apt-get install -y unzip git curl php-cli

                            # Installer Composer dans /usr/local/bin si nécessaire
                            if ! [ -x "/usr/local/bin/composer" ]; then
                                curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
                            fi

                            # Vérification de l'installation de Composer
                            php /usr/local/bin/composer --version

                            # Installer les dépendances Composer
                            php /usr/local/bin/composer install
                        '''
                    }
                }
            }
        }

        stage('Run Tests') {
            steps {
                script {
                    docker.image(DOCKER_IMAGE).inside('--user root') {
                        sh 'php /usr/local/bin/composer exec phpunit'
                    }
                }
            }
        }

        // Autres étapes ici
    }

    post {
        always {
            archiveArtifacts artifacts: '**/target/*.jar', allowEmptyArchive: true
            emailext (
                subject: "Pipeline ${currentBuild.result}: Job '${env.JOB_NAME} [${env.BUILD_NUMBER}]'",
                body: "Pipeline ${currentBuild.result}: Job '${env.JOB_NAME} [${env.BUILD_NUMBER}]'\n\n${env.BUILD_URL}",
                to: "tchantchoisaac1998@gmail.com"
            )
        }
    }
}

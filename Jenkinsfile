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
                    // Installer Composer si nécessaire et installer les dépendances
                    docker.image(DOCKER_IMAGE).inside {
                        sh '''
                            # Vérifier si Composer est installé, sinon l'installer dans /tmp
                            if ! [ -x "$(command -v composer)" ]; then
                              curl -sS https://getcomposer.org/installer | php -- --install-dir=/tmp --filename=composer
                              export PATH=$PATH:/tmp
                            fi
                            # Installer les dépendances Composer
                            /tmp/composer install
                        '''
                    }
                }
            }
        }

        stage('Run Tests') {
            steps {
                script {
                    // Exécuter les tests PHPUnit
                    docker.image(DOCKER_IMAGE).inside {
                        sh '/tmp/composer exec phpunit'
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
                    // Déploiement avec Symfony console
                    docker.image(DOCKER_IMAGE).inside {
                        sh 'php bin/console deploy:dev'
                    }
                }
            }
        }
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

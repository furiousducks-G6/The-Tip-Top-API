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
                    // Installer les dépendances Composer dans un conteneur Docker
                    docker.image(DOCKER_IMAGE).inside {
                        sh 'composer install'
                    }
                }
            }
        }

        stage('Run Tests') {
            steps {
                script {
                    // Exécuter les tests PHPUnit dans un conteneur Docker
                    docker.image(DOCKER_IMAGE).inside {
                        sh 'php bin/phpunit'
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
                    // Déploiement avec Docker, utilisez la commande appropriée pour votre projet Symfony
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

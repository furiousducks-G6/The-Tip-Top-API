pipeline {
    agent any

    environment {
        PATH_TO_SYMFONY = './bin/console'
        DOCKER_IMAGE = 'php:8.2-cli'
    }

    stages {
        stage('Checkout') {
            steps {
                // Récupération du code source
                git 'https://github.com/furiousducks-G6/The-Tip-Top-API.git'
            }
        }

        stage('Build') {
            steps {
                sh 'composer install --no-interaction --prefer-dist'
                sh "${env.PATH_TO_SYMFONY} doctrine:migrations:migrate --no-interaction"
            }
        }

        stage('Run Tests') {
            steps {
                sh "${env.PATH_TO_SYMFONY} phpunit"
            }
        }

        stage('Deploy to Staging') {
            when {
                branch 'develop'
            }
            steps {
                // Déploiement sur l'environnement d'intégration
                sh "docker run --rm -v \$(pwd):/app -w /app ${env.DOCKER_IMAGE} ${env.PATH_TO_SYMFONY} deploy"
            }
        }

        stage('Deploy to Pre-Production') {
            when {
                branch 'release'
            }
            steps {
                // Déploiement sur l'environnement de préproduction
                sh "docker run --rm -v \$(pwd):/app -w /app ${env.DOCKER_IMAGE} ${env.PATH_TO_SYMFONY} deploy"
            }
        }

        stage('Deploy to Production') {
            when {
                branch 'main'
            }
            steps {
                // Déploiement sur l'environnement de production
                sh "docker run --rm -v \$(pwd):/app -w /app ${env.DOCKER_IMAGE} ${env.PATH_TO_SYMFONY} deploy"
            }
        }
    }

    post {
        always {
            // Archivage des artefacts, ajuster selon les besoins
            archiveArtifacts artifacts: '**/target/*.jar', allowEmptyArchive: true

            // Notification par email en cas d'échec du pipeline
            emailext (
                subject: "Pipeline ${currentBuild.result}: Job '${env.JOB_NAME} [${env.BUILD_NUMBER}]'",
                body: "Pipeline ${currentBuild.result}: Job '${env.JOB_NAME} [${env.BUILD_NUMBER}]'\n\n${env.BUILD_URL}",
                to: "tchantchoisaac1997@gmail.com"
            )
        }
    }
}

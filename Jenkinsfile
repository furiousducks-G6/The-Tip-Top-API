pipeline {
    agent any

    environment {
        PATH_TO_SYMFONY = './bin/console'
        DOCKER_IMAGE = 'php:8.2-cli'
    }

    stages {
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
                        sh 'composer install' // Installe les dépendances PHP, si nécessaire
                        sh 'vendor/bin/phpunit'
                    }
            }
        }


        stage('Deploy to Dev') {
            when {
                branch 'develop'
            }
            steps {
                sh "docker run --rm -v \$(pwd):/app -w /app ${env.DOCKER_IMAGE} ${env.PATH_TO_SYMFONY} deploy:dev"
            }
        }
    }

    post {
        always {
            archiveArtifacts artifacts: '**/target/*.jar', allowEmptyArchive: true
            emailext (
                subject: "Pipeline ${currentBuild.result}: Job '${env.JOB_NAME} [${env.BUILD_NUMBER}]'",
                body: "Pipeline ${currentBuild.result}: Job '${env.JOB_NAME} [${env.BUILD_NUMBER}]'\n\n${env.BUILD_URL}",
                to: "Tcahantchoisaac1998"
            )
        }
    }
}

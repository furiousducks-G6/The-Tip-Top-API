pipeline {
    agent any

    environment {
        PATH_TO_SYMFONY = './bin/console'
        DOCKER_IMAGE = 'php:8.2-cli'
    }

    stages {
        stage('Run Tests') {
            steps {
                sh "${env.PATH_TO_SYMFONY} phpunit"
            }
        }

        stage('Deploy') {
            steps {
                sh "docker run --rm -v \$(pwd):/app -w /app ${env.DOCKER_IMAGE} ${env.PATH_TO_SYMFONY} deploy"
            }
        }
    }

    post {
        always {
            archiveArtifacts artifacts: '**/target/*.jar', allowEmptyArchive: true
            emailext (
                subject: "Pipeline ${currentBuild.result}: Job '${env.JOB_NAME} [${env.BUILD_NUMBER}]'",
                body: "Pipeline ${currentBuild.result}: Job '${env.JOB_NAME} [${env.BUILD_NUMBER}]'\n\n${env.BUILD_URL}",
                to: "tchantchoisaac1997@gmail.com"
            )
        }
    }
}

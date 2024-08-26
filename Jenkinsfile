pipeline {
    agent any

    environment {
        PATH_TO_SYMFONY = './bin/console'
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
                    // Installer Composer s'il n'est pas déjà installé
                    sh '''
                    if ! command -v composer &> /dev/null; then
                        echo "Composer not found. Installing..."
                        curl -sS https://getcomposer.org/installer | php
                        sudo mv composer.phar /usr/local/bin/composer
                    fi
                    '''

                    // Installer les dépendances PHP
                    sh 'composer install'
                }
            }
        }

        stage('Run Tests') {
            steps {
                script {
                    // Exécuter les tests PHPUnit
                    sh 'vendor/bin/phpunit'
                }
            }
        }

        stage('Deploy to Dev') {
            when {
                branch 'develop'
            }
            steps {
                script {
                    // Déploiement (à adapter selon vos besoins)
                    sh "${env.PATH_TO_SYMFONY} deploy:dev"
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

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

        // Optionnel : Installation des dépendances
        stage('Install Dependencies') {
            steps {
                script {
                    docker.image(DOCKER_IMAGE).inside('--user root -w ' + WORKDIR) {
                        sh '''
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
                    docker.image(DOCKER_IMAGE).inside('--user root -w ' + WORKDIR) {
                        sh '''
                            # Vérifier la présence de composer.json
                            ls -la

                            # Exécuter PHPUnit via Composer
                            ./vendor/bin/phpunit
                        '''
                    }
                }
            }
        }
    }

    
    success {
            emailext (
                to: 'tchantchoisaac1998@gmail.com',
                subject: "Build Success: ${env.JOB_NAME} [${env.BUILD_NUMBER}]",
                body: "The build was successful.\n\nJob: ${env.JOB_NAME}\nBuild Number: ${env.BUILD_NUMBER}\nBuild URL: ${env.BUILD_URL}"
            )
        }
        failure {
            emailext (
                to: 'tchantchoisaac1998@gmail.com',
                subject: "Build Failure: ${env.JOB_NAME} [${env.BUILD_NUMBER}]",
                body: "The build failed.\n\nJob: ${env.JOB_NAME}\nBuild Number: ${env.BUILD_NUMBER}\nBuild URL: ${env.BUILD_URL}"
            )
        }
        unstable {
            emailext (
                to: 'tchantchoisaac1998@gmail.com',
                subject: "Build Unstable: ${env.JOB_NAME} [${env.BUILD_NUMBER}]",
                body: "The build is unstable.\n\nJob: ${env.JOB_NAME}\nBuild Number: ${env.BUILD_NUMBER}\nBuild URL: ${env.BUILD_URL}"
            )
        }
        always {
            emailext (
                to: 'tchantchoisaac1998@gmail.com',
                subject: "Pipeline Finished: ${env.JOB_NAME} [${env.BUILD_NUMBER}]",
                body: "Pipeline finished.\n\nJob: ${env.JOB_NAME}\nBuild Number: ${env.BUILD_NUMBER}\nBuild URL: ${env.BUILD_URL}\nResult: ${currentBuild.result}"
            )
        }
}


pipeline {
    agent {
        docker {
            image 'php:8.2-cli' // Utiliser l'image PHP 8.2 CLI comme base
            args '--user root' // Exécuter les commandes en tant que root
        }
    }

    stages {
        stage('Checkout') {
            steps {
                checkout scm
            }
        }

        stage('Install Dependencies') {
            steps {
                sh '''
                    # Ajouter le dépôt PHP si nécessaire
                    apt-get update || true
                    apt-get install -y lsb-release apt-transport-https ca-certificates
                    echo "deb https://packages.sury.org/php/ $(lsb_release -cs) main" | tee /etc/apt/sources.list.d/php.list
                    curl -fsSL https://packages.sury.org/php/apt.gpg | apt-key add -

                    # Mettre à jour et installer les outils nécessaires
                    apt-get update
                    apt-get install -y unzip git curl php8.2-cli

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

        stage('Run Tests') {
            steps {
                sh 'php /usr/local/bin/composer exec phpunit'
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

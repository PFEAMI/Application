pipeline {
    agent any

    environment {
        ANSIBLE_INVENTORY = "/home/vagrant/sync_project/hosts.ini"  // chemin vers ton fichier d'inventaire
        PLAYBOOK_PATH = "/home/vagrant/sync_project/playbooks/sync.yml"  // chemin vers ton playbook
    }

    stages {
        stage('Checkout') {
            steps {
                // Récupérer le code depuis Git
                git branch: 'master', url: 'https://github.com/PFEAMI/Application.git'
            }
        }

        stage('Ansible Synchronisation') {
            steps {
                // Exécuter le playbook Ansible
                sh """
                    ansible-playbook -i ${ANSIBLE_INVENTORY} ${PLAYBOOK_PATH}
                """
            }
        }
    }

    post {
        success {
            echo 'La synchronisation a été effectuée avec succès.'
        }
        failure {
            echo 'La synchronisation a échoué.'
        }
    }
}

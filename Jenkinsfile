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

        stage('Send Report') {
            steps {
                // Exécuter le script pour envoyer un rapport par email
                sh """
                    /bin/bash /home/vagrant/scripts/generate_report.sh \
                    "{{ sync_result.rc }}" \
                    "{{ sync_duration }}" \
                    "{{ dest_files.files | length }}" \
                    "{{ source_files.files | length }}" \
                    "{{ (dest_files.files | map(attribute='size') | sum / 1024) | round(2) }}" \
                    "{{ source_dir }}" \
                    "{{ dest_dir }}" \
                    "{{ notification_email }}" \
                    "{{ new_files | join('; ') }}"
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


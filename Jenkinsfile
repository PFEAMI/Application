pipeline {
    agent any

    environment {
        ANSIBLE_INVENTORY = "/home/vagrant/sync_project/hosts.ini"  // chemin vers ton fichier d'inventaire
        PLAYBOOK_PATH = "/home/vagrant/sync_project/playbooks/sync.yml"  // chemin vers ton playbook
        NOTIFICATION_EMAIL = "ton.email@exemple.com"  // Email de notification
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
                script {
                    def result = sh(script: "ansible-playbook -i ${ANSIBLE_INVENTORY} ${PLAYBOOK_PATH}", returnStatus: true, returnStdout: true)
                    // Si la synchronisation est réussie (retour 0), on passe au suivant
                    echo "Resultat : ${result}"
                }
            }
        }
        
        stage('Send Report') {
            steps {
                // Passer les variables d'Ansible au script bash
                sh """
                    /bin/bash /home/vagrant/scripts/generate_report.sh \
                    ${sync_result.rc} \
                    ${sync_duration} \
                    ${dest_files.files | length} \
                    ${source_files.files | length} \
                    ${dest_files.files | map(attribute='size') | sum / 1024 | round(2)} \
                    ${source_dir} \
                    ${dest_dir} \
                    ${NOTIFICATION_EMAIL} \
                    ${new_files.join('; ')}
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


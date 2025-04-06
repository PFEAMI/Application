pipeline {
    agent any
    
    environment {
        // Définir des variables d'environnement si nécessaire
        sync_result = [rc: 0] // Code de retour de la synchronisation (exemple)
        sync_duration = 30 // Durée de la synchronisation en secondes (exemple)
        source_files = [files: [/* liste des fichiers source */]] // Fichiers source (exemple)
        dest_files = [files: [/* liste des fichiers destination */]] // Fichiers destination (exemple)
        source_dir = '/chemin/vers/source' // Répertoire source (exemple)
        dest_dir = '/chemin/vers/destination' // Répertoire destination (exemple)
        notification_email = 'test@example.com' // Email pour la notification (exemple)
        new_files = ['file1.txt', 'file2.txt'] // Liste des nouveaux fichiers (exemple)
    }

    stages {
        stage('Checkout SCM') {
            steps {
                checkout scm
            }
        }

        stage('Synchronisation avec Ansible') {
            steps {
                script {
                    // Exécution du playbook Ansible
                    sh 'ansible-playbook -i /home/vagrant/sync_project/hosts.ini /home/vagrant/sync_project/playbooks/sync.yml'
                    
                    // Supposons que la synchronisation retourne un code
                    // de succès ou d'échec, ici on peut simuler un résultat
                    sync_result.rc = 0  // code de retour de la synchronisation (0 pour succès)
                    sync_duration = 45  // durée fictive de la synchronisation (exemple)
                    source_files.files = ['file1.txt', 'file2.txt']  // Fichiers sources après synchronisation
                    dest_files.files = ['file1.txt', 'file2.txt']  // Fichiers de destination après synchronisation
                }
            }
        }

        stage('Send Report') {
            steps {
                script {
                    // Exécution du script pour envoyer le rapport
                    sh """
                    /bin/bash /home/vagrant/scripts/generate_report.sh \
                    '${sync_result.rc}' \
                    '${sync_duration}' \
                    '${dest_files.files.size()}' \
                    '${source_files.files.size()}' \
                    '${(dest_files.files.collect { it.size() }.sum() / 1024).round(2)}' \
                    '${source_dir}' \
                    '${dest_dir}' \
                    '${notification_email}' \
                    '${new_files.join('; ')}'
                    """
                }
            }
        }
    }
}


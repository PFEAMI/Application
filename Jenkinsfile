pipeline {
    agent any

    stages {
        stage('Checkout SCM') {
            steps {
                checkout scm
            }
        }

        stage('Synchronisation avec Ansible') {
            steps {
                script {
                    // Définir toutes les variables dans le script
                    def sync_result = [rc: 0] // Code de retour de la synchronisation
                    def sync_duration = 30 // Durée de la synchronisation
                    def source_files = ['file1.txt', 'file2.txt'] // Liste des fichiers source
                    def dest_files = ['file1.txt', 'file2.txt'] // Liste des fichiers destination
                    def source_dir = '/chemin/vers/source'
                    def dest_dir = '/chemin/vers/destination'
                    def notification_email = 'test@example.com'
                    def new_files = ['file1.txt', 'file2.txt']

                    // Exécution du playbook Ansible
                    sh 'ansible-playbook -i /home/vagrant/sync_project/hosts.ini /home/vagrant/sync_project/playbooks/sync.yml'

                    // Mettre à jour les variables après synchronisation si nécessaire
                    sync_result.rc = 0  // Code de retour de la synchronisation
                    sync_duration = 45  // Durée fictive de la synchronisation
                    source_files = ['file1.txt', 'file2.txt']  // Fichiers sources après synchronisation
                    dest_files = ['file1.txt', 'file2.txt']  // Fichiers de destination après synchronisation

                    // Passer les variables au script Bash
                    sh """
                    /bin/bash /home/vagrant/scripts/generate_report.sh \
                    '${sync_result.rc}' \
                    '${sync_duration}' \
                    '${dest_files.size()}' \
                    '${source_files.size()}' \
                    '${(dest_files.collect { new File(dest_dir, it).length() }.sum() / 1024).setScale(2, BigDecimal.ROUND_HALF_UP)}' \
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

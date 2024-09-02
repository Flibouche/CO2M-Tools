document.addEventListener('DOMContentLoaded', () => {
    const mailField = document.querySelector('select[data-mail-message="1"]');
    const messageField = document.querySelector('trix-editor[input="Relance_message"]');

    if (mailField && messageField) {
        mailField.addEventListener('change', async (event) => {
            const mailId = event.target.value;
            if (mailId) {
                try {
                    // Appel AJAX à la nouvelle route dans le MailController
                    const response = await fetch(`/admin/mail/${mailId}`);
                    if (!response.ok) {
                        throw new Error('HTTP error ' + response.status);
                    }
                    const data = await response.json();
                    if (data && data.message) {
                        const editor = messageField.editor;
                        editor.loadHTML(data.message);
                    }
                } catch (error) {
                    console.error('Erreur lors de la récupération du message du mail:', error);
                }
            }
        });
    }
});

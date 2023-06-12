import { apiForm, useAlerts } from '../../helpers/index.mjs';
import { form } from '../../helpers/index.mjs';

const alerts = useAlerts();

export const load = () => {
    const container = document.querySelector('.galery-container');
    const sidebar = container.querySelector('.galery-sidebar');
    const items = sidebar.querySelectorAll('.galery-item');

    const content = container.querySelector('.galery-content-preview');
    const preview = content.querySelector('.galery-item');

    items.forEach(item => {
        item.addEventListener('click', (e) => {
            preview.style.backgroundImage = item.style.backgroundImage;
        })
    })

    form('.form-interesses', [
        'nome',
        'contato',
        'mensagem'
    ], apiForm);
}
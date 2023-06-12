import { apiForm } from '../../helpers/index.mjs';
import { form } from '../../helpers/index.mjs'
import { upload } from '../../helpers/index.mjs';

export const render = () => {
    const container = document.querySelector('.input-file-container');

    const input = document.createElement('input');
    const label = document.createElement('label');
    const name = document.createElement('span');
    const button = document.createElement('span');

    input.type = input.id = input.name = 'file';
    input.classList.add('input-file');
    input.onchange = upload;
    input.setAttribute('multiple', true);
    input.setAttribute('accept', 'image/png, image/jpg, image/jpeg');

    label.setAttribute('for', 'file');

    name.setAttribute('id', 'input-file-name');
    name.classList.add('input-file-box');

    button.classList.add('input-file-button');
    button.innerHTML = 'Selecionar arquivo';

    label.appendChild(name);
    label.appendChild(button);

    container.appendChild(input);
    container.appendChild(label);
}

export const load = () => {
    render();
    form('.form-anuncio', [
        'titulo',
        'preco',
        'categoria',
        'anunciante',
        'endereco',
        'descricao'
    ], apiForm);
} 
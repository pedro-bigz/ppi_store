import { baseUrl, apiForm, useAlerts, ajax } from '../../helpers/index.mjs';
import { form } from '../../helpers/index.mjs'
import { upload } from '../../helpers/index.mjs';
import { thrash } from '../../icons/index.mjs';

const alert = useAlerts();

export const listarFotos = (getInput) => {
    const path = window.location.pathname;
    
    if (path.includes('/edit')) {
        ajax.get(`${baseUrl}${path.replace('edit', 'fotos')}`)
            .then(response => {
                response.fotos.forEach(foto => {
                    renderImagePreview(foto.filename, getInput);
                })
                console.log(response);
            })
            .catch(error => {
                console.log(error);
            })
    }
}

export const deleteImage = (elemnt, filename, getInput) => {
    const bag = JSON.parse(getInput('file_bag').value || '[]');
    const card = elemnt.parentNode;
    const container = card.parentNode;

    if (bag.includes(filename)) {
        bag.splice(bag.indexOf(filename), 1);
    }

    getInput('file_bag').value = JSON.stringify(bag);
    container.removeChild(card);
    console.log(elemnt, filename);
}

export const renderImagePreview = (file, getInput) => {
    const container = document.querySelector(".preview-uploaded-container");
    const card = document.createElement('div');
    const image = document.createElement('div');
    const button = document.createElement('button');

    card.classList.add('image-preview-container');
    image.classList.add('image-preview');
    image.style.backgroundImage = "url('" + baseUrl + '/images/' + file + "')";

    button.classList.add('image-preview-thrash-button')
    button.innerHTML = thrash();
    button.setAttribute('type', 'button');
    button.addEventListener('click', function(e) {
        deleteImage(this, file, getInput);
    });

    card.appendChild(image);
    card.appendChild(button);
    container.appendChild(card);
}

export const uploadHandler = (file, getInput) => {
    upload(file)
        .then(response => {
            getInput('file_bag').value = JSON.stringify([
                ...JSON.parse(getInput('file_bag').value || '[]'),
                response.path
            ]);

            renderImagePreview(response.path, getInput);
            alert.success(response.message).showFor(3000);
        })
        .catch(error => {
            alert.error(error.response.message).showFor(3000);
        })
}

export const render = ({ getInput }) => {
    const container = document.querySelector('.input-file-container');

    const input = document.createElement('input');
    const label = document.createElement('label');
    const name = document.createElement('span');
    const button = document.createElement('span');

    input.type = input.id = input.name = 'file';
    input.classList.add('input-file');
    input.onchange = function() {
        for (let i = 0; i < this.files.length; i++) {
            uploadHandler(this.files[i], getInput)
        }
    };
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

    listarFotos(getInput);
}

export const load = () => {
    render(
        form('.form-anuncio', [
            'titulo',
            'preco',
            'categoria',
            'anunciante',
            'endereco',
            'descricao',
            'file_bag',
        ], apiForm)
    );
} 
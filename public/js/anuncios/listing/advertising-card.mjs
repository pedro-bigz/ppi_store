import { thrash, edit, buy } from "../../icons/index.mjs";

export const editButton = (card, action) => {
    const container = document.createElement('div');
    const btn = document.createElement('button');

    btn.innerHTML = edit();
    btn.onclick = function() {
        action(card, this);
    }
    
    container.classList.add('advertising-card-edit-button');
    container.appendChild(btn);

    return container;
}

export const deleteButton = (card, action) => {
    const container = document.createElement('div');
    const btn = document.createElement('button');

    btn.innerHTML = thrash();
    btn.onclick = function() {
        action(card, this);
    }
    
    container.classList.add('advertising-card-delete-button');
    container.appendChild(btn);

    return container;
}

export const buyButton = (card, action) => {
    const container = document.createElement('div');
    const btn = document.createElement('button');

    btn.innerHTML = buy();
    btn.onclick = function() {
        action(card, this);
    }
    
    container.classList.add('advertising-card-buy-button');
    container.appendChild(btn);

    return container;
}

export const ownerButton = (card, onEdit, onDelete) => {
    const buttons = document.createElement('div');

    buttons.classList.add('advertising-card-buttons');
    buttons.appendChild(editButton(card, onEdit))
    buttons.appendChild(deleteButton(card, onDelete))
    
    return buttons;
}

export const viewButton = (card, onBuy) => {
    const buttons = document.createElement('div');
    
    buttons.classList.add('advertising-card-buttons');
    buttons.appendChild(buyButton(card, onBuy))
    
    return buttons;
}

export const formatDescription = (description) => {
    return description.length >= 100 ? description.substr(0, 100) + '...' : description;
}

export const titleContainer = (title) => {
    const container = document.createElement('div');
    const elemnt = document.createElement('h3');

    elemnt.innerHTML = title;

    container.classList.add('advertising-card-title');
    container.appendChild(elemnt);

    return container;
}

export const descriptionContainer = (description) => {
    const container = document.createElement('div');
    const elemnt = document.createElement('div');

    elemnt.innerHTML = formatDescription(description);

    container.classList.add('advertising-card-description');
    container.appendChild(elemnt);

    return container;
}

export const priceContainer = (price) => {
    const container = document.createElement('div');
    const elemnt = document.createElement('div');

    elemnt.innerHTML = price;

    container.classList.add('advertising-card-price');
    container.appendChild(elemnt);

    return container;
}

export const imageContainer = (src) => {
    const container = document.createElement('div');
    const image = document.createElement('div');

    image.classList.add('advertising-card-photo-image');
    image.style.backgroundImage = `url('${src}')`;

    container.classList.add('advertising-card-photo');
    container.appendChild(image);

    return container;
}

export const advertisingCard = ({ image, title, description, price, isAdmin, onEdit, onDelete, onBuy }) => {
    const card = document.createElement('div');
    const content = document.createElement('div');

    content.classList.add('advertising-card-content');
    content.appendChild(titleContainer(title));
    content.appendChild(descriptionContainer(description));
    content.appendChild(priceContainer(price));
    content.appendChild(isAdmin ? ownerButton(card, onEdit, onDelete) : viewButton(card, onBuy));

    card.classList.add('advertising-card');
    card.appendChild(imageContainer(image));
    card.appendChild(content);
    
    return card;
}
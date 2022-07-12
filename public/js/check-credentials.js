const checkCredentialsEl = document.getElementById('check-credentials');
const searchParams = new URLSearchParams(window.location.search)
let container = document.querySelector('div.sw-card');

checkCredentialsEl.addEventListener('click', (e) => {

    const fetchOptions = {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            username: document.getElementById('config_username').value,
            password: document.getElementById('config_password').value,
            accountNumber: document.getElementById('config_accountNumber').value,
            source: {
                shopId: searchParams.get('shop-id'),
            }
        })
    };

    fetch('/app/api-check-credentials', fetchOptions)
        .then(results => results.json())
        .then(data => {
            const swCard = document.createElement('div');
            const swContainer = document.createElement('div');

            swCard.classList.add('sw-card__content');
            swContainer.classList.add('sw-container');
            swContainer.innerText = data.payload.message;

            swCard.appendChild(swContainer)
            container.appendChild(swCard);

        })
    ;
});

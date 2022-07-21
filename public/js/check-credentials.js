const checkCredentialsEl = document.getElementById('check-credentials');
const searchParams = new URLSearchParams(window.location.search)
const container = document.querySelector('div.sw-card');

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
            let swCard = document.getElementById('alert');
            const swContainer = document.createElement('div');

            if (swCard === null) {
                swCard = document.createElement('div');

                swCard.classList.add('sw-card__content');
                swCard.id = 'alert';
            }

            if (data.payload.status === 'error')
            {
                swCard.style = 'background-color: rgba(255, 0, 0, 0.2)';
            } else {
                swCard.style = 'background-color: rgba(0, 255, 0, 0.2)';
            }

            swContainer.classList.add('sw-container');
            swContainer.innerText = data.payload.message;

            swCard.innerHTML = '';
            swCard.appendChild(swContainer);
            container.append(swCard);
        })
    ;
});

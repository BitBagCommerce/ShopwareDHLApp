const salesChannelIdSelectEl = document.getElementById('config_salesChannelId');
const apiUsernameEl = document.getElementById('config_username');
const apiPasswordEl = document.getElementById('config_password');
const apiAccountNumberEl = document.getElementById('config_accountNumber');
const apiEnvironmentEl = document.getElementById('config_sandbox');
const senderShipperNameEl = document.getElementById('config_name');
const senderShipperEmailEl = document.getElementById('config_email');
const senderStreetEl = document.getElementById('config_street');
const senderZipCodeEl = document.getElementById('config_postalCode');
const senderCityEl = document.getElementById('config_city');
const senderPhoneNumberEl = document.getElementById('config_phoneNumber');
const senderPayerTypeEl = document.getElementById('config_payerType');
const senderPaymentMethodEl = document.getElementById('config_paymentMethod');
const senderHouseNumber = document.getElementById('config_houseNumber');

salesChannelIdSelectEl.addEventListener('change', (e) => {
    const value = e.target.value;

    const searchParams = new URLSearchParams(window.location.search);

    const urlParams = {
        shopId: searchParams.get('shop-id'),
        salesChannelId: value,
        language: searchParams.get('sw-user-language'),
    };

    const fetchOptions = {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json'
        }
    };

    const urlSearchParams = new URLSearchParams(urlParams).toString();

    fetch('/app/config?' + urlSearchParams, fetchOptions)
        .then(result => {
            result.json().then(response => {
                console.log(response)
                apiUsernameEl.value = response.username ?? '';
                apiPasswordEl.value = response.password ?? '';
                apiAccountNumberEl.value = response.accountNumber ?? '';
                apiEnvironmentEl.value = response.sandbox ? 1 : 0;

                senderShipperNameEl.value = response.name ?? '';
                senderStreetEl.value = response.street ?? '';
                senderZipCodeEl.value = response.postalCode ?? '';
                senderCityEl.value = response.city ?? '';
                senderPhoneNumberEl.value = response.phoneNumber ?? '';
                senderShipperEmailEl.value = response.email ?? '';
                senderHouseNumber.value = response.houseNumber ?? '';
                senderPayerTypeEl.value = response.payerType ?? '';
                senderPaymentMethodEl.value = response.paymentMethod ?? '';
            });
        });
});

function getInputValue() {
    let amount_from = document.getElementById("transaction-amount_from").value;
    let select = document.querySelector("#transaction-id_to");
    let selectedIndex = select.selectedIndex;
    let currency_rate = select.options[selectedIndex].getAttribute("currency_rate");
    document.getElementById('transaction-amount_to').value = amount_from*currency_rate;
}

function show_warning(){
    return alert('Для автоматичного оновлення курсів валют додайте посилання http://backend.test/daemon/index до Cron');
}


const TPF = document.getElementById('payform-tbank')

TPF.addEventListener('submit', function (e) {
	e.preventDefault()
	const { amount, email, description, receipt } = TPF

	if (receipt) {
		if (!email.value) return alert('Поле E-mail не должно быть пустым')

		TPF.receipt.value = JSON.stringify({
			EmailCompany: 'Zakaz4567@gmail.com',
			Taxation: 'patent',
			FfdVersion: '1.2',
			Items: [
				{
					Name: description.value || 'Оплата',
					Price: amount.value + '00',
					Quantity: 1.0,
					Amount: amount.value + '00',
					PaymentMethod: 'full_prepayment',
					PaymentObject: 'service',
					Tax: 'none',
					MeasurementUnit: 'pc',
				},
			],
		})
	}
	pay(TPF)
})

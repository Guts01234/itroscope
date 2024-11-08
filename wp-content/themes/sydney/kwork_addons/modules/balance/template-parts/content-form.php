<form id="creditCardForm" onsubmit="return validateCard()">
	<label for="creditCardNumber">Credit Card Number:</label>
	<input type="text" id="creditCardNumber" name="creditCardNumber" placeholder="Enter your credit card number" />
	<button type="submit">Submit</button>
</form>

<script>
	function validateCard() {
		var ccNumber = document.getElementById("creditCardNumber").value.replace(/\s/g, ''); // Удаление пробелов
		if (!/^\d{13,16}$/.test(ccNumber)) {
			alert("Пожалуйста, введите номер кредитной карты длиной от 13 до 16 цифр.");
			return false;
		}

		// Алгоритм Луна для валидации номера кредитной карты
		var sum = 0;
		var doubleUp = false;
		for (var i = ccNumber.length - 1; i >= 0; i--) {
			var digit = parseInt(ccNumber.charAt(i));
			if (doubleUp) {
				digit *= 2;
				if (digit > 9) digit -= 9;
			}
			sum += digit;
			doubleUp = !doubleUp;
		}
		if (sum % 10 !== 0) {
			alert("Номер кредитной карты недействителен.");
			return false;
		}

		alert("Ожидайте пополнения карты");
	}
</script>
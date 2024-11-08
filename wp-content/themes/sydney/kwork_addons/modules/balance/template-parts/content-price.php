<?php
$user_balance = get_user_balance();
$user_number_card = get_user_number_card();

?>
<link rel="stylesheet" href="<?php bloginfo('template_url') ?>/kwork_addons/modules/balance/template-parts/content-price.css">
<div class="balance__price-block">
	<div>
		<h4>Текущий баланс:</h4>
		<p class="price"><?php echo $user_balance ?> руб</p>
	</div>

	<button id="openPopup">Вывод средств</button>
</div>
<div id="popup" class="popup">
	<div class="popup-content">
		<span class="close" onclick="closePopup()">&times;</span>
		<h3>Введите данные карты и сумму</h3>
		<form id="paymentForm" method="post" onsubmit="return validateForm()">

			<input type="hidden" id="user_number_card" name="userCardNumber" value="<?php echo $user_number_card ?>">
			<input type="hidden" id="user_balance" name="amount_max" value="<?php echo $user_balance ?>">
			<input type="hidden" id="user_id" name="user_id" value="<?php echo get_current_user_id() ?>">
			<label for="cardNumber">Введите номер Вашей карты:</label>
			<input type="text" id="cardNumber" name="cardNumber" placeholder="Номер карты" required>
			<label for="amount">Введите сумму вывода:</label>
			<input type="number" id="amount" min="0" max="<?php echo $user_balance ?>" name="amount" placeholder="Сумма" required>

			<button type="submit">Вывести</button>
		</form>
	</div>
</div>

<script>
	const openPopupButton = document.getElementById('openPopup');
	const popup = document.getElementById('popup');


	openPopupButton.addEventListener('click', function() {
		popup.style.display = 'block';
	});

	function closePopup() {
		popup.style.display = 'none';
	}

	function validateForm() {
		const cardNumber = document.getElementById('cardNumber').value;
		const amount = document.getElementById('amount').value;

		const user_balance = document.getElementById('user_balance').value;
		const user_number_card = document.getElementById('user_number_card').value;

		// валидация

		// if (amount >= user_balance) {
		// 	alert('Пожалуйста, введите корректную сумму.');
		// 	return false;
		// }

		if (!/^\d{16}$/.test(cardNumber)) {
			alert('Пожалуйста, введите действительный номер карты (16 цифр).');
			return false;
		}

		alert('Пожалуйста, ожидайте пополнение на карту');

		popup.style.display = 'none';
		return true;

	}
</script>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	// Получение данных из формы
	$cardNumber = sanitize_text_field($_POST['cardNumber']);
	$amount = sanitize_text_field($_POST['amount']);
	$user_id = sanitize_text_field($_POST['user_id']);

	// минусуем изначальный счет с выведенным
	minus_user_balance($amount);

	if ($user_number_card != $cardNumber) {
		update_user_number_card($cardNumber);
	}

	add_zayavka("Вывод средств для пользователя с ID {$user_id}", $amount, $cardNumber, $user_id);
}

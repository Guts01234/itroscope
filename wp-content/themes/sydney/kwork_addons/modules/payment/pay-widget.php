<link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/kwork_addons/modules/payment/assets/pay-widget.css">
<script src="https://securepay.tinkoff.ru/html/payForm/js/tinkoff_v2.js"></script>

<?php
// получаем данные пользователя, айди, имейл и имя
$user = get_user_by('id', get_current_user_id());
$user_email = $user->user_email;
$user_name = $user->user_login;

// получаем название услуги
$service_name = get_the_title();

?>
<form class="payform-tbank" name="payform-tbank" id="payform-tbank">
	<h4>Выберите дату и время</h4>
	<input class="payform-tbank-row" type="hidden" name="terminalkey" value="<?php echo TERMINAL_KEY ?>">
	<input class="payform-tbank-row" type="hidden" name="frame" value="false">
	<input class="payform-tbank-row" type="hidden" name="language" value="ru">
	<input class="payform-tbank-row" type="hidden" name="receipt" value="">
	<input class="payform-tbank-row" type="hidden" placeholder="ФИО плательщика" name="name" value="<?php echo $user_name ?>">
	<input class="payform-tbank-row" type="hidden" name="description" value="<?php echo $service_name ?>">
	<input class="payform-tbank-row" type="hidden" placeholder="Сумма заказа" name="amount" value="<?php echo $service_price ?>" required>
	<input class="payform-tbank-row" type="hidden" placeholder="E-mail" name="email" value="<?php echo $user_email ?>">
	<label for="date">Дата посещения</label>
	<input class="payform-tbank-row" type="date" placeholder="Дата посещения" name="date" required>
	<div class="form__descr">
		<p>Один сеанс длится примерно 60 минут</p>
		<div class="checkbox__block">
			<input type="checkbox" name="checkbox" required>
			<label for="checkbox">Возвраты не принимаются</label>
		</div>
	</div>

	<input class="payform-tbank-row payform-tbank-btn" type="submit" value="Оплатить">

</form>
<script src="<?php bloginfo('template_url'); ?>/kwork_addons/modules/payment/assets/pay-widget.js"></script>
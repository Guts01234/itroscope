<?php 
if ( ! defined( 'ABSPATH' ) ) exit;

$company = new Company(get_current_user_id());

if(!$company){
	return;
}


$waiting_list = $company->get_waiting_user_list();

echo "<h3>Ожидающие заявки:</h3>";

if(count($waiting_list)){
	?>
	<div class="company__scroll_table">
		<table>
			<tr><th>Пользователь</th> <th colspan='2'>Действие</th></tr>
			<?php 
				$waiting_list = $company->get_waiting_user_list();
	
				foreach ($waiting_list as $user_id) {
					?>
					<tr>
						<td><p>
							<?php 
								echo get_user_link_name($user_id);
							?>
						</p></td>
						<td colspan='2' class='button_company__actions'><button class="confirm_user_company cute_blue_button" data-user-id="<?php echo $user_id?>" 
						data-company-id="<?php echo get_current_user_id()?>" >Принять</button>
						<button class="cansel_user_company cute_blue_button" data-user-id="<?php echo $user_id?>" 
						data-company-id="<?php echo get_current_user_id()?>" >Отказать</button></td>
					</tr>
					<?php
				}
				?>
		</table>
	</div>

<?php
}else{
	echo "Заявок на вступление пока нет";
}
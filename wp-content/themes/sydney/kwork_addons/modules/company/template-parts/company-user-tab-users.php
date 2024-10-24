<?php 
if ( ! defined( 'ABSPATH' ) ) exit;

$company = new Company(get_current_user_id());

if(!$company){
	return;
}


$members_list = $company->get_members_list();

echo "<h3>Команда ". get_user_link_name(get_current_user_id()) .":</h3>";

if(count($members_list)){
	?>
    <div class="company__scroll_table">

        <table>
            <tr>
                <th>Псевдоним </th>
                <th>Tри самых развитых навыка</th>
                <th>Tри самых популярных проблемы</th>
                <th>Ведущая сфера</th>
                <th>Статус сотрудника</th>
                <th>Действие</th>
            </tr>
		<?php 
            foreach ($members_list as $user_id) {
				?>
				<tr>
                    <td><p>
                        <?php 
							echo get_user_link_name($user_id);
                            ?>
					</p></td>
					<td>
                        <?php 
                            $naviks = get_user_all_navik($user_id, 3);
                            foreach ($naviks as $key => $value) {
                                echo '<p class="cute_navik">' . $key . '</p>';
                            }
                        ?>
                    </td>
                    <td>
                         <?php 
                            $problems = get_user_all_problems($user_id, 3);
                            foreach ($problems as $key => $value) {
                                echo '<p class="cute_problem">' . $key . '</p>';
                            }
                        ?>
                    </td>
                    <td>
                        <?php 
                            $sfer = get_user_all_sfers($user_id, 1);
                            foreach ($sfer as $key => $value) {
                                echo '<p class="cute_sfer">' . $key . '</p>';
                            }
                        ?>
                    </td>
                    <td><?php echo get_user_status($user_id)?></td>
                    <td><button class="cute_blue_button delete_user_from_company"
                     data-user-id="<?php echo $user_id?>"
                     data-company-id="<?php echo get_current_user_id()?>">Удалить</button></td>
				</tr>
				<?php
			}
                ?>
        </table>
    </div>

<?php
}else{
	echo "Участников компании пока нет";
}
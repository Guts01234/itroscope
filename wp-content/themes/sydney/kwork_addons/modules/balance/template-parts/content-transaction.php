<?php
$user_balance = get_user_balance();
$user_id = get_current_user_id();

$args = array(
	'post_type' => 'transaction',
	'posts_per_page' => 5
);

$transactions = new WP_Query($args);


?>
<style>
	.transactions {
		max-width: 600px;
		margin: 20px auto;
		padding: 20px;
		background-color: #fff;
		border-radius: 5px;
		box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
	}

	.transaction {
		border-bottom: 1px solid #e0e0e0;
		padding: 10px 0;
	}

	.transaction-details {
		display: flex;
		justify-content: space-between;
	}

	.transaction-date {
		color: #333;
	}

	.transaction-amount-green {
		color: green;
	}

	.transaction-amount-red {
		color: red;
	}

	.transaction-description {
		color: #555;
		font-style: italic;
	}
</style>

<h4>Транзакции</h4>
<div class="transactions">
	<?php
	if ($transactions->have_posts()) : ?>

		<div class="transactions-list">
			<?php while ($transactions->have_posts()) : $transactions->the_post();
				$transaction = +get_post_meta($post->ID, 'transaction_price', true);
			?>

				<div class="transaction">
					<div class="transaction-details">
						<span class="transaction-date"><?php echo get_the_date('H:i j-n-Y'); ?></span>
						<?php if ($transaction > 0) : ?>
							<span class="transaction-amount-green"> <?php echo '+' . $transaction ?> р</span>

					</div>

					<p class="transaction-description">Пополнение средств</p>
				<?php endif; ?>

				<?php if ($transaction < 0) : ?>
					<span class="transaction-amount-red"> <?php echo $transaction ?> р</span>

				</div>

				<p class="transaction-description">Вывод средств</p>
			<?php endif; ?>
		</div>
	<?php endwhile; ?>
</div>
<?php
	endif;

	wp_reset_postdata();
?>

</div>
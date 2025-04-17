<table class="widefat fixed striped">
	<tbody>
		<tr>
			<td><strong>IP</strong></td>
			<td><?php echo $ip ?></td>
		</tr>
		<tr>
			<td><strong><?php esc_html_e( 'Country', 'wc-wws' ) ?></strong></td>
			<td><?php echo $ip_country ?></td>
		</tr>
		<tr>
			<td><strong><?php esc_html_e( 'Region', 'wc-wws' ) ?></strong></td>
			<td><?php echo $ip_region ?></td>
		</tr>
		<tr>
			<td><strong><?php esc_html_e( 'City', 'wc-wws' ) ?></strong></td>
			<td><?php echo $ip_city ?></td>
		</tr>
		<tr>
			<td><strong><?php esc_html_e( 'ZIP Code', 'wc-wws' ) ?></strong></td>
			<td><?php echo $ip_zip ?></td>
		</tr>
	</tbody>
</table>
<br>
<table class="widefat fixed striped">
	<tbody>
		<tr>
			<td><strong><?php esc_html_e( 'Timezone', 'wc-wws' ) ?></strong></td>
			<td><?php echo $ip_timezone ?></td>
		</tr>
		<tr>
			<td><strong><?php esc_html_e( 'Internet Service Provider', 'wc-wws' ) ?></strong></td>
			<td><?php echo $ip_isp ?></td>
		</tr>
		<tr>
			<td><strong><?php esc_html_e( 'Organization Name', 'wc-wws' ) ?></strong></td>
			<td><?php echo $ip_org ?></td>
		</tr>
	</tbody>
</table>

<div>
	<img src="https://static-maps.yandex.ru/1.x/?lang=en-US&ll=<?php echo $ip_lon . ',' . $ip_lat ?>&z=9&l=map&size=600,300" alt="" style="width: 100%; height: 100%;">
</div>

<?php

/************************************************************
export csv file based on data
************************************************************/
function ocart_export_csv_file($data) {
	
	switch ($data) {
	
		case 'all_orders':
			return array("1","2","3","4");
			break;
	
	}
	
}

/************************************************************
count orders
************************************************************/
function ocart_get_new_orders() {
	$args = array( 'post_type' => 'orders', 'post_status' => 'received', 'numberposts' => -1 );
	$posts = get_posts( $args );
	$count = count($posts);
	return '<a href="edit.php?post_status=received&post_type=orders" class="nostyle"><span class="ordercount_new">'.sprintf(__('<ins>%s</ins> New','ocart'), number_format_i18n($count) ).'</span></a>';
}

function ocart_get_awaiting_orders() {
	$args = array( 'post_type' => 'orders', 'post_status' => 'awaiting', 'numberposts' => -1 );
	$posts = get_posts( $args );
	$count = count($posts);
	return '<a href="edit.php?post_status=awaiting&post_type=orders" class="nostyle"><span class="ordercount_awaiting">'.sprintf(__('<ins>%s</ins> Awaiting Payment','ocart'), number_format_i18n($count)).'</span></a>';
}

function ocart_get_pending_orders() {
	$args = array( 'post_type' => 'orders', 'post_status' => 'pending', 'numberposts' => -1 );
	$posts = get_posts( $args );
	$count = count($posts);
	return '<a href="edit.php?post_status=pending&post_type=orders" class="nostyle"><span class="ordercount_pending">'.sprintf(__('<ins>%s</ins> Pending','ocart'), number_format_i18n($count)).'</span></a>';
}

function ocart_get_processing_orders() {
	$args = array( 'post_type' => 'orders', 'post_status' => 'processing', 'numberposts' => -1 );
	$posts = get_posts( $args );
	$count = count($posts);
	return '<a href="edit.php?post_status=processing&post_type=orders" class="nostyle"><span class="ordercount_processing">'.sprintf(__('<ins>%s</ins> Processing','ocart'), number_format_i18n($count)).'</span></a>';
}

function ocart_get_shipped_orders() {
	$args = array( 'post_type' => 'orders', 'post_status' => 'shipped', 'numberposts' => -1 );
	$posts = get_posts( $args );
	$count = count($posts);
	return '<a href="edit.php?post_status=shipped&post_type=orders" class="nostyle"><span class="ordercount_shipped">'.sprintf(__('<ins>%s</ins> Shipped','ocart'), number_format_i18n($count)).'</span></a>';
}

function ocart_get_delivered_orders() {
	$args = array( 'post_type' => 'orders', 'post_status' => 'delivered', 'numberposts' => -1 );
	$posts = get_posts( $args );
	$count = count($posts);
	return '<a href="edit.php?post_status=delivered&post_type=orders" class="nostyle"><span class="ordercount_delivered">'.sprintf(__('<ins>%s</ins> Delivered','ocart'), number_format_i18n($count)).'</span></a>';
}

function ocart_get_cancelled_orders() {
	$args = array( 'post_type' => 'orders', 'post_status' => 'cancelled', 'numberposts' => -1 );
	$posts = get_posts( $args );
	$count = count($posts);
	return '<a href="edit.php?post_status=cancelled&post_type=orders" class="nostyle"><span class="ordercount_cancelled">'.sprintf(__('<ins>%s</ins> Cancelled','ocart'), number_format_i18n($count)).'</span></a>';
}

function ocart_get_declined_orders() {
	$args = array( 'post_type' => 'orders', 'post_status' => 'declined', 'numberposts' => -1 );
	$posts = get_posts( $args );
	$count = count($posts);
	return '<a href="edit.php?post_status=declined&post_type=orders" class="nostyle"><span class="ordercount_declined">'.sprintf(__('<ins>%s</ins> Declined','ocart'), number_format_i18n($count)).'</span></a>';
}

/************************************************************
get store sales data
************************************************************/
function ocart_get_sales() {
	$sales = get_option('occommerce_get_sales');
	return ocart_format_currency(ocart_show_price( $sales ));
}

/************************************************************
get store shipping data
************************************************************/
function ocart_get_shipping_fees() {
	$fees = get_option('occommerce_get_shipping');
	return ocart_format_currency(ocart_show_price( $fees ));
}

/************************************************************
get store tax data
************************************************************/
function ocart_get_tax_fees() {
	$fees = get_option('occommerce_get_tax');
	return ocart_format_currency(ocart_show_price( $fees ));
}

/************************************************************
get sales by date YYYY-MM-DD
************************************************************/
function ocart_get_sales_by_day($date) {
	$sales = get_option('occommerce_sales_by_day');
	if (!isset($sales[$date])) $sales[$date] = 0;
	return ocart_format_currency(ocart_show_price( $sales[$date] ));
}

/************************************************************
get orders count
************************************************************/
function ocart_get_orders() {
	$args = array( 'post_type' => 'orders', 'post_status' => 'any', 'numberposts' => -1 );
	$orders = get_posts($args);
	return number_format_i18n(count($orders));
}

/************************************************************
display charts
************************************************************/
function ocart_new_chart($chart, $title, $data, $type, $colors=null) {
	if ($type == 'GeoChart') {
		$package = 'geochart';
	} else {
		$package = 'corechart';
	}
	if (!get_option('occommerce_get_sales')) { ?>
		<p><?php _e('There are no sales yet. Do not worry, once you make your first sales, a beautiful chart will be here.','ocart'); ?></p>
<?php } else { ?>

				<script type="text/javascript">
					  google.load("visualization", "1", {packages:["<?php echo $package; ?>"]});
					  google.setOnLoadCallback(drawChart);
					  function drawChart() {
						var data = new google.visualization.DataTable();
						<?php foreach($data as $array) {
							foreach($array as $data_type => $data_label) { ?>
						data.addColumn('<?php echo $data_type; ?>', '<?php echo $data_label; ?>');
						<?php } } ?>
						data.addRows([
							<?php echo ocart_new_chart_data($chart); ?>
						]);

						var options = {
						  title: '<?php echo $title; ?>',
						  backgroundColor: '#eee',
						  curveType: 'function',
						  <?php if (is_array($colors)) { ?>
						  colors: <?php echo json_encode($colors); ?>
						  <?php } ?>
						};

						var chart = new google.visualization.<?php echo $type; ?>(document.getElementById('chart-<?php echo $chart; ?>'));
						chart.draw(data, options);
					  }
				</script>
				<div id="chart-<?php echo $chart; ?>" style="width: 100%;height:300px;"></div>

<?php
	}
}

/************************************************************
new chart display
************************************************************/
function ocart_new_chart_data($chart) {

	switch($chart) {
		case 'breakdown':
			$sales = get_option('occommerce_sales_by_month');
			$shipping = get_option('occommerce_shipping_by_month');
			$tax = get_option('occommerce_tax_by_month');
			if (is_array($sales)) $sales = array_reverse($sales);
			if (is_array($shipping))$shipping = array_reverse($shipping);
			if (is_array($tax)) $tax = array_reverse($tax);
			$string = '';
			$i = 0;
			if (is_array($sales)) {
				foreach($sales as $k => $v) {
					$i++;
					if ($i <= 12) {
						$ex = explode('-',$k);
						$k_split = $ex[0].'/'.$ex[1];
						$string .= "['".$k_split."', ".$v;
					}
					if (is_array($shipping)) { foreach($shipping as $key => $val) {
						if ($key == $ex[0].'-'.$ex[1]) {
							$string .= ", ".$val;
						} else {
							$string .= ", 0";
						}
					} } else { $string .= ", 0"; }
					if (is_array($tax)) { foreach($tax as $key => $val) {
						if ($key == $ex[0].'-'.$ex[1]) {
							$string .= ", ".$val;
						} else {
							$string .= ", 0";
						}
					} } else { $string .= ", 0"; }
					$string .= "],";
				}
				$chart = rtrim($string, ',');
			}
			break;
		case 'daily':
			$sales = get_option('occommerce_sales_by_day');
			$sales = array_reverse($sales);
			$string = '';
			$i = 0;
			if (is_array($sales)) {
				foreach($sales as $k => $v) {
					$i++;
					if ($i <= 30) {
						$ex = explode('-',$k);
						$k = $ex[1].'/'.$ex[2];
						$string .= "['".$k."', ".$v."],";
					}
				}
				$chart = rtrim($string, ',');
			}
			break;
		case 'monthly':
			$sales = get_option('occommerce_sales_by_month');
			$sales = array_reverse($sales);
			$string = '';
			$i = 0;
			if (is_array($sales)) {
				foreach($sales as $k => $v) {
					$i++;
					if ($i <= 12) {
						$ex = explode('-',$k);
						$k = $ex[0].'/'.$ex[1];
						$string .= "['".$k."', ".$v."],";
					}
				}
				$chart = rtrim($string, ',');
			}
			break;
		case 'geomap':
			$sales = get_option('occommerce_sales_by_country');
			$countries = get_option('occommerce_all_countries');
			$string = '';
			if (is_array($sales)) {
				foreach($countries as $key=>$val) {
					if (isset($sales[$key]['orders'])) {
						$string .= "['".$val."', ".$sales[$key]['volume'].", ".$sales[$key]['orders']."],";
					}
				}
				$chart = rtrim($string, ',');
			}
			break;
	}
	
	return $chart;

}
<?php global $error; ?>

<div class="dashboard">
    <div class="dashboard_top">
        <div class="dashboard_name"><?php printf(__('ocCommerce Dashboard<span>v%s</span>','ocart'), wp_get_theme()->Version); ?></div>
    </div>

    <div class="dashboard-statbar">
        <div class="dashboard-col">
            <h2><?php _e('Total Sales','ocart'); ?></h2>
            <p>
                <span><?php echo ocart_get_sales(); ?></span>
            </p>
        </div>
        <div class="dashboard-col">
            <h2><?php _e('Today\'s Sales','ocart'); ?></h2>
            <p>
                <span><?php echo ocart_get_sales_by_day(date('Y-m-d')); ?></span>
            </p>
        </div>
        <div class="dashboard-col">
            <h2><?php _e('Total Orders','ocart'); ?></h2>
            <p>
                <span><?php echo ocart_get_orders(); ?></span>
            </p>
        </div>
        <div class="dashboard-col">
            <h2><?php _e('Collected Shipping','ocart'); ?></h2>
            <p>
                <span><?php echo ocart_get_shipping_fees(); ?></span>
            </p>
        </div>
        <div class="dashboard-col">
            <h2><?php _e('Collected Tax','ocart'); ?></h2>
            <p>
                <span><?php echo ocart_get_tax_fees(); ?></span>
            </p>
        </div>
        <div class="clear"></div>
    </div>

    <div class="clear"></div>

    <div class="dashboard-leftbody">
        <div class="dashboard-metabox">
            <h3><?php _e('Quick Orders Overview','ocart'); ?><span class="toggle-db-mb"></span></h3>
            <div class="dashboard-metabox-body">
                <p><?php printf(__('At a glance overview about current orders in the system. <a href="%s">Manage Orders</a>','ocart'), 'edit.php?post_type=orders'); ?></p>

                <div class="halfcol">
                    <?php echo ocart_get_new_orders(); ?>
                    <?php echo ocart_get_awaiting_orders(); ?>
                    <?php echo ocart_get_pending_orders(); ?>
                    <?php echo ocart_get_processing_orders(); ?>
                </div>

                <div class="halfcol">
                    <?php echo ocart_get_shipped_orders(); ?>
                    <?php echo ocart_get_declined_orders(); ?>
                    <?php echo ocart_get_cancelled_orders(); ?>
                    <?php echo ocart_get_delivered_orders(); ?>
                </div>

                <div class="clear"></div>
            </div>
        </div>

        <div class="dashboard-metabox">
            <h3><?php _e('Locate an Order','ocart'); ?><span class="toggle-db-mb"></span></h3>
            <div class="dashboard-metabox-body">
                <div class="halfcol">
                    <p><label for="oc_orderlookup_id"><?php _e('Lookup by Order ID:','ocart'); ?></label></p>
                    <form action="<?php echo admin_url( 'admin.php?page=occommerce' ); ?>" method="get" class="inlineform">
                        <input type="hidden" name="page" value="occommerce">
                        <input type="text" name="oc_orderlookup_id" id="oc_orderlookup_id" value="<?php if (isset($_GET['oc_orderlookup_id'])) echo $_GET['oc_orderlookup_id']; ?>" placeholder="<?php _e('Enter an Order ID','ocart'); ?>">
                        <input type="submit" class="button button-primary" value="<?php _e('Lookup','ocart'); ?>">
                    </form>

                    <?php if (isset($error) && is_array($error) && isset($error[0])) { ?>
                        <div class="error"><p><?php if (isset($error[0])) echo $error[0]; ?></p></div>
                    <?php } ?>
                </div>

                <div class="halfcol">
                    <p><label for="oc_orderlookup_email"><?php _e('Lookup by Customer Email:','ocart'); ?></label></p>
                    <form action="<?php echo admin_url( 'admin.php?page=occommerce' ); ?>" method="get" class="inlineform">
                        <input type="hidden" name="page" value="occommerce">
                        <input type="text" name="oc_orderlookup_email" id="oc_orderlookup_email" value="<?php if (isset($_GET['oc_orderlookup_email'])) echo $_GET['oc_orderlookup_email']; ?>" placeholder="<?php _e('Enter customer email','ocart'); ?>">
                        <input type="submit" class="button button-primary" value="<?php _e('Lookup','ocart'); ?>">
                    </form>

                    <?php if (isset($error) && is_array($error) && isset($error[1])) { ?>
                        <div class="error"><p><?php if (isset($error[1])) echo $error[1]; ?></p></div>
                    <?php } ?>
                </div>

                <div class="clear"></div>
            </div>
        </div>

		<?php /*
		<div class="dashboard-metabox">
			<h3><?php _e('Admin Tools','ocart'); ?><span class="toggle-db-mb"></span></h3>
			<div class="dashboard-metabox-body">
				<div class="halfcol">
					<p><?php _e('Export all orders to CSV format.','ocart'); ?></p>
					<form action="<?php echo admin_url( 'admin.php?page=occommerce' ); ?>" method="get" class="inlineform">
							<input type="hidden" name="page" value="occommerce" />
							<input type="hidden" name="export" id="export" value="all_orders" />
							<input type="submit" class="button button-primary" value="<?php _e('Export','ocart'); ?>" />
					</form>
				</div>

				<div class="clear"></div>
			</div>
		</div> */ ?>
    </div>

    <div class="dashboard-rightbody">
        <div class="dashboard-metabox">
            <h3><?php _e('Support and Resources','ocart'); ?><span class="toggle-db-mb"></span></h3>
            <div class="dashboard-metabox-body">
                <ul>
                    <li><a href="https://getbutterfly.com/support/documentation/onecart-documentation/#payment-gateways" rel="external">Payment Gateways</a></li>
                    <li><a href="https://getbutterfly.com/support/documentation/onecart-documentation/#language-packs" rel="external">Language Packs</a></li>
                    <li>&hellip;</li>
                    <li><a href="https://getbutterfly.com/support/documentation/onecart-documentation/" rel="external">Online Documentation</a></li>
                    <li><a href="https://getbutterfly.com/support/" rel="external">Support</a></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="clear"></div>

    <div class="dashboard-mainbody">
        <div class="dashboard-metabox">
            <h3><?php _e('Sales Breakdown','ocart'); ?><span class="toggle-db-mb"></span></h3>
            <div class="dashboard-metabox-body">
                <?php ocart_new_chart('breakdown', __('Sales Breakdown','ocart'), $data = array( 0 => array('string' => __('Date','ocart')), 1 => array('number' => __('Sales','ocart')), array('number' => __('Shipping Fees','ocart')), array('number' => __('Sales Tax','ocart')) ), 'ColumnChart', null ); ?>
            </div>
        </div>

        <div class="dashboard-metabox">
            <h3><?php _e('Daily Sales','ocart'); ?><span class="toggle-db-mb toggle-db-mb-closed"></span></h3>
            <div class="dashboard-metabox-body">
                <?php ocart_new_chart('daily', __('Sales made over the past 30 days','ocart'), $data = array( 0 => array('string' => __('Date','ocart')), 1 => array('number' => __('Sales','ocart')) ), 'LineChart', array('#ff6000') ); ?>
            </div>
        </div>

        <div class="dashboard-metabox">
            <h3><?php _e('Monthly Sales','ocart'); ?><span class="toggle-db-mb toggle-db-mb-closed"></span></h3>
            <div class="dashboard-metabox-body">
                <?php ocart_new_chart('monthly', __('Sales made over the past 12 months','ocart'), $data = array( 0 => array('string' => __('Date','ocart')), 1 => array('number' => __('Sales','ocart')) ), 'ColumnChart', array('#238689') ); ?>
            </div>
        </div>

        <div class="dashboard-metabox">
            <h3><?php _e('Customers Map','ocart'); ?><span class="toggle-db-mb toggle-db-mb-closed"></span></h3>
            <div class="dashboard-metabox-body">
                <?php ocart_new_chart('geomap', '', $data = array( 0 => array('string' => __('Country','ocart')), 1 => array('number' => __('Sales Volume','ocart')), 2 => array('number' => __('Orders','ocart')) ), 'GeoChart' ); ?>
            </div>
        </div>
    </div>

    <div class="clear"></div>
</div>

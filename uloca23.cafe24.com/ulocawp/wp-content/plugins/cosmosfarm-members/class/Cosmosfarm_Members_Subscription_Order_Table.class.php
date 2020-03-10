<?php
/**
 * Cosmosfarm_Members_Subscription_Order_Table
 * @link https://www.cosmosfarm.com/
 * @copyright Copyright 2018 Cosmosfarm. All rights reserved.
 */
class Cosmosfarm_Members_Subscription_Order_Table extends WP_List_Table {

	var $status;
	var $subscription_next;
	
	public function __construct(){
		parent::__construct();
		
		$this->status = isset($_REQUEST['status']) ? sanitize_text_field($_REQUEST['status']) : '';
		$this->subscription_next = isset($_REQUEST['subscription_next']) ? sanitize_text_field($_REQUEST['subscription_next']) : '';
	}
	
	public function get_views(){
		global $wpdb;
		
		$order = new Cosmosfarm_Members_Subscription_Order();
		$views = array();
		$class = '';
		
		$count = $wpdb->get_var("SELECT COUNT(*) FROM `{$wpdb->posts}` WHERE `post_type`='{$order->post_type}'");
		$class = !$this->status ? ' class="current"' : '';
		$views['all'] = '<a href="' . add_query_arg(array(), admin_url('admin.php?page=cosmosfarm_subscription_order')) . '"' . $class . '>' . '전체' . " <span class=\"count\">({$count})</span></a>";
		
		$args = array('post_type'=>$order->post_type, 'meta_query'=>array(array(
			'key'     => 'status',
			'value'   => 'paid',
			'compare' => 'LIKE'
		)));
		$query = new WP_Query($args);
		$count = $query->found_posts;
		
		$class = $this->status == 'paid' ? ' class="current"' : '';
		$views['paid'] = '<a href="' . add_query_arg(array('status'=>'paid'), admin_url('admin.php?page=cosmosfarm_subscription_order')) . '"' . $class . '>' . '결제됨' . " <span class=\"count\">({$count})</span></a>";
		
		$args = array('post_type'=>$order->post_type, 'meta_query'=>array(array(
			'key'     => 'status',
			'value'   => 'cancelled',
			'compare' => 'LIKE'
		)));
		$query = new WP_Query($args);
		$count = $query->found_posts;
		
		$class = $this->status == 'cancelled' ? ' class="current"' : '';
		$views['cancelled'] = '<a href="' . add_query_arg(array('status'=>'cancelled'), admin_url('admin.php?page=cosmosfarm_subscription_order')) . '"' . $class . '>' . '취소됨' . " <span class=\"count\">({$count})</span></a>";
		
		return $views;
	}
	
	public function prepare_items(){
		$columns = $this->get_columns();
		$hidden = array();
		$sortable = array();
		$this->_column_headers = array($columns, $hidden, $sortable);
		
		$target = isset($_GET['target']) ? sanitize_text_field($_GET['target']) : '';
		$keyword = isset($_GET['s']) ? sanitize_text_field($_GET['s']) : '';
		
		$per_page = 20;
		$order = new Cosmosfarm_Members_Subscription_Order();
		$args = array(
				'post_type'      => $order->post_type,
				'orderby'        => 'ID',
				'posts_per_page' => $per_page,
				'paged'          => $this->get_pagenum(),
		);
		
		$meta_query = array();
		
		if($keyword){
			if($target == 'buyer_name'){
				$meta_query[] = array(
						'key'     => 'buyer_name',
						'value'   => $keyword,
						'compare' => 'LIKE',
				);
			}
			else if($target == 'buyer_email'){
				$meta_query[] = array(
						'key'     => 'buyer_email',
						'value'   => $keyword,
						'compare' => 'LIKE',
				);
			}
			else if($target == 'buyer_tel'){
				$meta_query[] = array(
						'key'     => 'buyer_tel',
						'value'   => $keyword,
						'compare' => 'LIKE',
				);
			}
			else if($target == 'merchant_uid'){
				$meta_query[] = array(
						'key'     => 'merchant_uid',
						'value'   => $keyword,
						'compare' => 'LIKE',
				);
			}
			else{
				$args['s'] = $keyword;
			}
		}
		
		if($this->status){
			$meta_query[] = array(
				'key'     => 'status',
				'value'   => $this->status,
				'compare' => 'LIKE',
			);
		}
		
		if($this->subscription_next){
			$meta_query[] = array(
				'key'     => 'subscription_next',
				'value'   => $this->subscription_next,
				'compare' => 'LIKE',
			);
		}
		
		$args['meta_query'] = $meta_query;
		
		$query = new WP_Query($args);
		$this->items = $query->posts;
		
		$this->set_pagination_args(array('total_items'=>$query->found_posts, 'per_page'=>$per_page));
	}
	
	public function get_table_classes(){
		$classes = parent::get_table_classes();
		$classes[] = 'cosmosfarm-members';
		$classes[] = 'subscription-order';
		return $classes;
	}
	
	public function no_items(){
		echo __('No orders found.', 'cosmosfarm-members');
	}
	
	public function get_columns(){
		return array(
			'cb' => '<input type="checkbox">',
			'title' => '상품 이름',
			'price' => '가격',
			'status' => '결제 상태',
			'subscription_next' => '정기결제 상태',
			'user' => '사용자',
			'buyer_name' => '주문자명',
			'buyer_email' => '이메일',
			'buyer_tel' => '전화번호',
			'merchant_uid' => '거래번호',
			'datetime' => '날짜',
		);
	}
	
	public function get_bulk_actions(){
		return array(
			'refund' => __('Cancel orders', 'cosmosfarm-members'),
			'refund_and_delete' => __('Cancel and permanently delete', 'cosmosfarm-members'),
			'delete' => __('Delete Permanently', 'cosmosfarm-members')
		);
	}
	
	public function display_tablenav($which){ ?>
		<div class="tablenav <?php echo esc_attr($which)?>">
			<div class="alignleft actions bulkactions"><?php $this->bulk_actions($which)?></div>
			<?php if($which=='top'):?>
				<div class="alignleft actions">
					<input type="hidden" name="status" value="<?php echo esc_attr($this->status)?>">
					<label class="screen-reader-text" for="subscription_next">정기결제 상태</label>
					<select id="subscription_next" name="subscription_next">
						<option value="">정기결제 상태</option>
						<option value="wait"<?php if($this->subscription_next == 'wait'):?> selected<?php endif?>>진행중</option>
						<option value="expiry"<?php if($this->subscription_next == 'expiry'):?> selected<?php endif?>>만료됨</option>
						<option value="success"<?php if($this->subscription_next == 'success'):?> selected<?php endif?>>정기결제 아님</option>
					</select>
					<input type="button" name="filter_action" class="button" value="필터" onclick="cosmosfarm_subscription_order_filter(this.form)">
					<span class="spinner"></span>
				</div>
				<script>
				function cosmosfarm_subscription_order_filter(form){
					var url = '<?php echo admin_url('admin.php?page=cosmosfarm_subscription_order')?>'
					var status = jQuery('input[name=status]', form).val();
					var subscription_next = jQuery('select[name=subscription_next]', form).val();
					if(status){
						url += '&status='+status;
					}
					if(subscription_next){
						url += '&subscription_next='+subscription_next;
					}
					window.location.href = url;
				}
				</script>
			<?php endif?>
			<?php
			$this->extra_tablenav($which);
			$this->pagination($which);
			?>
			<br class="clear">
		</div>
	<?php }
	
	public function display_rows(){
		foreach($this->items as $post){
			$order = new Cosmosfarm_Members_Subscription_Order($post->ID);
			$this->single_row($order);
		}
	}
	
	public function single_row($order){
		$user = $order->user();
		$edit_url = admin_url("admin.php?page=cosmosfarm_subscription_order&order_id={$order->ID}");
		$user_url = admin_url("user-edit.php?user_id={$user->ID}");
		
		echo '<tr data-order-id="'.$order->ID.'">';
		
		echo '<th scope="row" class="check-column">';
		echo '<input type="checkbox" name="order_id[]" value="'.$order->ID.'">';
		echo '</th>';
		
		echo '<td>';
		echo "<strong><a href=\"{$edit_url}\" class=\"row-title\" title=\"주문 정보\">".$order->title()."</a></strong>";
		echo '</td>';
		
		echo '<td>';
		echo cosmosfarm_members_currency_format($order->price());
		echo '</td>';
		
		echo '<td class="status-'.$order->status().'">';
		echo $order->status_format();
		echo '</td>';
		
		echo '<td class="subscription-next-'.$order->subscription_next().'">';
		echo $order->subscription_next_format();
		echo '</td>';
		
		echo '<td>';
		echo "<a href=\"{$user_url}\" title=\"사용자 편집\">{$user->display_name}({$user->user_login})</a>";
		echo '</td>';
		
		echo '<td>';
		echo $order->buyer_name;
		echo '</td>';
		
		echo '<td>';
		echo $order->buyer_email;
		echo '</td>';
		
		echo '<td>';
		echo $order->buyer_tel;
		echo '</td>';
		
		echo '<td>';
		echo $order->merchant_uid;
		echo '</td>';
		
		echo '<td>';
		echo $order->post_date;
		echo '</td>';
		
		echo '</tr>';
	}
	
	public function search_box($text, $input_id){
		$target = isset($_GET['target']) ? sanitize_text_field($_GET['target']) : '';
	?>
	<p class="search-box">
		<input type="hidden" name="status" value="<?php echo esc_attr($this->status)?>">
		<input type="hidden" name="subscription_next" value="<?php echo esc_attr($this->subscription_next)?>">
		<select name="target" style="float:left;height:28px;margin:0 4px 0 0">
			<option value="">상품이름</option>
			<option value="buyer_name"<?php if($target == 'buyer_name'):?> selected<?php endif?>>주문자명</option>
			<option value="buyer_email"<?php if($target == 'buyer_email'):?> selected<?php endif?>>이메일</option>
			<option value="buyer_tel"<?php if($target == 'buyer_tel'):?> selected<?php endif?>>전화번호</option>
			<option value="merchant_uid"<?php if($target == 'merchant_uid'):?> selected<?php endif?>>거래번호</option>
		</select>
		<input type="search" id="<?php echo $input_id?>" name="s" value="<?php _admin_search_query()?>">
		<?php submit_button($text, 'button', false, false, array('id'=>'search-submit'))?>
	</p>
	<?php }
}
?>
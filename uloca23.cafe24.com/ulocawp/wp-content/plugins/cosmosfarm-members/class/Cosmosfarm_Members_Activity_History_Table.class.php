<?php
/**
 * Cosmosfarm_Members_Activity_History_Table
 * @link https://www.cosmosfarm.com/
 * @copyright Copyright 2018 Cosmosfarm. All rights reserved.
 */
class Cosmosfarm_Members_Activity_History_Table extends WP_List_Table {
	
	public function __construct(){
		parent::__construct();
	}
	
	public function prepare_items(){
		global $wpdb;
		
		$columns = $this->get_columns();
		$hidden = array();
		$sortable = array();
		$this->_column_headers = array($columns, $hidden, $sortable);
		
		$target = isset($_GET['target']) ? sanitize_text_field($_GET['target']) : '';
		$keyword = isset($_GET['s']) ? sanitize_text_field($_GET['s']) : '';
		
		switch($target){
			case 'user_login':
				$user = get_user_by('login', $keyword);
				if($user) $where = "`user_id`='{$user->ID}'";
				else $where = "`user_id`=''";
				break;
			case 'user_email':
				$user = get_user_by('email', $keyword);
				if($user) $where = "`user_id`='{$user->ID}'";
				else $where = "`user_id`=''";
				break;
			case 'related_user_login':
				$user = get_user_by('login', $keyword);
				if($user) $where = "`related_user_id`='{$user->ID}'";
				else $where = "`related_user_id`=''";
				break;
			case 'related_user_email':
				$user = get_user_by('email', $keyword);
				if($user) $where = "`related_user_id`='{$user->ID}'";
				else $where = "`related_user_id`=''";
				break;
			case 'ip_address':
				$where = "`ip_address`='".esc_sql($keyword)."'";
				break;
			default:
				$where = '1';
		}
		
		$page = $this->get_pagenum();
		$per_page = 20;
		$offset = (($page-1)*$per_page);
		
		$this->items = $wpdb->get_results("SELECT * FROM `{$wpdb->prefix}cosmosfarm_members_activity_history` WHERE {$where} ORDER BY activity_history_id DESC LIMIT {$offset},{$per_page}");
		$total_items = $wpdb->get_var("SELECT COUNT(*) FROM `{$wpdb->prefix}cosmosfarm_members_activity_history` WHERE {$where}");
		
		$this->set_pagination_args(array('total_items'=>$total_items, 'per_page'=>$per_page));
	}
	
	public function get_table_classes(){
		$classes = parent::get_table_classes();
		$classes[] = 'cosmosfarm-members';
		$classes[] = 'activity-history';
		return $classes;
	}
	
	public function no_items(){
		echo __('No history found.', 'cosmosfarm-members');
	}
	
	public function get_columns(){
		return array(
				'cb' => '<input type="checkbox">',
				'user_id' => '사용자',
				'related_user_id' => '조회된 회원',
				'comment' => '내용',
				'ip_address' => '아이피주소',
				'activity_datetime' => '활동 시간',
		);
	}
	
	function get_bulk_actions(){
		//return array('delete' => __('Delete', 'cosmosfarm-members'));
		return array();
	}
	
	public function display_rows(){
		foreach($this->items as $item){
			$this->single_row($item);
		}
	}
	
	public function single_row($item){
		$edit_url = admin_url("admin.php?page=kboard_list&board_id={$item->activity_history_id}");
		
		echo '<tr data-activity-history-id"'.$item->activity_history_id.'">';
		
		echo '<th scope="row" class="check-column">';
		echo '<input type="checkbox" name="activity_history_id[]" value="'.$item->activity_history_id.'">';
		echo '</th>';
		
		echo '<td>';
		$user = get_userdata($item->user_id);
		if($user){
			echo "{$user->user_login} ($user->user_email)";
		}
		else{
			echo '삭제된 회원';
		}
		echo '</td>';
		
		echo '<td>';
		$user = get_userdata($item->related_user_id);
		if($user){
			echo "{$user->user_login} ($user->user_email)";
		}
		else{
			echo '삭제된 회원';
		}
		echo '</td>';
		
		echo '<td>';
		echo $item->comment;
		echo '</td>';
		
		echo '<td>';
		echo $item->ip_address;
		echo '</td>';
		
		echo '<td>';
		echo $item->activity_datetime;
		echo '</td>';
		
		echo '</tr>';
	}
	
	public function search_box($text, $input_id){
		$target = isset($_GET['target']) ? sanitize_text_field($_GET['target']) :'';
	?>
	<p class="search-box">
		<select name="target" style="float:left;height:28px;margin:0 4px 0 0">
			<option value="user_login"<?php if($target == 'user_login'):?> selected<?php endif?>>사용자 계정</option>
			<option value="user_email"<?php if($target == 'user_email'):?> selected<?php endif?>>사용자 이메일</option>
			<option value="related_user_login"<?php if($target == 'related_user_login'):?> selected<?php endif?>>조회된 회원 계정</option>
			<option value="related_user_email"<?php if($target == 'related_user_email'):?> selected<?php endif?>>조회된 회원 이메일</option>
			<option value="ip_address"<?php if($target == 'ip_address'):?> selected<?php endif?>>아이피주소</option>
		</select>
		<input type="search" id="<?php echo $input_id?>" name="s" value="<?php _admin_search_query()?>">
		<?php submit_button($text, 'button', false, false, array('id'=>'search-submit'))?>
	</p>
	<?php }
}
?>
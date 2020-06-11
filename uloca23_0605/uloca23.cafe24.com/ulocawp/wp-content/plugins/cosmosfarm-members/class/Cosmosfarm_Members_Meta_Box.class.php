<?php
/**
 * Cosmosfarm_Members_Meta_Box
 * @link https://www.cosmosfarm.com/
 * @copyright Copyright 2018 Cosmosfarm. All rights reserved.
 */
class Cosmosfarm_Members_Meta_Box {
	
	public function __construct(){
		add_action('add_meta_boxes', array($this, 'add_meta_boxes'), 99, 2);
		add_action('save_post', array($this, 'save_page_restriction'), 10, 2);
		
		add_action('category_add_form_fields', array($this, 'new_category_meta_field'), 99, 2);
		add_action('category_edit_form_fields', array($this, 'edit_category_meta_field'), 99, 2);
		
		add_action('edited_category', array($this, 'save_category_meta_field'), 99, 2);
		add_action('create_category', array($this, 'save_category_meta_field'), 99, 2);
	}
	
	public function add_meta_boxes($post_type, $post){
		//remove_meta_box('wpmem-block-meta-id', 'post', 'side');
		//remove_meta_box('wpmem-block-meta-id', 'page', 'side');
		add_meta_box('cosmosfarm-members-page-restriction', __('Page Restriction', 'cosmosfarm-members'), array($this, 'render_page_restriction'), array(), 'side', 'default');
	}
	
	public function render_page_restriction($post, $box){
		$page_restriction = get_post_meta($post->ID, 'cosmosfarm_members_page_restriction', true);
		$restriction_roles = get_post_meta($post->ID, 'cosmosfarm_members_page_restriction_roles', true);
		
		echo wp_nonce_field(basename(__FILE__), 'cosmosfarm_members_page_restriction_nonce');
		?>
		<p class="post-attributes-label-wrapper"><label class="post-attributes-label" for="cosmosfarm_members_page_restriction">공개</label></p>
		<select id="cosmosfarm_members_page_restriction" name="cosmosfarm_members_page_restriction">
			<option value="">전체 공개</option>
			<option value="1"<?php if($page_restriction):?> selected<?php endif?>>선택된 사용자만 공개</option>
		</select>
		
		<p class="post-attributes-label-wrapper"><label class="post-attributes-label">사용자 선택</label></p>
		<ul>
		<?php foreach(get_editable_roles() as $key=>$value):?>
			<li><label><input type="checkbox" name="cosmosfarm_members_page_restriction_roles[]" value="<?php echo $key?>"<?php if($key=='administrator'):?> onclick="return false"<?php endif?><?php if($key=='administrator' || (is_array($restriction_roles) && in_array($key, $restriction_roles))):?> checked<?php endif?>><?php echo _x($value['name'], 'User role')?></label></li>
		<?php endforeach?>
		</ul>
		
		<p class="post-attributes-label-wrapper"><label class="post-attributes-label">추가 설정</label></p>
		<a href="<?php echo admin_url('admin.php?page=cosmosfarm_members_setting')?>">코스모스팜 회원관리 설정으로 이동</a>
	<?php }
	
	function save_page_restriction($post_id, $post){
		
		if(!isset($_POST['cosmosfarm_members_page_restriction_nonce']) || !wp_verify_nonce($_POST['cosmosfarm_members_page_restriction_nonce'], basename(__FILE__))){
			return $post_id;
		}
		
		$post_type = get_post_type_object($post->post_type);
		if(!current_user_can($post_type->cap->edit_post, $post_id)){
			return $post_id;
		}
		
		/*
		$post_type = get_post_type($post_id);
		if(!in_array($post_type, array('post', 'page'))){
			return $post_id;
		}
		*/
		
		/*
		if(wp_is_post_revision($post_id)){
			return $post_id;
		}
		*/
		
		$new_meta_value = isset($_POST['cosmosfarm_members_page_restriction']) ? $_POST['cosmosfarm_members_page_restriction'] : '';
		$this->meta_update($post_id, 'cosmosfarm_members_page_restriction', $new_meta_value);
		
		$new_meta_value = isset($_POST['cosmosfarm_members_page_restriction_roles']) ? $_POST['cosmosfarm_members_page_restriction_roles'] : array();
		$this->meta_update($post_id, 'cosmosfarm_members_page_restriction_roles', $new_meta_value);
	}
	
	public function meta_update($post_id, $meta_key, $new_meta_value){
		$meta_value = get_post_meta($post_id, $meta_key, true);
		
		if($new_meta_value){
			if(!$meta_value){
				add_post_meta($post_id, $meta_key, $new_meta_value, true);
			}
			else if($new_meta_value != $meta_value){
				update_post_meta($post_id, $meta_key, $new_meta_value);
			}
		}
		else if($meta_value){
			delete_post_meta($post_id, $meta_key, $meta_value);
		}
	}
	
	function new_category_meta_field(){ ?>
		<div class="form-field term-category-restriction-wrap">
			<label for="term_meta[cosmosfarm_members_category_restriction]"><?php echo __('Category Restriction', 'cosmosfarm-members')?></label>
			
			<select id="term_meta[cosmosfarm_members_category_restriction]" name="term_meta[cosmosfarm_members_category_restriction]">
				<option value="">전체 공개</option>
				<option value="1">선택된 사용자만 공개</option>
			</select>
			
			<label>사용자 선택</label>
			<ul style="margin:0">
			<?php foreach(get_editable_roles() as $key=>$value):?>
				<li><label><input type="checkbox" name="term_meta[cosmosfarm_members_category_restriction_roles][]" value="<?php echo $key?>"<?php if($key=='administrator'):?> onclick="return false"<?php endif?><?php if($key=='administrator'):?> checked<?php endif?>><?php echo _x($value['name'], 'User role')?></label></li>
			<?php endforeach?>
			</ul>
			
			<label>추가 설정</label>
			<a href="<?php echo admin_url('admin.php?page=cosmosfarm_members_setting')?>">코스모스팜 회원관리 설정으로 이동</a>
		</div>
	<?php }
	
	function edit_category_meta_field($term){
    	$term_meta = get_option("taxonomy_{$term->term_id}");
    	$category_restriction = isset($term_meta['cosmosfarm_members_category_restriction'])?$term_meta['cosmosfarm_members_category_restriction']:'';
    	$restriction_roles = isset($term_meta['cosmosfarm_members_category_restriction_roles'])?$term_meta['cosmosfarm_members_category_restriction_roles']:array();
    	?>
		<tr class="form-field term-category-restriction-wrap">
			<th scope="row" valign="top"><label for="term_meta[cat_icon]"><?php echo __('Category Restriction', 'cosmosfarm-members')?></label></th>
			<td>
				<select id="term_meta[cosmosfarm_members_category_restriction]" name="term_meta[cosmosfarm_members_category_restriction]">
					<option value="">전체 공개</option>
					<option value="1"<?php if($category_restriction):?> selected<?php endif?>>선택된 사용자만 공개</option>
				</select>
				
				<div>
					<label>사용자 선택</label>
					<br>
					<ul style="margin:0">
					<?php foreach(get_editable_roles() as $key=>$value):?>
						<li><label><input type="checkbox" name="term_meta[cosmosfarm_members_category_restriction_roles][]" value="<?php echo $key?>"<?php if($key=='administrator'):?> onclick="return false"<?php endif?><?php if($key=='administrator' || (is_array($restriction_roles) && in_array($key, $restriction_roles))):?> checked<?php endif?>><?php echo _x($value['name'], 'User role')?></label></li>
					<?php endforeach?>
					</ul>
				</div>
				
				<div class="description">
					<label>추가 설정</label>
					<br>
					<a href="<?php echo admin_url('admin.php?page=cosmosfarm_members_setting')?>">코스모스팜 회원관리 설정으로 이동</a>
				</div>
			</td>
		</tr>
	<?php }
	
	function save_category_meta_field($term_id){
		if(isset($_POST['term_meta'])){
			$term_meta = get_option("taxonomy_{$term_id}");
			$cat_keys = array_keys($_POST['term_meta']);
			foreach($cat_keys as $key){
				if(isset($_POST['term_meta'][$key])){
					$term_meta[$key] = $_POST['term_meta'][$key];
				}
			}
			update_option("taxonomy_{$term_id}", $term_meta);
		}
	}
}
?>
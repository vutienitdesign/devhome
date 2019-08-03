<?php
	$vID = $_REQUEST['row-edit'];
	
	global $wpdb;
	$tbl    = $wpdb->prefix . 'decorate_medium';
	$sql    = "SELECT `name` FROM `{$tbl}` WHERE `id` = '{$vID}'";
	$result = $wpdb->get_row($sql, ARRAY_A);
	
	$html = '<tr id="edit-'.$vID.'" class="quick-edit">
                <td colspan="7" class="colspanchange">
                	<legend class="inline-edit-legend">Sửa nhanh</legend>
                	<div class="msg"></div>
                    <fieldset class="poka-row left">
					    <div class="inline-col">
					        <div class="item">
					            <label class="title">Name</label>
					            <span class="data"><input type="text" name="poka_name" value="'.$result['name'].'"></span>
					        </div>
					    </div>
					</fieldset>
                    
                    <div class="submit inline-edit-save">
						<button type="button" class="button alignleft btn-clone">Cancel</button>
						<button onclick="return confirm(\'Are you sure you want to update?\');" type="button" class="button button-primary alignleft btn-update" update-now="'.$vID.'">Update</button>
					</div>
				</div>
                </td>
             </tr>';
	
	echo $html;
	
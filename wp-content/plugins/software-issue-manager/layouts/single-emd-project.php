<?php $real_post = $post;
$ent_attrs = get_option('software_issue_manager_attr_list');
?>
<div style="position:relative" class="emd-container">
<div class="panel panel-info" >
    <div class="panel-heading clearfix" style="position:relative; ">
        <div class="panel-title">
            <span class='single-title font-bold'><span class="emd_project-title"><?php echo get_the_title(); ?></span></span>
        </div>
    </div>
    <div class="panel-body clearfix">
        <div class="single-well well emd-project">
            <div class="row">
                <div class="col-sm-6">
                    <div class="slcontent emdbox">
                        <?php if (emd_is_item_visible('tax_project_priority', 'software_issue_manager', 'taxonomy')) { ?>
                        <div class="segment-block tax-project-priority">
                            <div class="row" hasattrib="">
                                <div class="col-sm-6"><span class="segtitle"><?php _e('Priority', 'software-issue-manager'); ?></span></div>
                                <div class="col-sm-6"><span class="taxlabel" style="white-space:normal;" taxlight=""><?php echo emd_get_tax_vals(get_the_ID() , 'project_priority'); ?></span></div>
                            </div>
                        </div>
                        <?php
} ?><?php if (emd_is_item_visible('tax_project_status', 'software_issue_manager', 'taxonomy')) { ?>
                        <div class="segment-block tax-project-status">
                            <div class="row" hasattrib="">
                                <div class="col-sm-6"><span class="segtitle"><?php _e('Status', 'software-issue-manager'); ?></span></div>
                                <div class="col-sm-6"><span class="taxlabel" style="white-space:normal;" taxlight=""><?php echo emd_get_tax_vals(get_the_ID() , 'project_status'); ?></span></div>
                            </div>
                        </div>
                        <?php
} ?>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="srcontent emdbox">
                        <?php if (emd_is_item_visible('ent_prj_start_date', 'software_issue_manager', 'attribute')) { ?>
                        <div class="segment-block ent-prj-start-date">
                            <div data-has-attrib="false" class="row">
                                <div class="col-sm-6"><span class="segtitle"><?php _e('Start Date', 'software-issue-manager'); ?></span></div>
                                <div class="col-sm-6"><span class="segvalue"><?php echo date_i18n(get_option('date_format') , strtotime(emd_mb_meta('emd_prj_start_date'))); ?></span></div>
                            </div>
                        </div>
                        <?php
} ?><?php if (emd_is_item_visible('ent_prj_target_end_date', 'software_issue_manager', 'attribute')) { ?>
                        <div class="segment-block ent-prj-target-end-date">
                            <div data-has-attrib="false" class="row">
                                <div class="col-sm-6"><span class="segtitle"><?php _e('Target End Date', 'software-issue-manager'); ?></span></div>
                                <div class="col-sm-6"><span class="segvalue"><?php echo date_i18n(get_option('date_format') , strtotime(emd_mb_meta('emd_prj_target_end_date'))); ?></span></div>
                            </div>
                        </div>
                        <?php
} ?>
                    </div>
                </div>
            </div>
        </div>
        <div id="modified-info-block" class=" text-right">
            <p class="textSmall text-muted"><?php _e('Last modified by', 'software-issue-manager'); ?> <?php echo get_the_modified_author(); ?> - <?php echo human_time_diff(strtotime(get_the_modified_date() . " " . get_the_modified_time()) , current_time('timestamp')); ?> <?php _e('ago', 'software-issue-manager'); ?></p>
        </div>
        <div class="tab-container wpastabcontainer" style="padding:0 20px 40px;">
            <ul class="nav nav-tabs" role="tablist" style="padding-bottom: 0px;">
                <li class=" active "><a id="description-tablink" href="#description" role="tab" data-toggle="tab"><?php _e('Description', 'software-issue-manager'); ?></a></li>
                <li><a id="details-tablink" href="#details" role="tab" data-toggle="tab"><?php _e('Details', 'software-issue-manager'); ?></a></li>
            </ul>
            <div class="tab-content wpastabcontent">
                <div class="tab-pane fade in active" id="description">
                    <?php if (emd_is_item_visible('content', 'software_issue_manager', 'attribute')) { ?>
                    <div class="single-content content"><?php echo $post->post_content; ?></div>
                    <?php
} ?>
                </div>
                <div class="tab-pane fade in " id="details">
                    <?php if (emd_is_item_visible('ent_prj_actual_end_date', 'software_issue_manager', 'attribute')) { ?>
                    <div class="segment-block ent-prj-actual-end-date">
                        <div data-has-attrib="false" class="row">
                            <div class="col-sm-6"><span class="segtitle"><?php _e('Actual End Date', 'software-issue-manager'); ?></span></div>
                            <div class="col-sm-6"><span class="segvalue"><?php echo date_i18n(get_option('date_format') , strtotime(emd_mb_meta('emd_prj_actual_end_date'))); ?></span></div>
                        </div>
                    </div>
                    <?php
} ?><?php if (emd_is_item_visible('ent_prj_file', 'software_issue_manager', 'attribute')) { ?>
                    <div class="segment-block ent-prj-file">
                        <div data-has-attrib="false" class="row">
                            <div class="col-sm-6"><span class="segtitle"><?php _e('Documents', 'software-issue-manager'); ?></span></div>
                            <div class="col-sm-6"><span class="segvalue"><?php
	$emd_mb_file = emd_mb_meta('emd_prj_file', 'type=file');
	if (!empty($emd_mb_file)) {
		foreach ($emd_mb_file as $info) {
			$fsrc = wp_mime_type_icon($info['ID']);
?>
 <div class='att-file' style='padding:5px;display:inline-block;'><a class='att-link' href='<?php echo esc_url($info['url']); ?>' target='_blank' title='<?php echo esc_attr($info['title']); ?>'><img src='<?php echo esc_url($fsrc); ?>' title='<?php echo esc_attr($info['name']); ?>' width='48' height='64'/></a><div class='att-filename' style='padding:2px;font-size:80%;'><?php echo $info['title']; ?></div></div>
<?php
		}
	}
?>
</span></div>
                        </div>
                    </div>
                    <?php
} ?><?php $cust_fields = get_metadata('post', get_the_ID());
$real_cust_fields = Array();
$ent_map_list = get_option('software_issue_manager_ent_map_list', Array());
foreach ($cust_fields as $ckey => $cval) {
	if (empty($ent_attrs['emd_project'][$ckey]) && !preg_match('/^(_|wpas_|emd_)/', $ckey)) {
		$cust_key = str_replace('-', '_', sanitize_title($ckey));
		if (empty($ent_map_list) || (!empty($ent_map_list) && empty($ent_map_list['emd_project']['cust_fields'][$cust_key]))) {
			$real_cust_fields[$ckey] = $cval;
		}
	}
}
if (!empty($real_cust_fields)) {
	$fcount = 0;
	foreach ($real_cust_fields as $rkey => $rval) {
		$val = implode($rval, " ");
		$fcount++;
?><div id='cust-field-<?php echo $fcount; ?>'>
                    <div class="segment-block emd-project-custom-fields">
                        <div class="row">
                            <div class="col-sm-6 "><span class="segtitle"><?php echo $rkey; ?></span></div>
                            <div class="col-sm-6"><span class="segvalue"><?php echo $val; ?></span></div>
                        </div>
                    </div>
                    </div><?php
	}
}
?>
                </div>
            </div>
        </div>
        <div class="panel-group" id="accordion"> <?php if (emd_is_item_visible('entrelcon_project_issues', 'software_issue_manager', 'relation')) { ?>
<?php $post = get_post();
	$rel_filter = "";
	$res = emd_get_p2p_connections('connected', 'project_issues', 'std', $post, 1, 0, '', 'software_issue_manager', $rel_filter);
	if (!empty($res['rels'])) {
?>
<div class="emd-project entrelcon-project-issues"><div style="overflow:visible;" class="panel panel-default relseg">
 <div class="panel-heading clearfix">
  <div class="panel-title">
   <a class="accor-title-link collapsed" data-toggle="collapse" data-parent="#accordion" href="#rel-1088">
   <div class="accor-title"><?php _e('Project Issues', 'software-issue-manager'); ?></div>
   </a>
  </div>
 </div>
 <div id="rel-1088" class="panel-collapse collapse in">
  <div data-has-attrib="false" class="panel-body clearfix emd-table-container">
<table id="table-project-issues-con"
           class="table emd-table table-bordered table-hover"
>
<thead><tr><th><?php _e('Title', 'software-issue-manager'); ?></th><?php if (emd_is_item_visible('ent_iss_id', 'software_issue_manager', 'attribute', 1)) { ?>
<th><?php _e('Issue #', 'software-issue-manager'); ?></th>
<?php
		} ?><?php if (emd_is_item_visible('tax_issue_priority', 'software_issue_manager', 'taxonomy', 1)) { ?>
<th><?php _e('Priority', 'software-issue-manager'); ?></th>
<?php
		} ?><?php if (emd_is_item_visible('tax_issue_cat', 'software_issue_manager', 'taxonomy', 1)) { ?>
<th><?php _e('Category', 'software-issue-manager'); ?></th>
<?php
		} ?><?php if (emd_is_item_visible('tax_issue_status', 'software_issue_manager', 'taxonomy', 1)) { ?>
<th><?php _e('Status', 'software-issue-manager'); ?></th>
<?php
		} ?></tr></thead><?php
		echo $res['before_list'];
		$real_post = $post;
		$rel_count_id = 1;
		foreach ($res['rels'] as $myrel) {
			$post = $myrel;
			echo $res['before_item']; ?>
<tr>
    <td><a href="<?php echo get_permalink($post->ID); ?>" title="<?php echo get_the_title(); ?>"><?php echo get_the_title(); ?></a></td>
    <?php if (emd_is_item_visible('ent_iss_id', 'software_issue_manager', 'attribute', 1)) { ?> 
    <td><?php echo esc_html(emd_mb_meta('emd_iss_id', '', $myrel->ID)); ?></td>
    <?php
			} ?><?php if (emd_is_item_visible('tax_issue_priority', 'software_issue_manager', 'taxonomy', 1)) { ?> 
    <td><?php echo emd_get_tax_vals($myrel->ID, 'issue_priority'); ?></td>
    <?php
			} ?><?php if (emd_is_item_visible('tax_issue_cat', 'software_issue_manager', 'taxonomy', 1)) { ?> 
    <td><?php echo emd_get_tax_vals($myrel->ID, 'issue_cat'); ?></td>
    <?php
			} ?><?php if (emd_is_item_visible('tax_issue_status', 'software_issue_manager', 'taxonomy', 1)) { ?> 
    <td><?php echo emd_get_tax_vals($myrel->ID, 'issue_status'); ?></td>
    <?php
			} ?>
</tr><?php
			echo $res['after_item'];
			$rel_count_id++;
		}
		$post = $real_post;
		echo $res['after_list']; ?>
</table>  </div>
 </div>
</div></div><?php
	} ?>
<?php
} ?> </div>
    </div>
    <div class="panel-footer clearfix"></div>
</div>
</div><!--container-end-->
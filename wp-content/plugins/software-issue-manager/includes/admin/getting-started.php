<?php
/**
 * Getting Started
 *
 * @package SOFTWARE_ISSUE_MANAGER
 * @since WPAS 5.3
 */
if (!defined('ABSPATH')) exit;
add_action('software_issue_manager_getting_started', 'software_issue_manager_getting_started');
/**
 * Display getting started information
 * @since WPAS 5.3
 *
 * @return html
 */
function software_issue_manager_getting_started() {
	global $title;
	list($display_version) = explode('-', SOFTWARE_ISSUE_MANAGER_VERSION);
?>
<style>
#emd-about ul li:before{
    content: "\f522";
    font-family: dashicons;
    font-size:25px;
 }
#gallery {
	margin: auto;
}
#gallery .gallery-item {
	float: left;
	margin-top: 10px;
	margin-right: 10px;
	text-align: center;
	width: 48%;
        cursor:pointer;
}
#gallery img {
	border: 2px solid #cfcfcf; 
height: 405px;  
}
#gallery .gallery-caption {
	margin-left: 0;
}
#emd-about .top{
text-decoration:none;
}
#emd-about .toc{
    background-color: #fff;
    padding: 25px;
    border: 1px solid #add8e6;
    border-radius: 8px;
}
#emd-about h3,
#emd-about h2{
    margin-top: 0px;
    margin-right: 0px;
    margin-bottom: 0.6em;
    margin-left: 0px;
}
#emd-about p{font-size:18px}
#emd-about .top:after{
content: "\f342";
    font-family: dashicons;
    font-size:25px;
text-decoration:none;
}
#emd-about li,
#emd-about a{
vertical-align: top;
}
#emd-about .quote{
    background: #fff;
    border-left: 4px solid #088cf9;
    -webkit-box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
    box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
    margin-top: 25px;
    padding: 1px 12px;
}
#emd-about .tooltip{
    display: inline;
    position: relative;
}
#emd-about .tooltip:hover:after{
    background: #333;
    background: rgba(0,0,0,.8);
    border-radius: 5px;
    bottom: 26px;
    color: #fff;
    content: 'Click to enlarge';
    left: 20%;
    padding: 5px 15px;
    position: absolute;
    z-index: 98;
    width: 220px;
}
</style>

<?php add_thickbox(); ?>
<div id="emd-about" class="wrap about-wrap">
<div id="emd-header" style="padding:10px 0" class="wp-clearfix">
<div style="float:right"><img src="http://emd-plugins.s3.amazonaws.com/sim_logo.png"></div>
<div style="margin: .2em 200px 0 0;padding: 0;color: #32373c;line-height: 1.2em;font-size: 2.8em;font-weight: 400;">
<?php printf(__('Welcome to Software Issue Manager Community %s', 'software-issue-manager') , $display_version); ?>
</div>

<p class="about-text">
<?php printf(__('For effective and efficient issue management', 'software-issue-manager') , $display_version); ?>
</p>

<?php
	$tabs['getting-started'] = __('Getting Started', 'software-issue-manager');
	$tabs['whats-new'] = __('What\'s New', 'software-issue-manager');
	$tabs['resources'] = __('Resources', 'software-issue-manager');
	$active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'getting-started';
	echo '<h2 class="nav-tab-wrapper wp-clearfix">';
	foreach ($tabs as $ktab => $mytab) {
		$tab_url[$ktab] = esc_url(add_query_arg(array(
			'tab' => $ktab
		)));
		$active = "";
		if ($active_tab == $ktab) {
			$active = "nav-tab-active";
		}
		echo '<a href="' . esc_url($tab_url[$ktab]) . '" class="nav-tab ' . $active . '" id="nav-' . $ktab . '">' . $mytab . '</a>';
	}
	echo '</h2>';
	echo '<div class="tab-content" id="tab-getting-started"';
	if ("getting-started" != $active_tab) {
		echo 'style="display:none;"';
	}
	echo '>';
?>
<div style="height:25px" id="rtop"></div><div class="toc"><h3 style="color:#0073AA;text-align:left;">Quickstart</h3><ul><li><a href="#gs-sec-15">Issue Manager Workflow a.k.a How to use this tool to make things happen</a></li>
<li><a href="#gs-sec-10">Using Setup assistant</a></li>
<li><a href="#gs-sec-12">How to create your first project</a></li>
<li><a href="#gs-sec-9">How to create your first issue</a></li>
<li><a href="#gs-sec-17">How to customize it to better match your needs</a></li>
<li><a href="#gs-sec-18">How to limit access to issue entry and search forms by logged-in users only</a></li>
<li><a href="#gs-sec-19">How to resolve theme related issues</a></li>
</ul></div><div class="quote">
<p class="about-description">The secret of getting ahead is getting started - Mark Twain</p>
</div>
<div class="changelog getting-started getting-started-15" style="margin:0"><div style="height:40px" id="gs-sec-15"></div><h2>Issue Manager Workflow a.k.a How to use this tool to make things happen</h2><div id="gallery" class="wp-clearfix"><div class="sec-img gallery-item"><a class="thickbox tooltip" rel="gallery-15" href="https://emdsnapshots.s3.amazonaws.com/simcom-requirement.jpg"><img src="https://emdsnapshots.s3.amazonaws.com/simcom-requirement.jpg"></a></div></div><div class="sec-desc"><p>Most projects fail due to lack of understanding of requirement management and analysis process. Requirement management and analysis is not some type of red tape dragging you down but a necessary component of successful system development process. <a href="https://speakerdeck.com/emarketdesign/effective-requirement-collection">Check out a presentation by Dara Duman on "Effective Requirement Collection".</a></p>
<ol>
  <li>Create a project</li>
  <li>Create issues and assign them to that project</li>
  <li>Alternatively, assign issues to Products using Easy Digital Download or WooCommerce extensions</li>
  <li>Update issue and project information as your project moves along in the timeline</li>
  <li>If issues are resolved; bugs fixed, feature released or task completed, write down a resolution summary</li>
  <li>Ask project members to collaborate on issue resolutions</li>
<li>Repeat this process till you get things done and move on to the next one.</li>
</ol></div></div><div style="margin-top:15px"><a href="#rtop" class="top">Go to top</a></div><hr style="margin-top:40px"><div class="changelog getting-started getting-started-10" style="margin:0"><div style="height:40px" id="gs-sec-10"></div><h2>Using Setup assistant</h2><div id="gallery" class="wp-clearfix"><div class="sec-img gallery-item"><a class="thickbox tooltip" rel="gallery-10" href="https://emdsnapshots.s3.amazonaws.com/simcom-setupassistant.png"><img src="https://emdsnapshots.s3.amazonaws.com/simcom-setupassistant.png"></a></div></div><div class="sec-desc"><p>Setup assistant creates the issue search and entry form pages automatically.</p></div></div><div style="margin-top:15px"><a href="#rtop" class="top">Go to top</a></div><hr style="margin-top:40px"><div class="changelog getting-started getting-started-12" style="margin:0"><div style="height:40px" id="gs-sec-12"></div><h2>How to create your first project</h2><div id="gallery" class="wp-clearfix"><div class="sec-img gallery-item"><a class="thickbox tooltip" rel="gallery-12" href="https://emdsnapshots.s3.amazonaws.com/simcom-project_edit.png"><img src="https://emdsnapshots.s3.amazonaws.com/simcom-project_edit.png"></a></div></div><div class="sec-desc"><p>Project is a collection of related issues. A project is identified by its name and version.</p>
<ol>
  <li>Click the 'Projects' tab.</li>
  <li>Click the 'Add New' sub-tab or the “Add New” button in the project list page.</li>
  <li>Start filling in your project fields. You must fill all required fields. All required fields have red star after their labels.</li>
  <li>As needed, set project taxonomies and relationships. All required relationships or taxonomies must be set.</li>
  <li>When you are ready, click Publish. If you do not have publish privileges, the "Submit for Review" button is displayed.</li>
  <li>After the submission is completed, the project status changes to "Published" or "Pending Review".</li>
<li>Click on the permalink to see the project page on the frontend</li>
</ol></div></div><div style="margin-top:15px"><a href="#rtop" class="top">Go to top</a></div><hr style="margin-top:40px"><div class="changelog getting-started getting-started-9" style="margin:0"><div style="height:40px" id="gs-sec-9"></div><h2>How to create your first issue</h2><div id="gallery" class="wp-clearfix"><div class="sec-img gallery-item"><a class="thickbox tooltip" rel="gallery-9" href="https://emdsnapshots.s3.amazonaws.com/simcom-issue_edit.png"><img src="https://emdsnapshots.s3.amazonaws.com/simcom-issue_edit.png"></a></div></div><div class="sec-desc"><p>Issues can be any type of defects, feature requests, improvements etc. Issues can be shared by many projects.</p>
<ol>
  <li>Click the 'All Issues' link.</li>
  <li>Click “Add New” button in the issue list page.</li>
  <li>Start filling in your issue fields. You must fill all required fields. All required fields have red star after their labels.</li>
  <li>As needed, set issue taxonomies. Link issue to one or more projects by linking it under "Affected projects" connection box</li>
  <li>When you are ready, click Publish. If you do not have publish privileges, the "Submit for Review" button is displayed.</li>
  <li>After the submission is completed, the issue status changes to "Published" or "Pending Review".</li>
<li>Click on the permalink to see the issue page on the frontend</li>
</ol></div></div><div style="margin-top:15px"><a href="#rtop" class="top">Go to top</a></div><hr style="margin-top:40px"><div class="changelog getting-started getting-started-17" style="margin:0"><div style="height:40px" id="gs-sec-17"></div><h2>How to customize it to better match your needs</h2><div id="gallery" class="wp-clearfix"><div class="sec-img gallery-item"><a class="thickbox tooltip" rel="gallery-17" href="https://emdsnapshots.s3.amazonaws.com/simcom-customization.png"><img src="https://emdsnapshots.s3.amazonaws.com/simcom-customization.png"></a></div></div><div class="sec-desc"><p>Software Issue Manager can be customized from plugin setting without modifying code or theme templates(most cases)</p>
<ul>
<li>Enable or disable all fields, taxonomies and relationships from backend and/or frontend</li>
<li>Create custom fields in the edit area and optionally display them in issue search and entry forms and frontend pages</li>
<li>Set slug of any entity and/or archive base slug</li><li>Set the page template of any entity, taxonomy and/or archive page to sidebar on left, sidebar on right or no sidebar (full width)</li>
<li>Hide the previous and next post links on the frontend for single posts</li>
<li>Hide the page navigation links on the frontend for archive posts</li>
<li>Display any side bar widget on plugin pages using EMD Widget Area</li>
<li>Set custom CSS rules for all plugin pages including plugin shortcodes</li>
</ul></div></div><div style="margin-top:15px"><a href="#rtop" class="top">Go to top</a></div><hr style="margin-top:40px"><div class="changelog getting-started getting-started-18" style="margin:0"><div style="height:40px" id="gs-sec-18"></div><h2>How to limit access to issue entry and search forms by logged-in users only</h2><div id="gallery" class="wp-clearfix"><div class="sec-img gallery-item"><a class="thickbox tooltip" rel="gallery-18" href="https://emdsnapshots.s3.amazonaws.com/simcom-loginregform.png"><img src="https://emdsnapshots.s3.amazonaws.com/simcom-loginregform.png"></a></div></div><div class="sec-desc"><ol>
<li>Go to SIM COM menu > Settings page > Forms tab</li>
<li>Click on the form you want access to be limited by logged in users only</li>
<li>Locate Show Register / Login Form field and select from the dropdown which forms needs to show when non-logged-in users access to the form page</li>
<li>Click save changes and done</li>
</ol>

</div></div><div style="margin-top:15px"><a href="#rtop" class="top">Go to top</a></div><hr style="margin-top:40px"><div class="changelog getting-started getting-started-19" style="margin:0"><div style="height:40px" id="gs-sec-19"></div><h2>How to resolve theme related issues</h2><div id="gallery" class="wp-clearfix"></div><div class="sec-desc"><p>If your theme is not coded based on WordPress theme coding standards and does have an unorthodox markup, you might see some unussual things on your site such as sidebars not getting displayed where they are supposed to or random text getting displayed on headers etc. The good news is you may fix all of theme related conflicts following the steps in the documentation.</p>
<p>Please note that if you’re unfamiliar with code/templates and resolving potential conflicts, we strongly suggest to <a href="https://emdplugins.com/open-a-support-ticket/?pk_campaign=simcom-hireme">hire us</a> or a developer to complete the project for you.</p>
<p>
<a href="https://docs.emdplugins.com/docs/software-issue-manager-community-documentation/#section1470">Software Issue Manager Community Edition Documentation - Resolving theme related conflicts</a>
</p></div></div><div style="margin-top:15px"><a href="#rtop" class="top">Go to top</a></div><hr style="margin-top:40px">

<?php echo '</div>';
	echo '<div class="tab-content" id="tab-whats-new"';
	if ("whats-new" != $active_tab) {
		echo 'style="display:none;"';
	}
	echo '>';
?>
<p class="about-description">Software Issue Manager V4.0.0 offers many new features, bug fixes and improvements.</p>

<div class="wp-clearfix"><div class="changelog whats-new whats-new-5" style="margin:0"><h2 class="new"><div style="font-size:110%;color:#00C851"><span class="dashicons dashicons-megaphone"></span> NEW</div>
Easy Digital Downloads Software Issue Manager extension</h2><div ></a>* Added configuration for Easy Digital Downloads Software Issue Manager extension</div></div></div><hr style="margin:30px 0"><div class="wp-clearfix"><div class="changelog whats-new whats-new-6" style="margin:0"><h2 class="new"><div style="font-size:110%;color:#00C851"><span class="dashicons dashicons-megaphone"></span> NEW</div>
WooCommerce Software Issue Manager extension</h2><div ></a>* Added configuration for WooCommerce Software Issue Manager extension</div></div></div><hr style="margin:30px 0"><div class="wp-clearfix"><div class="changelog whats-new whats-new-7" style="margin:0"><h2 class="new"><div style="font-size:110%;color:#00C851"><span class="dashicons dashicons-megaphone"></span> NEW</div>
Interface consolidation</h2><div ></a>* Consolidated issues and projects under projects menu</div></div></div><hr style="margin:30px 0"><div class="wp-clearfix"><div class="changelog whats-new whats-new-8" style="margin:0"><h2 class="new"><div style="font-size:110%;color:#00C851"><span class="dashicons dashicons-megaphone"></span> NEW</div>
New templating system</h2><div ></a>* Ability to set page templates for issue and project single pages. Options are sidebar on left, sidebar on right or full width</div></div></div><hr style="margin:30px 0"><div class="wp-clearfix"><div class="changelog whats-new whats-new-9" style="margin:0"><h2 class="new"><div style="font-size:110%;color:#00C851"><span class="dashicons dashicons-megaphone"></span> NEW</div>
EMD Widget Area for all sidebar widgets</h2><div ></a>* EMD Widget area to display sidebar widgets in plugin pages</div></div></div><hr style="margin:30px 0"><div class="wp-clearfix"><div class="changelog whats-new whats-new-10" style="margin:0"><h2 class="new"><div style="font-size:110%;color:#00C851"><span class="dashicons dashicons-megaphone"></span> NEW</div>
Easy customization system</h2><div ></a>* Ability enable/disable any field, taxonomy and relationship from backend and/or frontend</div></div></div><hr style="margin:30px 0"><div class="wp-clearfix"><div class="changelog whats-new whats-new-11" style="margin:0"><h2 class="new"><div style="font-size:110%;color:#00C851"><span class="dashicons dashicons-megaphone"></span> NEW</div>
Custom Css area in settings</h2><div ></a>* Easily add site specific CSS rules in setting without getting affected by plugin updates</div></div></div><hr style="margin:30px 0"><div class="wp-clearfix"><div class="changelog whats-new whats-new-12" style="margin:0"><h2 class="new"><div style="font-size:110%;color:#00C851"><span class="dashicons dashicons-megaphone"></span> NEW</div>
Custom frontend login and registration forms</h2><div ></a>* Ability to limit Issue search and entry forms to logged-in users only from plugin settings</div></div></div><hr style="margin:30px 0"><div class="wp-clearfix"><div class="changelog whats-new whats-new-13" style="margin:0"><h2 class="new"><div style="font-size:110%;color:#00C851"><span class="dashicons dashicons-megaphone"></span> NEW</div>
Admin tools to permanently delete plugin data</h2><div ></a>* Added ability to permanently delete plugin related data from plugin settings</div></div></div><hr style="margin:30px 0"><div class="wp-clearfix"><div class="changelog whats-new whats-new-14" style="margin:0"><h2 class="new"><div style="font-size:110%;color:#00C851"><span class="dashicons dashicons-megaphone"></span> NEW</div>
Admin tools to recreate plugin pages</h2><div ></a>* Added ability to recreate installation pages from plugin settings</div></div></div><hr style="margin:30px 0">
<?php echo '</div>';
	echo '<div class="tab-content" id="tab-resources"';
	if ("resources" != $active_tab) {
		echo 'style="display:none;"';
	}
	echo '>';
?>
<div style="height:25px" id="ptop"></div><div class="toc"><h3 style="color:#0073AA;text-align:left;">Upgrade your game for better results</h3><ul><li><a href="#gs-sec-11">Extensive documentation is available</a></li>
<li><a href="#gs-sec-16">EMD CSV Import Export Extension</a></li>
<li><a href="#gs-sec-13">Software Issue Manager Pro - for small development teams</a></li>
<li><a href="#gs-sec-14">Software Issue Manager Enterprise - for large multi-role development teams</a></li>
<li><a href="#gs-sec-35">SIM WooCommerce and SIM Easy Digital Downloads extensions - tracking issues to products</a></li>
</ul></div><div class="changelog resources resources-11" style="margin:0"><div style="height:40px" id="gs-sec-11"></div><h2>Extensive documentation is available</h2><div id="gallery" class="wp-clearfix"></div><div class="sec-desc"><a href="https://docs.emdplugins.com/docs/software-issue-manager-community-documentation/">Software Issue Manager Community Edition Documentation</a></div></div><div style="margin-top:15px"><a href="#ptop" class="top">Go to top</a></div><hr style="margin-top:40px"><div class="changelog resources resources-16" style="margin:0"><div style="height:40px" id="gs-sec-16"></div><h2>EMD CSV Import Export Extension</h2><div id="gallery" class="wp-clearfix"><div class="sec-img gallery-item"><a class="thickbox tooltip" rel="gallery-16" href="https://emdsnapshots.s3.amazonaws.com/simcom-operations_large.png"><img src="https://emdsnapshots.s3.amazonaws.com/simcom-operations_540.png"></a></div></div><div class="sec-desc"><p>EMD CSV Import Export Extension allows bulk import/export/sync of issues, projects and their relationship information (including edd or woo products if corresponding extensions are purchased) from/to external systems using CSV files.</p>
<p><a href="https://emdplugins.com/plugins/emd-csv-import-export-extension/?pk_campaign=siment-buybtn&pk_kwd=simcom-resources"><img src="https://emd-plugins.s3.amazonaws.com/button_buy-now.png"></a></p></div></div><div style="margin-top:15px"><a href="#ptop" class="top">Go to top</a></div><hr style="margin-top:40px"><div class="changelog resources resources-13" style="margin:0"><div style="height:40px" id="gs-sec-13"></div><h2>Software Issue Manager Pro - for small development teams</h2><div id="gallery" class="wp-clearfix"><div class="sec-img gallery-item"><a class="thickbox tooltip" rel="gallery-13" href="https://emdsnapshots.s3.amazonaws.com/simcom-simprointro.png"><img src="https://emdsnapshots.s3.amazonaws.com/simcom-simprointro.png"></a></div></div><div class="sec-desc"><p>Software Issue Manager Professional provides project based issue management solution with built-in reports, dashboards, and advanced collaboration methods helping organizations move faster to issue resolutions.</p>
<p><a href="https://emdplugins.com/plugins/software-issue-manager-professional/?pk_campaign=simpro-buybtn&pk_kwd=simcom-resources"><img src="https://emd-plugins.s3.amazonaws.com/button_buy-now.png"></a></p></div></div><div style="margin-top:15px"><a href="#ptop" class="top">Go to top</a></div><hr style="margin-top:40px"><div class="changelog resources resources-14" style="margin:0"><div style="height:40px" id="gs-sec-14"></div><h2>Software Issue Manager Enterprise - for large multi-role development teams</h2><div id="gallery" class="wp-clearfix"><div class="sec-img gallery-item"><a class="thickbox tooltip" rel="gallery-14" href="https://emdsnapshots.s3.amazonaws.com/simcom-simentintro.png"><img src="https://emdsnapshots.s3.amazonaws.com/simcom-simentintro.png"></a></div></div><div class="sec-desc"><p>Software Issue Manager Enterprise provides project based 360-degree issue management with ability to create custom reports, built-in system and staff dashboards, built-in multi-role data access, time tracking and more.</p>
<p><a href="https://emdplugins.com/plugins/software-issue-manager-enterprise/?pk_campaign=siment-buybtn&pk_kwd=simcom-resources"><img src="https://emd-plugins.s3.amazonaws.com/button_buy-now.png"></a></p></div></div><div style="margin-top:15px"><a href="#ptop" class="top">Go to top</a></div><hr style="margin-top:40px"><div class="changelog resources resources-35" style="margin:0"><div style="height:40px" id="gs-sec-35"></div><h2>SIM WooCommerce and SIM Easy Digital Downloads extensions - tracking issues to products</h2><div id="gallery" class="wp-clearfix"><div class="sec-img gallery-item"><a class="thickbox tooltip" rel="gallery-35" href="https://emdsnapshots.s3.amazonaws.com/montage_sim_com_edd_woo_large.png"><img src="https://emdsnapshots.s3.amazonaws.com/montage_sim_com_edd_woo_540.png"></a></div></div><div class="sec-desc"><p>Software Issue Manager EDD and WooCommerce Extension enables development teams to track product related issues providing insight on the real cost of developing and supporting products.</p>


<div style="display: table">
<div style="display: table-row">
<div style="display: table-cell;padding-bottom: 22px;padding-right:40px;">
<a href="https://emdplugins.com/plugins/software-issue-manager-easy-digital-downloads-extension/?pk_campaign=simcom-buybtn&pk_kwd=simcom-resources">
<p>SIM Easy Digital Downloads extension</p>
<div><img src="https://emd-plugins.s3.amazonaws.com/button_buy-now.png"></div>
</a>
</div>

<div style="display: table-cell;padding-bottom: 22px;">
<a href="https://emdplugins.com/plugins/software-issue-manager-woocommerce-extension/?pk_campaign=simcom-buybtn&pk_kwd=simcom-resources">
<p>SIM WooCommerce extension</p>
<div><img src="https://emd-plugins.s3.amazonaws.com/button_buy-now.png">
</div></a>
</div>

</div>
</div></div></div><div style="margin-top:15px"><a href="#ptop" class="top">Go to top</a></div><hr style="margin-top:40px">
<?php echo '</div></div>';
}

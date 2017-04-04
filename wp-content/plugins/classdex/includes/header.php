<div class="wrap">
	<div class="navbar">
		<div class="navbar-inner"
			style="padding-right: 0px; padding-left: 0px;">
			<div class="container-fluid">
				<a class="brand" href="admin.php?page=classdex_home">ClassDex</a>
				<ul class="nav">
					<li><a href="admin.php?page=classdex_home"><i class="icon-home"></i></a></li>
					<li><a href="admin.php?page=classdex_classes&amp;t=2&amp;g=1"><i
							class="icon-book"></i> Classes</a></li>
					<li><a href="admin.php?page=classdex_browse"><i class="icon-list"></i>
							Browse</a></li>
		  <?php if ( current_user_can('manage_options') ) { ?>
 	 			<li><a href="admin.php?page=classdex_new_class"><i
							class="icon-lock"></i> Admin</a></li>
 		  <?php }  ?> 
		</ul>
				<form action="admin.php?page=classdex_search" method="post"
					class="navbar-form pull-right">
			<?php wp_nonce_field( 'classdex_search','classdex_search_nonce' ); ?>
			<input type="text" style="padding: 14px 12px;" name="keyword"
						value="" id="keyword" class="search-query"
						placeholder="Search Students"> <input type="submit" name="submit"
						id="submit" value="Search" class="btn">
				</form>
			</div>
		</div>
	</div>
	<div class="container-fluid"
		style="padding-right: 0px; padding-left: 0px;">
		<div class="row-fluid">
			<div class="span12">
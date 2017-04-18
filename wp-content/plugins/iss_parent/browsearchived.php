<?php global $wpdb; ?>
  <?php   $regyear = iss_registration_period(); ?>
    <?php require_once(ISS_PATH . "/includes/functions.php"); ?>

      <div class="wrap">
        <nav class="navbar navbar-light bg-faded">
          <strong class="navbar-brand">Archived Parents </strong>
          <ul class="nav navbar-nav">
            <li class="nav-item">
              <a class="page-link" href="<?php echo get_admin_url() . '?page=user_home'; ?>">
                <span class="button-primary">Change Registration Period: <?php if (isset($regyear)) echo $regyear; ?></span>
              </a>
            </li>
          </ul>
        </nav>
      </div>
      <div>
        <div class="row">

          <?php
if (isset ( $_GET ['aid'] )) {
    $aid = iss_sanitize_input ( $_GET ['aid'] );
    if (! empty ( $aid ))
        iss_archive_family ( $aid );
} else if (isset ( $_GET ['uid'] )) {
    $uid = iss_sanitize_input ( $_GET ['uid'] );
    if (! empty ( $uid ))
        iss_unarchive_family ( $uid );
}

$result_set = iss_get_archived_parents_list ( $regyear );

if (count ( $result_set ) == 0) {
    echo "<br><em>No results were found.</em><br> <a href=\"admin.php?page=parents_home\">Browse parents by last name.</a>";
} else {
    ?>
            <table class="table table-striped" id="data_table_simple">
              <thead>
                <tr>
                  <th>Last Name</th>
                  <th>First Names</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php    foreach($result_set as $row) { ?>
                  <tr>
                    <td><i class="dashicons dashicons-id"></i>
                      <?php echo $row['FatherLastName'];?>
                    </td>
                    <td>
                      <?php echo $row['FatherFirstName'];?>
                    </td>
                    <td>
                      <?php if (iss_current_user_is_secretery()) {    ?>
                        <a href="admin.php?page=archived_home&uid=<?php echo $row['ParentViewID'];?>"><span style="padding-left: 10px; white-space: nowrap;"><i class="glyphicon glyphicon-eye-open"></i> UnArchive </a></span>
                        <?php } ?>
                          <a href="admin.php?page=view_parent&id=<?php echo $row['ParentViewID'];?>"> <span style="padding-left: 10px; white-space: nowrap;"> <i class="glyphicon glyphicon-eye-open"></i> View </a></span>
                          <a href="admin.php?page=print_parent&id=<?php echo $row['ParentViewID'];?>"> <span style="padding-left: 10px; white-space: nowrap;"> <i class="glyphicon glyphicon-print"></i> Print</span></a>
                          <a href="admin.php?page=delete_parent&id=<?php echo $row['ParentViewID'];?>"> <span style="padding-left: 10px; white-space: nowrap;"><i class="glyphicon glyphicon-remove"></i> Delete</a></span>
                    </td>
                  </tr>
                  <?php  } ?>
              </tbody>
            </table>
            <?php
}
?>

              <script>
                jQuery(window).load(function() {
                  jQuery('#pleaseWaitDialog').hide();
                  $('table.iss_parent_table').DataTable();
                });
              </script>
              <?php require("includes/footer.php"); ?>
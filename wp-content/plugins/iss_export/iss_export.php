<?php

/*
 * Plugin Name: ISS Export to CSV
 * Description: Export Parents with students data to a csv file.
 * Version: 1.0.0
 * Author: Azra Syed
 * Text Domain: iss_export
 */

// load_plugin_textdomain( 'iss_export', false, basename( dirname( __FILE__ ) ) . '/languages' );

/**
 * Main plugin class
 *
 * @since 0.1
 *       
 */
class ISS_Export_Parents {
	private $regyear = null;
	/**
	 * Class contructor
	 *
	 * @since 0.1
	 *       
	 */
	public function __construct() {
		add_action ( 'admin_menu', array (
				$this,
				'add_admin_pages' 
		) );
		add_action ( 'init', array (
				$this,
				'generate_csv' 
		) );
	}
	public function load_custom_iss_style() {
		wp_register_script ( 'ecustom_iss_jquery_script', ISS_URL . '/js/jquery-1.12.4.js' );
		wp_enqueue_script ( 'ecustom_iss_jquery_script' );
		
		wp_register_script ( 'custom_iss_export_script', ISS_URL . '/js/multiselect.min.js' );
		wp_enqueue_script ( 'custom_iss_export_script' );
	}
	public function iss_load_admin_custom_css() {
		add_action ( 'admin_enqueue_scripts', 'load_custom_issv_style' );
	}
	/**
	 * Add administration menus
	 *
	 * @since 0.1
	 *       
	 */
	public function add_admin_pages() {
		$my_page = add_menu_page ( __ ( 'ExportParents', 'export-parents-to-csv' ), __ ( 'Export', 'export-parents-to-csv' ), 'iss_board', 'export-parents-to-csv', array (
				$this,
				'users_page' 
		), 'dashicons-download', 7 );
		add_action ( 'load-' . $my_page, 'iss_load_admin_custom_css' );
	}
	
	/**
	 * Process content of CSV file
	 *
	 * @since 0.1
	 *       
	 */
	public function generate_csv() {
		if (isset ( $_POST ['_wpnonce-iss-export-parents-page_export'] )) {
			check_admin_referer ( 'iss-export-parents-page_export', '_wpnonce-iss-export-parents-page_export' );
			
			$sitename = sanitize_key ( get_bloginfo ( 'name' ) );
			if (! empty ( $sitename ))
				$sitename .= '.';
			
			if (! isset ( $_POST ['RegistrationYear'] ) || empty ( $_POST ['RegistrationYear'] )) {
				echo '<div class="updated"><p><strong>' . __ ( 'Registration Year is required.', 'export-parents-to-csv' ) . '</strong></p></div>';
				return;
			} else if (! isset ( $_POST ['ISSGrade'] ) || empty ( $_POST ['ISSGrade'] )) {
				echo '<div class="updated"><p><strong>' . __ ( 'Islamic School Grade is required.', 'export-parents-to-csv' ) . '</strong></p></div>';
				return;
			} else if (isset ( $_POST ['ColumnArray'] ) && (count ( $_POST ['ColumnArray'] ) != 0)) {
				$this->regyear = iss_sanitize_input ( $_POST ['RegistrationYear'] );
				$class = iss_sanitize_input ( $_POST ['ISSGrade'] );
				$fields = $_POST ['ColumnArray'];
				
				$filename = /*$sitename . */ $this->regyear . 'Grade' . $class . date ( '.Ymd.His' ) . '.csv';
				header ( 'Content-Description: File Transfer' );
				header ( 'Content-Disposition: attachment; filename=' . $filename );
				header ( 'Content-Type: text/csv; charset=' . get_option ( 'blog_charset' ), true );
				
				iss_write_log ( "Export: RegistrationYear:{$this->regyear} Class:{$class}" );
				
				$rows = iss_get_export_list ( $this->regyear );
				
				foreach ( $fields as $key => $value ) {
					if (($value == 'RegistrationYear') || ($value == 'ParentID') || ($value == 'StudentID')) {
						unset ( $fields [$key] );
					}
				}
				
				echo implode ( ',', $fields ) . ",RegistrationYear,ParentID,StudentID\n";
				
				foreach ( $rows as $row ) {
					if (($class == 'All') || ($class == $row ['ISSGrade'])) {
						foreach ( $fields as $field ) {
							echo "{$row[$field]},";
						}
						echo "{$row['RegistrationYear']},{$row['ParentID']},{$row['StudentID']}\n";
					}
				}
			} else {
				$this->regyear = iss_sanitize_input ( $_POST ['RegistrationYear'] );
				$class = iss_sanitize_input ( $_POST ['ISSGrade'] );
				
				$filename = /*$sitename . */  $this->regyear . 'Grade' . $class . date ( '.Ymd.His' ) . '.csv';
				header ( 'Content-Description: File Transfer' );
				header ( 'Content-Disposition: attachment; filename=' . $filename );
				header ( 'Content-Type: text/csv; charset=' . get_option ( 'blog_charset' ), true );
				
				iss_write_log ( "Export: RegistrationYear:{$this->regyear} Class:{$class}" );
				$rows = iss_get_export_list ( $this->regyear );
				
				$head = false;
				foreach ( $rows as $row ) {
					unset ( $row ['ParentViewID'] );
					unset ( $row ['created'] );
					unset ( $row ['updated'] );
					
					if ($head == false) {
						foreach ( $row as $key => $value ) {
							echo "{$key},";
							$head = true;
						}
						echo "\n";
					}
					
					if (($class == 'All') || ($class == $row ['ISSGrade'])) {
						foreach ( $row as $key => $value ) {
							if (strpos ( $value, ',' ) !== false) {
								$value = iss_quote_all ( $value );
							}
							echo "{$value},";
						}
						echo "\n";
					}
				}
			}
			exit ();
		}
	}
	
	/**
	 * Content of the settings page
	 *
	 * @since 0.1
	 *       
	 */
	public function users_page() {
		if (! iss_current_user_on_board())
			wp_die ( __ ( 'You do not have sufficient permissions to access this page.', 'export-parents-to-csv' ) );
		?>

<div class="wrap">
	<h2><?php _e( 'Export Parents to a CSV file', 'export-parents-to-csv' ); ?></h2>
    <?php
		if (isset ( $_GET ['error'] )) {
			echo '<div class="updated"><p><strong>' . __ ( 'No user found.', 'export-parents-to-csv' ) . '</strong></p></div>';
		}
		if (NULL == $this->regyear) {
			$this->regyear = iss_registration_period ();
		}
		$regyearlist = iss_get_registrationyear_list ();
		?>
      <form method="post" action="" enctype="multipart/form-data">
        <?php wp_nonce_field( 'iss-export-parents-page_export', '_wpnonce-iss-export-parents-page_export' ); ?>
          <div class="row">
			<div class="col-sm-4 col-md-2">
				<label>Registration Year</label>
			</div>

			<div class="col-sm-4 col-md-2">
				<select name="RegistrationYear" id="RegistrationYear"
					class="form-control" title="Choose Registration Year" required>
					<option value "" selected>Select Registration Year</option>
                <?php foreach ($regyearlist as $ryear) { ?>
                  <option
						value="<?php echo $ryear['RegistrationYear'];?>"
						<?php echo ($this->regyear == $ryear['RegistrationYear']) ? ' selected' : '';?>>
                    <?php echo $ryear['RegistrationYear'];?>
                  </option>
                  <?php } ?>
              </select>
			</div>
			<div class=" col-sm-3 col-md-2">
				<input type="submit" class="button-primary"
					value="<?php _e( 'Export', 'export-parents-to-csv' ); ?>" /> <input
					type="hidden" name="_wp_http_referer"
					value="<?php echo $_SERVER['REQUEST_URI'] ?>" />
			</div>
		</div>
		<br />
		<div class="row">
			<div class="col-sm-4 col-md-2">
				<label>Islamic School Grade</label>
			</div>
			<div class="col-sm-4 col-md-2">
				<select name="ISSGrade" id="ISSGrade" class="form-control"
					title="Choose Class" required>
					<option value="All" selected>All</option>
                <?php
		
$issclasslist = array (
				'KG' => 'Kindergarten',
				'1' => 'Grade 1',
				'2' => 'Grade 2',
				'3' => 'Grade 3',
				'4' => 'Grade 4',
				'5' => 'Grade 5',
				'6' => 'Grade 6',
				'7' => 'Grade 7',
				'8' => 'Grade 8',
				'YB' => 'Youth Boys',
				'YG' => 'Youth Girls' 
		);
		foreach ( $issclasslist as $class => $name ) {
			echo "<option value=\"$class\">{$name}</option>";
		}
		?>
              </select>
			</div>

		</div>
		<hr />
		<div style="padding-bottom: 5px; font-weight: bold;">Select specific
			columns and order. By default all columns will be included.</div>
		<div class="row">
			<div class="col-sm-5 col-md-3">
				<select name="FromExport[]" id="multiselect" class="form-control"
					size="30" multiple="multiple">
					<optgroup label="Parent Information">
                  <?php
		
foreach ( iss_parent_tabfields () as $field ) {
			echo "<option value='{$field}'>{$field}</option>";
		}
		?>
                </optgroup>
					<optgroup label="Student Information">
                  <?php
		
foreach ( iss_student_fields () as $field ) {
			echo "<option value='{$field}'>{$field}</option>";
		}
		?>
                </optgroup>
					<optgroup label="Home Information">
                  <?php
		
foreach ( iss_home_tabfields () as $field ) {
			echo "<option value='{$field}'>{$field}</option>";
		}
		?>
                </optgroup>
					<optgroup label="Emergency Information">
                  <?php
		
foreach ( iss_contact_tabfields () as $field ) {
			echo "<option value='{$field}'>{$field}</option>";
		}
		?>
                </optgroup>
					<optgroup label="Payment Information">
                  <?php
		
foreach ( iss_payment_tabfields () as $field ) {
			echo "<option value='{$field}'>{$field}</option>";
		}
		?>
                </optgroup>
				</select>
			</div>

			<div class="col-sm-2 col-md-1">
				<button type="button" id="multiselect_rightAll"
					class="btn btn-block">
					<i class="glyphicon glyphicon-forward"></i>
				</button>
				<button type="button" id="multiselect_rightSelected"
					class="btn btn-block">
					<i class="glyphicon glyphicon-chevron-right"></i>
				</button>
				<button type="button" id="multiselect_leftSelected"
					class="btn btn-block">
					<i class="glyphicon glyphicon-chevron-left"></i>
				</button>
				<button type="button" id="multiselect_leftAll" class="btn btn-block">
					<i class="glyphicon glyphicon-backward"></i>
				</button>
			</div>

			<div class="col-sm-5 col-md-3">
				<select name="ColumnArray[]" id="multiselect_to"
					class="form-control" size="30" multiple="multiple"></select>

				<div class="row">
					<div class="col-sm-6">
						<button type="button" id="multiselect_move_up"
							class="btn btn-block">
							<i class="glyphicon glyphicon-arrow-up"></i>
						</button>
					</div>
					<div class="col-sm-6">
						<button type="button" id="multiselect_move_down"
							class="btn btn-block col-sm-6">
							<i class="glyphicon glyphicon-arrow-down"></i>
						</button>
					</div>
				</div>
			</div>
		</div>
		<p>Note: ParentID, StudentID and Registration year are added to every
			extract,</p>
		<p>these columns are needed in case the changes need to be made in
			exported file and imported back into the system.</p>
		<p>Please delete columns if don't need them.</p>

		<script type="text/javascript">
            jQuery(window).load(function() {
              $('#multiselect').multiselect({
                search: {
                  left: '<input type="text" name="q" class="form-control" placeholder="Search..." />',
                  right: '<input type="text" name="q" class="form-control" placeholder="Search..." />',
                }
              });
            });
          </script>


	</form>
</div>
<?php
	}
}
new ISS_Export_Parents ();
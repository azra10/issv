
</div>
</div>

</div>
<!--/.fluid-container-->
<script>
	$(document).ready( function () {
	    jQuery('#data_table').dataTable( {
	    	"aaSorting": [[ 1, "asc" ]],
	    	"bAutoWidth": false	 
	    });
	} );
	$(document).ready(function() {
	    jQuery('#data_table_simple').dataTable( {
	        "bPaginate": false,
	        "bLengthChange": false,
	        "bFilter": true,
	        "bSort": false,
	        "bInfo": false,
	        "bAutoWidth": false
	    } );
	} );
</script>
</div>
<!--wrap-->


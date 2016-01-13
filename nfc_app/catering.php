<!-- LOAD CATERING GROUPS -->
<?php
$db = pg_connect('host=nfcprojectinstance.cpx69rsmkoux.us-west-2.rds.amazonaws.com port=5432 dbname=nfcprojectdb user=root password=nfcproject'); 

// get menu items
$query = "SELECT cg.gpid, cg.name FROM catering_group cg";

$result = pg_query($query); 
if (!$result) { 
	$errormessage = pg_last_error(); 
	exit(); 
} 

$cg_table = array();
$num_results = 0;
while ($row = pg_fetch_row($result)) {
  array_push($cg_table, $row);
  $num_results++;
}

?>

<!-- LOAD MENU_ITEMS GROUPS -->
<?php

// get menu items
$query = "SELECT mi.iid, mi.name, mi.description, cg.gpid, cg.name, mi.price FROM menu_items mi, group_items gi, catering_group cg WHERE mi.iid=gi.iid AND gi.gpid=cg.gpid ORDER BY cg.gpid, mi.iid";

$result = pg_query($query); 
if (!$result) { 
	$errormessage = pg_last_error(); 
	exit(); 
} 

$mi_table = array();
$num_results = 0;
while ($row = pg_fetch_row($result)) {
  array_push($mi_table, $row);
  $num_results++;
}

?>



<!-- DISPLAY LIST OF CATERING -->
<form class="form-horizontal">
	<fieldset>
	  <legend>Select the catering group</legend>
	  	<select id="catering_selector" class="form-control">
	  		<option value=""></option>
			<?php
			while (!empty($cg_table)) {
				$row = array_shift($cg_table);
				echo "<option value='" . $row[0] . "'>" . $row[1] . "</option>";
			}
			?>
		</select>
</form>

<table class="table table-striped">
  <thead>
    <tr>
      <th>IID</th>
      <th>Item Name</th>
	  <th>Item Description</th>
	  <th>Unit Price</th>
	  <th>Quantity</th>
	  <th>Total price</th>
    </tr>
  </thead>

  <?php
	$gpid=0;
	$start=true;
	while (!empty($mi_table)) {


		$row = array_shift($mi_table);
		if($gpid != $row[3]) {
			$gpid=$row[3];
			if(start) {
				$start=false;
			} else {
				echo "</tbody>\n";
			}
			echo "<tbody hidden='hidden' name='table_gpid' id='table_gpid".$gpid."'>";
		}
		echo "<tr>\n";
		echo "  <td>".$row[0]."</td>\n";
		echo "  <td>".$row[1]."</td>\n";
		echo "  <td>".$row[2]."</td>\n";
		echo "  <td id='item_price_". $gpid . "_" . $row[0] . " val='".$row[5]."' >".$row[5]."</td>\n";

		echo '<td><form class="form-inline"><button name="decrease_quantity" type="button" class="btn btn-default">-</button>';
		echo '<input name="item_quantity" value=0 type="text" class="form-control">';
		echo '<button gpid_iid="'. $gpid . '_' . $row[0] . '" type="button" name="increase_quantity" class="btn btn-default">+</button></form></td>';

		echo '<td style="width: 20%">
				  <div class="form-group">
				    <div class="input-group">
				      <div class="input-group-addon">CHF</div>
				      <input readonly="readonly" type="text" class="form-control" name="item_subtotal" value="0.00">
				    </div>
				  </div></td>';

		echo "</tr>\n";
	}
	echo "</tbody>\n";

	?>

	<tfoot>
	    <tr>
	      <td></td>
	      <td></td>
		  <td></td>
		  <td></td>
		  <th>Total</th>
		  <td style="width: 20%">
				  <div class="form-group">
				    <div class="input-group">
				      <div class="input-group-addon">CHF</div>
				      <input readonly="readonly" type="text" class="form-control" id="total" value="0.00">
				    </div>
				  </div></td>
	    </tr>
	</tfoot>
</table>
<br/>
<button id="order" type="button" class="btn btn-primary btn-lg btn-block">Order</button>
<button id="clear_order" type="button" class="btn btn-default btn-lg btn-block">Clear</button>

<script>
$(document).ready(function() {
	// Display and hide catering table depending on selection
	$('#catering_selector').change(function() {
	  $('[name="table_gpid"]').attr('hidden','hidden');
	  var gpid = $( "#catering_selector option:selected" ).val();
	  $('#table_gpid'+gpid).removeAttr('hidden');

      	resetOrders();
	});

    $('[name="decrease_quantity"]').click(function(event) {
    	if($(this).next("input").val()>0) {
	    	var q=$(this).next("input");
    		q.val(parseInt(q.val())-1);

    		var quantity = parseInt(q.val());

	    	var price=parseFloat($(this).closest("td").prev().text());

	    	$(this).closest("td").next().find("input").val(price*quantity);
	    	updateTotal();
    	}
    });

    $('[name="increase_quantity"]').click(function(event) {
    	var q=$(this).prev("input");
    	q.val(parseInt(q.val())+1);

    	var quantity = parseInt(q.val());

    	var price=parseFloat($(this).closest("td").prev().text());

    	$(this).closest("td").next().find("input").val(price*quantity);
    	updateTotal();
    });

    $('[name="item_quantity"]').on('input', function() {
    	alert("Hello! I am an alert box!!");

    	
    	var q=parseInt($this.val());
    	
    });

    function updateTotal() {
    	var total = 0.0;
    	$('[name="item_subtotal"]').each(function() {
    		total += parseFloat($(this).val());
    	});

    	$('#total').val(total);
    }

    function resetOrders() {
    	$('[name="item_quantity"]').val("0")
    	$('[name="item_subtotal"]').val("0.00");
		$('#total').val("0.00");
    }

    $('#clear_order').click(function(event) {
        resetOrders();
    });

    $('#order').click(function(event) {
        // Create the order list....
    });
});
</script>

<?php
	pg_close();
?>
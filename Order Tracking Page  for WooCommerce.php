// Use Shortcode: [custom_order_tracking]

if (!function_exists('custom_order_tracking_form')) {
    function custom_order_tracking_form() {
        ob_start();
        ?>

<style>
.order_tracking_form {
    max-width: 600px;
    width: 100%;
    margin: 0px auto;
    box-shadow: #0071dc17 0px 12px 16px -5px;
    background: #ffffff;
    border: 1px solid #1b7fed2e;
    padding: 50px 40px;
    border-radius: 16px;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap:16px;
}
	
	.order_tracking_form label{
	   font-size: 28px;
   line-height: 36px;
   font-weight: 700;
   text-align: center;
   color: #000;	
	}	
	

.order_tracking_form .submit_btn{
  align-items: center;
  background-color: #2b8a3e;
  border:2px solid #2b8a3e;
  border-radius:4px;
  box-sizing: border-box;
  color: #FFF;
  cursor: pointer;
  display: flex;
  font-size: 16px;
  justify-content: center;
  line-height:100%;
  max-width: 100%;
  padding: 14px;
  text-align: center;
  transition: all 0.3s linear;	
 width:100%;	
}

	
.order_tracking_form .submit_btn:hover {
   background-color: transparent; 
   color:#2b8a3e;
}

.order_tracking_form .input_field{
 border:2px solid #2b8a3e73;
font-size: 15px;
padding:22px 12px;	
color:#000;	
	
	}

	
.order_status_table {
    margin-block: 50px !important;
    padding: 40px;
    box-shadow: #0071dc17 0px 12px 16px -5px;
    background: #ffffff;
    border: 1px solid #1b7fed2e;
    border-radius: 16px;
    max-width: 1000px;
    width: 100%;
    margin: 50px auto 0;
}
	
	.order_status_table h3{
	  font-size: 20px;
  line-height: 26px;
  font-weight: 700;
  text-align: center;
  margin: 0px 0px 25px;	
	}
	
	
	.order_status_table table{
	text-align: center;	
		margin:0px;
	}	
	
.order_status_table table td,
.order_status_table table th{
	 border: 1px solid #1b7fed2e;
	padding:12px 8px;
	text-align: center;	
	}	
.order_status_table table th{
	 font-size:15px;
  line-height: 22px;
  font-weight:700;	
color:#fff;	
	}
.order_status_table table td{
	 font-size:15px;
  line-height: 22px;
  font-weight:500;	
	color:#000;	
	}		
	
	
	@media(max-width:767px){
	.order_tracking_form {
    padding:30px;
     }	
		
		
		.order_tracking_form label {
    font-size: 20px;
    line-height: 26px;
}
		
	.order_tracking_form .input_field {
    padding: 20px 10px;
}	
		
	.order_tracking_form .submit_btn {
    font-size: 15px;
    padding: 12px;
}	
	
	.order_status_table {
    margin-block: 40px !important;
    padding: 30px 16px;
    margin: 40px auto 0;
}	
		
.order_status_table h3 {
    font-size:20px;
    line-height: 24px;
    margin: 0px 0px 18px;
}		
	
.order_status_table table td, 
.order_status_table table th {
    padding: 8px 6px;
}	
		
.order_status_table table th {
    font-size: 16px;
    line-height: 18px;
}		
	
.order_status_table table td {
   font-size: 16px;
  line-height: 18px;
}	
		
.order_status_table .status_table {
  width: 100%;
  overflow-x: auto;
 padding-bottom:5px;	
}	
	.order_status_table .status_table table {
    width: max-content;
}
		
		
		
	}
	
	
	
</style>

        <form method="post" class="order_tracking_form">
            <label for="order_info">আপনার অর্ডার ট্র্যাক করুন</label>
            <input class="input_field" type="text" name="order_info" required placeholder="আপনার অর্ডার নম্বর বা ফোন নাম্বার লিখুন"/>
            <input class="submit_btn" type="submit" name="track_order" value="ট্র্যাক করুন"/>
        </form>
        <?php

        if (isset($_POST['track_order'])) {
            $input = sanitize_text_field($_POST['order_info']);
            custom_track_order_status($input);
        }

        return ob_get_clean();
    }
    add_shortcode('custom_order_tracking', 'custom_order_tracking_form');
}

if (!function_exists('custom_track_order_status')) {
    function custom_track_order_status($input) {
        $args = array(
            'limit' => -1,
            'orderby' => 'date',
            'order' => 'DESC',
            'status' => array_keys(wc_get_order_statuses()),
        );

        $orders = wc_get_orders($args);
        $matched_orders = [];

        foreach ($orders as $order) {
            $order_id = $order->get_id();
            $billing_phone = $order->get_billing_phone();

            if ($input == $order_id || $input == $billing_phone) {
                $matched_orders[] = $order;
            }
        }

        if (count($matched_orders) > 0) {
			echo "<div class='order_status_table'>";
            echo "<h3>মোট ".count($matched_orders)." টি অর্ডার পাওয়া গেছে!</h3>";
			echo "<div class='status_table'>";
            echo "<table>
                    <thead>
                        <tr style='background: #2b8a3e;'>
                            <th>অর্ডার নম্বর</th>
                            <th>তারিখ</th>
                            <th>স্ট্যাটাস</th>
                            <th>মোট টাকা</th>
                        </tr>
                    </thead>
                    <tbody>";
            foreach ($matched_orders as $order) {
                echo "<tr>
                        <td>#".$order->get_id()."</td>
                        <td>".$order->get_date_created()->date('d-m-Y')."</td>
                        <td>".wc_get_order_status_name($order->get_status())."</td>
                        <td>".$order->get_formatted_order_total()."</td>
                    </tr>";
            }
            echo "</tbody></table>";
			echo "</div>";
			echo "</div>";
        } else {
            echo "<p style='color: red; text-align: center; font-size: 16px; font-weight: 700;'>❌ কোনো অর্ডার পাওয়া যায়নি। অনুগ্রহ করে সঠিক তথ্য দিন।</p>";
        }
    }
}

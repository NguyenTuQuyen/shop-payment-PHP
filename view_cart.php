<?php
session_start();
include_once("config.php");
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Xem giỏ hàng</title>
<link href="style/style.css" rel="stylesheet" type="text/css">

</head>
<body>
<h1 align="center">Giỏ hàng</h1>
<div class="cart-view-table-back">
<form method="post" action="cart_update.php">
<table width="100%"  cellpadding="6" cellspacing="0"><thead><tr><th>Số lượng</th><th>Tên hàng</th><th>Giá</th><th>Tổng</th><th>Xóa</th></tr></thead>
  <tbody>
 	<?php
	if(isset($_SESSION["cart_products"])) //check session var
    {
		$total = 0; //set initial total value
		$b = 0; //var for zebra stripe table 
		foreach ($_SESSION["cart_products"] as $cart_itm)
        {
			//set variables to use in content below
			$product_name = $cart_itm["product_name"];
			$product_qty = $cart_itm["product_qty"];
			$product_price = $cart_itm["product_price"];
			$product_code = $cart_itm["product_code"];
			$product_color = $cart_itm["product_color"];
			$subtotal = ($product_price * $product_qty); //calculate Price x Qty
			
		   	$bg_color = ($b++%2==1) ? 'odd' : 'even'; //class for zebra stripe 
		    echo '<tr class="'.$bg_color.'">';
			echo '<td><input type="text" size="2" maxlength="2" name="product_qty['.$product_code.']" value="'.$product_qty.'" /></td>';
			echo '<td>'.$product_name.'</td>';
			echo '<td>'.$currency.$product_price.'</td>';
			echo '<td>'.$currency.$subtotal.'</td>';
			echo '<td><input type="checkbox" name="remove_code[]" value="'.$product_code.'" /></td>';
            echo '</tr>';
			$total = ($total + $subtotal); //add subtotal to total var
        }
		
		$grand_total = $total + $shipping_cost; //grand total including shipping cost
		foreach($taxes as $key => $value){ //list and calculate all taxes in array
				$tax_amount     = round($total * ($value / 100));
				$tax_item[$key] = $tax_amount;
				//$grand_total    = $grand_total + $tax_amount;  //add tax val to grand total
		}
		
		$list_tax       = '';
		//foreach($tax_item as $key => $value){ //List all taxes
		//	$list_tax .= $key. ' : '. $currency. sprintf("%01.2f", $value).'<br ///>';
		//}
		$shipping_cost = ($shipping_cost)?'Phí ship : '.$currency. sprintf("%01.2f", $shipping_cost).'<br />':'';
	}
    ?>
    <tr><td colspan="5"><span style="float:right;text-align: right;"><?php echo $shipping_cost. $list_tax; ?>Số tiền : <?php echo sprintf("%01.2f", $grand_total);?></span></td></tr>
    <tr><td colspan="5"><a href="index.php" class="button">Thêm sản phẩm</a><button type="">Cập nhật</button></td>
    
    </td></tr>
 
 
  </tbody>
</table>

<input type="hidden" name="return_url" value="<?php 
$current_url = urlencode($url="http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
echo $current_url; ?>" />
</form>

<form method="post" action="" onsubmit="return check();">
<table width="100%"  cellpadding="6" cellspacing="0">
    <thead>
    <tr>
        <th>Thanh toán qua cổng nội địa</th>
    </tr>
    </thead>
    <tbody>
       <tr>
        <td colspan="2">
            <span style="width:100px; display:inline-block">Tên:</span><input type="text" name="name" value="" />
        </td>
    </tr>
    <tr>
        <td >
            <span style="width:100px; display:inline-block">SĐT:</span><input type="number" name="phone" value="" />
        </td>
    </tr>
    <tr>
        <td >
            <span style="width:100px; display:inline-block">Email:</span><input type="email" name="email" value="" />
        </td>
    </tr>
    <tr>
        <td >
            <span style="width:100px; display:inline-block">Địa chỉ:</span><input type="text" name="address" value="" />
        </td>
    </tr>
    <tr>
        <td>
            <span style="width:100px; display:inline-block" >Phương thức thanh toán:</span>
        </td>
    </tr>
    <tr >
        <td  >

            <input type="radio" name="payment-method" id="input" value="1" checked >
                Trả khi nhận hàng</input></br>
        </td>
    </tr>
    <tr>
        <td>
            <input type="radio" name="payment-method" id="input" value="2" >
                Thanh toán bằng ví Momo</input></br>
        </td>
    </tr>
    <tr>
        <td>
           <input type="radio" name="payment-method" id="input" value="3">
                Thanh toán bằng Ngân Lượng</input></br> 
        </td>
    </tr>

    <tr>
        <td>
            <p><input class="button" style="background:red" type="submit" value="Thanh toán" name="checkout" /></p>
        </td>
    </tr>
    </tbody>
</table>
</form>
<table width="100%"  cellpadding="6" cellspacing="0">
    <thead>
    <tr>
        <th>Thanh toán bằng qua cổng quốc tế (Paypal)</th>
    </tr>
    </thead>
</table>




	<!--<form action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post" style="padding: 30px 10px;"> -->
	<form action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post" style="padding: 30px 10px;"  >

            <!-- Nhập địa chỉ email người nhận tiền (người bán) -->
            <input type="hidden" name="business" value="nguoiban@hocitonline.com">

            <!-- tham số cmd có giá trị _xclick chỉ rõ cho paypal biết là người dùng nhất nút thanh toán -->
            <input type="hidden" name="cmd" value="_xclick">

            <!-- Thông tin mua hàng. -->
            <input type="hidden" name="item_name" value="HoaDonMuaHang">
			<!--Trị giá của giỏ hàng, vì paypal không hỗ trợ tiền việt nên phải đổi ra tiền $-->
            Số tiền hóa đơn : <input style="border: 0px; text-align: center; font-size: 24px; font-weight: bolder; color: red;width: 261px; " type="number" name="amount" value="<?php echo sprintf("%01.2f", sprintf("%01.2f", $grand_total)/23255)?>"> USD
			<!--Loại tiền-->
            <input type="hidden" name="currency_code" value="USD">
			<!--Đường link mình cung cấp cho Paypal biết để sau khi xử lí thành công nó sẽ chuyển về theo đường link này-->
            <input type="hidden" name="return" value="http://3dbuilder.vn/view_cart.php">
			<!--Đường link mình cung cấp cho Paypal biết để nếu  xử lí KHÔNG thành công nó sẽ chuyển về theo đường link này-->
            <input type="hidden" name="cancel_return" value="http://3dbuilder.vn/view_cart.php">
            <!-- Nút bấm. -->
            <input style="font-size: 15px; background: red; border: 2px solid #fff; color: #fff; padding: 10px; border-radius: 5px; float:right" type="submit" name="paypal" value="Thanh toán qua Paypal"   >
 

	</form>
</div>
</body>
</html>
<?php
include 'config-nganluong.php';
include 'lib/nganluong.class.php';

ob_start();



$name = $_POST['name'];
$phone = $_POST['phone'];
$email = $_POST['email'];
$address = $_POST['address'];
$radioVal = $_POST["payment-method"];
    if(isset($_POST['checkout'])){
    if($name == null || $phone == null || $email == null || $address == null){
        $message = "Please fill all form!";
        echo "<script type='text/javascript'>alert('$message');</script>";
    }
    else{
        
        if($radioVal == 1){
            session_destroy();
            $message = "Payment success!";
            echo "<script type='text/javascript'>Promt('$message');</script>";
            header('Location: /');
        }
        elseif($radioVal == 2){
        //---------pay with momo---------
        function execPostRequest($url, $data)
       {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data))
        );
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    
        //execute post
        $result = curl_exec($ch);
    
        //close connection
        curl_close($ch);
    
        return $result;
       }
       $endpoint = "https://test-payment.momo.vn/gw_payment/transactionProcessor";
       $partnerCode = "MOMOODIN20190427";
       $accessKey = "FAHTbQyHjkfY5884";
       $serectkey = "VjkYj1oFCAwYRjE3HpP3Rb8X4oshKKQq";
       $orderInfo = "pay with MoMo";
       $returnUrl = "https://momo.vn/return";
       $notifyurl = "https://dummy-url.vn/notify";
       $amount = "$grand_total";
       $orderid = time()."";
       $requestId = time()."";
       $requestType = "captureMoMoWallet";
       $extraData = "merchantName=;merchantId=";//pass empty value if your merchant does not have stores else merchantName=[storeName]; merchantId=[storeId] to identify a transaction map with a physical store
       //before sign HMAC SHA256 signature
       $rawHash = "partnerCode=".$partnerCode."&accessKey=".$accessKey."&requestId=".$requestId."&amount=".$amount."&orderId=".$orderid."&orderInfo=".$orderInfo."&returnUrl=".$returnUrl."&notifyUrl=".$notifyurl."&extraData=".$extraData;
       echo "Raw signature: ".$rawHash."\n";
       $signature = hash_hmac("sha256", $rawHash, $serectkey);
    
       $data =  array('partnerCode' => $partnerCode,
                      'accessKey' => $accessKey,
                      'requestId' => $requestId,
                      'amount' => $amount,
                      'orderId' => $orderid,
                      'orderInfo' => $orderInfo,
                      'returnUrl' => $returnUrl,
                      'notifyUrl' => $notifyurl,
                      'extraData' => $extraData,
                      'requestType' => $requestType,
                      'signature' => $signature);
       echo "Data send to MoMo: \n";
       print_r(json_encode($data));
       echo "\n";
    
       $result = execPostRequest($endpoint, json_encode($data));
       $jsonResult =json_decode($result,true);  // decode json
       echo "Result: \n";
       print_r($jsonResult);
       
       echo "-------------";
       $url = $jsonResult[payUrl];
       //echo $url;
       header("Location:".$url);
       
        session_destroy();
            }
        //-----end pay with momo---------

        elseif($radioVal == 3){
        //----Payment with Ngan Luong-----
        // Lấy các tham số để chuyển sang Ngânlượng thanh toán:

        //$ten= $_POST["txt_test"];
        $receiver=RECEIVER;
    	//Mã đơn hàng 
    	$order_code='NL_'.time();
    	//Khai báo url trả về 
    	$return_url= $_SERVER['HTTP_REFERER']. "/success.php";
    	// Link nut hủy đơn hàng
    	$cancel_url= $_SERVER['HTTP_REFERER'];	
    	//Giá của cả giỏ hàng 
    	$txh_name =$_POST['name']; 	
    	$txt_email =$_POST['email']; 	
    	$txt_phone =$_POST['phone']; 	
    	$price =$grand_total; 	
    	//Thông tin giao dịch
    	$transaction_info="Thong tin giao dich";
    	$currency= "vnd";
    	$quantity=1;
    	$tax=0;
    	$discount=0;
    	$fee_cal=0;
    	$fee_shipping=0;
    	$order_description="Thong tin don hang: ".$order_code;
    	$buyer_info=$txh_name."*|*".$txt_email."*|*".$txt_phone;
    	$affiliate_code="";
        //Khai báo đối tượng của lớp NL_Checkout
    	$nl= new NL_Checkout();
    	$nl->nganluong_url = NGANLUONG_URL;
    	$nl->merchant_site_code = MERCHANT_ID;
    	$nl->secure_pass = MERCHANT_PASS;
    	//Tạo link thanh toán đến nganluong.vn
    	$url= $nl->buildCheckoutUrlExpand($return_url, $receiver, $transaction_info, $order_code, $price, $currency, $quantity, $tax, $discount , $fee_cal,    $fee_shipping, $order_description, $buyer_info , $affiliate_code);
    	//$url= $nl->buildCheckoutUrl($return_url, $receiver, $transaction_info, $order_code, $price);
    	
    	
    	//echo $url; die;
    	if ($order_code != "") {
    		//một số tham số lưu ý
    		//&cancel_url=http://yourdomain.com --> Link bấm nút hủy giao dịch
    		//&option_payment=bank_online --> Mặc định forcus vào phương thức Ngân Hàng
    		$url .='&cancel_url='. $cancel_url;
    		//$url .='&option_payment=bank_online';
    		
    		echo '<meta http-equiv="refresh" content="0; url='.$url.'" >';
    		//&lang=en --> Ngôn ngữ hiển thị google translate
    	}
    	
    	 session_destroy();
        }
        //---end payment with Ngan luong--------

    }
    }
    
ob_end_flush();
?>
<script type="text/javascript">
//script Ngan luong
function check(){
		var price = document.Test.txt_gia.value;
		
		if (price < 2000) {
		
		alert('Minimum amount is 2000 VNĐ');
		return false;
		}
		
	return true;	
}

</script>
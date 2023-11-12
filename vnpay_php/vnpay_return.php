

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <meta name="description" content="">
        <meta name="author" content="">
        <title>VNPAY RESPONSE</title>
        <!-- Bootstrap core CSS -->
        <link href="/vnpay_php/assets/bootstrap.min.css" rel="stylesheet"/>
        <!-- Custom styles for this template -->
        <link href="/vnpay_php/assets/jumbotron-narrow.css" rel="stylesheet">         
        <script src="/vnpay_php/assets/jquery-1.11.3.min.js"></script>
    </head>
    <body>
        <?php
        require_once("./config.php");
        ob_start();
        session_start();
        require_once('../admin/inc/config.php');
        $vnp_SecureHash = $_GET['vnp_SecureHash'];
        $payment_date = date('Y-m-d H:i:s');
        $inputData = array();
        foreach ($_GET as $key => $value) {
            if (substr($key, 0, 4) == "vnp_") {
                $inputData[$key] = $value;
            }
        }
        
        unset($inputData['vnp_SecureHash']);
        ksort($inputData);
        $i = 0;
        $hashData = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashData = $hashData . '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashData = $hashData . urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
        }

        $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);
        ?>
        <!--Begin display -->
        <div class="container">
            <div class="header clearfix">
                <h3 class="text-muted">VNPAY RESPONSE</h3>
            </div>
            <div class="table-responsive">
                <div class="form-group">
                    <label >Mã đơn hàng:</label>

                    <label><?php echo $_GET['vnp_TxnRef'] ?></label>
                </div>    
                <div class="form-group">

                    <label >Số tiền:</label>
                    <label><?php echo $_GET['vnp_Amount']/100 ?></label>
                </div>  
                <div class="form-group">
                    <label >Nội dung thanh toán:</label>
                    <label><?php echo $_GET['vnp_OrderInfo'] ?></label>
                </div> 
                <div class="form-group">
                    <label >Mã phản hồi (vnp_ResponseCode):</label>
                    <label><?php echo $_GET['vnp_ResponseCode'] ?></label>
                </div> 
                <div class="form-group">
                    <label >Mã GD Tại VNPAY:</label>
                    <label><?php echo $_GET['vnp_TransactionNo'] ?></label>
                </div> 
                <div class="form-group">
                    <label >Mã Ngân hàng:</label>
                    <label><?php echo $_GET['vnp_BankCode'] ?></label>
                </div> 
                <div class="form-group">
                    <label >Thời gian thanh toán:</label>
                    <label><?php echo $_GET['vnp_PayDate'] ?></label>
                </div> 
                <div class="form-group">
                    <label >Kết quả:</label>
                    <label>
                        <?php
                            if ($secureHash == $vnp_SecureHash) {
                                if ($_GET['vnp_ResponseCode'] == '00') {
                                    $statement = $pdo->prepare("INSERT INTO tbl_payment (
                                        customer_id,
                                        customer_name,
                                        customer_email,
                                        payment_date,
                                        txnid, 
                                        paid_amount,
                                        card_number,
                                        card_cvv,
                                        card_month,
                                        card_year,
                                        bank_transaction_info,
                                        payment_method,
                                        payment_status,
                                        tss_id,
                                        payment_id
                                        ) 
                                        VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
                            
                                    $sql = $statement->execute(array(
                                        $_SESSION['customer']['cust_id'],
                                        $_SESSION['customer']['cust_name'],
                                        $_SESSION['customer']['cust_email'],
                                        $payment_date,
                                        $_GET['vnp_TxnRef'],
                                        $_GET['vnp_Amount']/100,
                                        '',
                                        '',
                                        '',
                                        '',
                                        '',
                                        'VNPAY',
                                        'Completed',
                                        1,

                                        $_GET['vnp_TransactionNo']
                                    ));
                
                                    $i=0;
                                    foreach($_SESSION['cart_p_id'] as $key => $value) 
                                    {
                                        $i++;
                                        $arr_cart_p_id[$i] = $value;
                                    }
                                
                                    $i=0;
                                    foreach($_SESSION['cart_p_name'] as $key => $value) 
                                    {
                                        $i++;
                                        $arr_cart_p_name[$i] = $value;
                                    }
                                
                                    $i=0;
                                    foreach($_SESSION['cart_size_name'] as $key => $value) 
                                    {
                                        $i++;
                                        $arr_cart_size_name[$i] = $value;
                                    }
                                
                                    $i=0;
                                    foreach($_SESSION['cart_color_name'] as $key => $value) 
                                    {
                                        $i++;
                                        $arr_cart_color_name[$i] = $value;
                                    }
                                
                                    $i=0;
                                    foreach($_SESSION['cart_p_qty'] as $key => $value) 
                                    {
                                        $i++;
                                        $arr_cart_p_qty[$i] = $value;
                                    }
                                
                                    $i=0;
                                    foreach($_SESSION['cart_p_current_price'] as $key => $value) 
                                    {
                                        $i++;
                                        $arr_cart_p_current_price[$i] = $value;
                                    }
                                
                                
                                    $i=0;
                                    $statement = $pdo->prepare("SELECT * FROM tbl_product");
                                    $statement->execute();
                                    $result = $statement->fetchAll(PDO::FETCH_ASSOC);							
                                    foreach ($result as $row) {
                                        $i++;
                                        $arr_p_id[$i] = $row['p_id'];
                                        $arr_p_qty[$i] = $row['p_qty'];
                                    }
                                
                                
                                    for($i=1;$i<=count($arr_cart_p_name);$i++) {
                                        $statement = $pdo->prepare("INSERT INTO tbl_order (
                                                        product_id,
                                                        product_name,
                                                        size, 
                                                        color,
                                                        quantity, 
                                                        unit_price, 
                                                        payment_id
                                                        ) 
                                                        VALUES (?,?,?,?,?,?,?)");
                                        $sql = $statement->execute(array(
                                                        $arr_cart_p_id[$i],
                                                        $arr_cart_p_name[$i],
                                                        $arr_cart_size_name[$i],
                                                        $arr_cart_color_name[$i],
                                                        $arr_cart_p_qty[$i],
                                                        $arr_cart_p_current_price[$i],
                                                        $_GET['vnp_TransactionNo']
                                                    ));
                                
                                        // Update the stock
                                        for($j=1;$j<=count($arr_p_id);$j++)
                                        {
                                            if($arr_p_id[$j] == $arr_cart_p_id[$i]) 
                                            {
                                                $current_qty = $arr_p_qty[$j];
                                                break;
                                            }
                                        }
                                        $final_quantity = $current_qty - $arr_cart_p_qty[$i];
                                        $statement = $pdo->prepare("UPDATE tbl_product SET p_qty=? WHERE p_id=?");
                                        $statement->execute(array($final_quantity,$arr_cart_p_id[$i]));
                                
                                    }
                                    unset($_SESSION['cart_p_id']);
                                    unset($_SESSION['cart_size_id']);
                                    unset($_SESSION['cart_size_name']);
                                    unset($_SESSION['cart_color_id']);
                                    unset($_SESSION['cart_color_name']);
                                    unset($_SESSION['cart_p_qty']);
                                    unset($_SESSION['cart_p_current_price']);
                                    unset($_SESSION['cart_p_name']);
                                    unset($_SESSION['cart_p_featured_photo']);
                                    echo "<span style='color:blue'>Ban da thanh toan thanh cong</span>";
                                    $to=$_SESSION['customer']['cust_email'];
        
                                    $subject = "Bạn đã đặt hàng thành công";
                                    $verify_link = BASE_URL.'customer-order.php';
                                    $message = "Xin chào <strong>".$_SESSION['customer']['cust_name']."</strong>,<br /><br />Bạn đã đặt hàng thành công. Vui lòng click vào link bên dưới để xem chi tiết đơn hàng.<br /><br /><a href=".$verify_link.">Xem đơn hàng</a><br /><br />Thanks<br />VNPAY";
                            
                                    $headers = "From: noreply@" . BASE_URL . "\r\n" .
                                               "Reply-To: noreply@" . BASE_URL . "\r\n" .
                                               "X-Mailer: PHP/" . phpversion() . "\r\n" . 
                                               "MIME-Version: 1.0\r\n" . 
                                               "Content-Type: text/html; charset=UTF-8\r\n";
                                    
                                    // Sending Email
                                    mail($to, $subject, $message, $headers);
                                    header("refresh:3;url=../dashboard.php");
                                    exit();
                                } else {
                                    echo "<span style='color:red'>GD Khong thanh cong</span>";
                                }
                            } else {
                                echo "<span style='color:red'>Chu ky khong hop le</span>";
                            }
                        ?>

                    </label>
                </div> 
            </div>
            <p>
                &nbsp;
            </p>
            <footer class="footer">
                   <p>&copy; VNPAY <?php echo date('Y')?></p>
            </footer>
        </div>  
    </body>
</html>



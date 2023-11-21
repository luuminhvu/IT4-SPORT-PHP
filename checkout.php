<?php require_once('header.php'); ?>

<?php
$statement = $pdo->prepare("SELECT * FROM tbl_settings WHERE id=1");
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);                            
foreach ($result as $row) {
    $banner_checkout = $row['banner_checkout'];
}
?>

<?php
if(!isset($_SESSION['cart_p_id'])) {
    header('location: cart.php');
    exit;
}
?>

<div class="page-banner" style="background-image: url(assets/uploads/<?php echo $banner_checkout; ?>)">
    <div class="overlay"></div>
    <div class="page-banner-inner">
        <h1><?php echo  $languages[22]; ?></h1>
    </div>
</div>

<div class="page">
    <div class="container">
        <div class="row">
            <div class="col-md-12">

                <?php if(!isset($_SESSION['customer'])): ?>
                <p>
                    <a href="login.php" class="btn btn-md btn-danger"><?php echo  $languages[160]; ?></a>
                </p>
                <?php else: ?>

                <h3 class="special"><?php echo  $languages[26]; ?></h3>
                <div class="cart">
                    <table class="table table-responsive table-hover table-bordered">
                        <tr>
                            <th><?php echo '#' ?></th>
                            <th><?php echo  $languages[8]; ?></th>
                            <th><?php echo  $languages[47]; ?></th>
                            <th><?php echo  $languages[157]; ?></th>
                            <th><?php echo  $languages[158]; ?></th>
                            <th><?php echo  $languages[159]; ?></th>
                            <th><?php echo  $languages[55]; ?></th>
                            <th class="text-right"><?php echo  $languages[82]; ?></th>
                        </tr>
                        <?php
                        $table_total_price = 0;

                        $i=0;
                        foreach($_SESSION['cart_p_id'] as $key => $value) 
                        {
                            $i++;
                            $arr_cart_p_id[$i] = $value;
                        }

                        $i=0;
                        foreach($_SESSION['cart_size_id'] as $key => $value) 
                        {
                            $i++;
                            $arr_cart_size_id[$i] = $value;
                        }

                        $i=0;
                        foreach($_SESSION['cart_size_name'] as $key => $value) 
                        {
                            $i++;
                            $arr_cart_size_name[$i] = $value;
                        }

                        $i=0;
                        foreach($_SESSION['cart_color_id'] as $key => $value) 
                        {
                            $i++;
                            $arr_cart_color_id[$i] = $value;
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
                        foreach($_SESSION['cart_p_name'] as $key => $value) 
                        {
                            $i++;
                            $arr_cart_p_name[$i] = $value;
                        }

                        $i=0;
                        foreach($_SESSION['cart_p_featured_photo'] as $key => $value) 
                        {
                            $i++;
                            $arr_cart_p_featured_photo[$i] = $value;
                        }
                        ?>
                        <?php for($i=1;$i<=count($arr_cart_p_id);$i++): ?>
                        <tr>
                            <td><?php echo $i; ?></td>
                            <td>
                                <img src="assets/uploads/<?php echo $arr_cart_p_featured_photo[$i]; ?>" alt="">
                            </td>
                            <td><?php echo $arr_cart_p_name[$i]; ?></td>
                            <td><?php echo $arr_cart_size_name[$i]; ?></td>
                            <td><?php echo $arr_cart_color_name[$i]; ?></td>
                            <td><?php echo $arr_cart_p_current_price[$i];?><?php echo  $languages[1]; ?></td>
                            <td><?php echo $arr_cart_p_qty[$i]; ?></td>
                            <td class="text-right">
                                <?php
                                $row_total_price = $arr_cart_p_current_price[$i]*$arr_cart_p_qty[$i];
                                $table_total_price = $table_total_price + $row_total_price;
                                ?>
                                <?php echo $row_total_price; ?><?php echo  $languages[1]; ?>
                            </td>
                        </tr>
                        <?php endfor; ?>
                        <tr>
                            <th colspan="7" class="total-text"><?php echo  $languages[81]; ?></th>
                            <th class="total-amount"><?php echo $table_total_price; ?><?php echo  $languages[1]; ?></th>
                        </tr>
                        <?php
                        $statement = $pdo->prepare("SELECT * FROM tbl_shipping_cost WHERE country_id=?");
                        $statement->execute(array($_SESSION['customer']['cust_s_country']));
                        $total = $statement->rowCount();
                        if($total) {
                            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                            foreach ($result as $row) {
                                $shipping_cost = $row['amount'];
                            }
                        } else {
                            $statement = $pdo->prepare("SELECT * FROM tbl_shipping_cost_all WHERE sca_id=1");
                            $statement->execute();
                            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                            foreach ($result as $row) {
                                $shipping_cost = $row['amount'];
                            }
                        }              
                        ?>
                        <tr>
                            <td colspan="7" class="total-text"><?php echo  $languages[84]; ?></td>
                            <td class="total-amount"><?php echo $shipping_cost; ?><?php echo  $languages[1]; ?></td>
                        </tr>
                        <tr>
                            <th colspan="7" class="total-text"><?php echo  $languages[82]; ?></th>
                            <th class="total-amount">
                                <?php
                                $final_total = $table_total_price+$shipping_cost;
                                ?>
                                <?php echo $final_total ?><?php echo  $languages[1]; ?>
                            </th>
                        </tr>
                    </table>
                </div>



                <div class="billing-address">
                    <div class="row">
                        <div class="col-md-6">
                            <h3 class="special"><?php echo  $languages[161]; ?></h3>
                            <table class="table table-responsive table-bordered table-hover table-striped bill-address">
                                <tr>
                                    <td><?php echo  $languages[102]; ?></td>
                                    <td><?php echo $_SESSION['customer']['cust_b_name']; ?></p>
                                    </td>
                                </tr>
                                
                                <tr>
                                    <td><?php echo  $languages[104]; ?></td>
                                    <td><?php echo $_SESSION['customer']['cust_b_phone']; ?></td>
                                </tr>
                                <tr>
                                    <td><?php echo  $languages[106]; ?></td>
                                    <td>
                                        <?php
                                        $statement = $pdo->prepare("SELECT * FROM tbl_country WHERE country_id=?");
                                        $statement->execute(array($_SESSION['customer']['cust_b_country']));
                                        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                                        foreach ($result as $row) {
                                            echo $row['country_name'];
                                        }
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td><?php echo  $languages[105]; ?></td>
                                    <td>
                                        <?php echo nl2br($_SESSION['customer']['cust_b_address']); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td><?php echo  $languages[107]; ?></td>
                                    <td><?php echo $_SESSION['customer']['cust_b_city']; ?></td>
                                </tr>
                                
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h3 class="special"><?php echo  $languages[162]; ?></h3>
                            <table class="table table-responsive table-bordered table-hover table-striped bill-address">
                                <tr>
                                    <td><?php echo  $languages[102]; ?></td>
                                    <td><?php echo $_SESSION['customer']['cust_s_name']; ?></p>
                                    </td>
                                </tr>
                               
                                <tr>
                                    <td><?php echo  $languages[104]; ?></td>
                                    <td><?php echo $_SESSION['customer']['cust_s_phone']; ?></td>
                                </tr>
                                <tr>
                                    <td><?php echo  $languages[106]; ?></td>
                                    <td>
                                        <?php
                                        $statement = $pdo->prepare("SELECT * FROM tbl_country WHERE country_id=?");
                                        $statement->execute(array($_SESSION['customer']['cust_s_country']));
                                        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                                        foreach ($result as $row) {
                                            echo $row['country_name'];
                                        }
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td><?php echo  $languages[105]; ?></td>
                                    <td>
                                        <?php echo nl2br($_SESSION['customer']['cust_s_address']); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td><?php echo  $languages[107]; ?></td>
                                    <td><?php echo $_SESSION['customer']['cust_s_city']; ?></td>
                                </tr>
                                
                            </table>
                        </div>
                    </div>
                </div>



                <div class="cart-buttons">
                    <ul>
                        <li><a href="cart.php" class="btn btn-primary"><?php echo  $languages[21]; ?></a></li>
                    </ul>
                </div>

                <div class="clear"></div>
                <h3 class="special"><?php echo  $languages[33]; ?></h3>
                <div class="row">

                    <?php
		                $checkout_access = 1;
		                if(
		                    ($_SESSION['customer']['cust_b_name']=='') ||

		                    ($_SESSION['customer']['cust_b_phone']=='') ||
		                    ($_SESSION['customer']['cust_b_country']=='') ||
		                    ($_SESSION['customer']['cust_b_address']=='') ||
		                    ($_SESSION['customer']['cust_b_city']=='') ||

		                    ($_SESSION['customer']['cust_s_name']=='') ||

		                    ($_SESSION['customer']['cust_s_phone']=='') ||
		                    ($_SESSION['customer']['cust_s_country']=='') ||
		                    ($_SESSION['customer']['cust_s_address']=='') ||
		                    ($_SESSION['customer']['cust_s_city']=='') 

		                ) {
		                    $checkout_access = 0;
		                }
		                ?>
                    <?php if($checkout_access == 0): ?>
                    <div class="col-md-12">
                        <div style="color:red;font-size:22px;margin-bottom:50px;">
                            Bạn phải điền tất cả thông tin thanh toán và giao hàng từ bảng điều khiển để thanh toán đơn
                            hàng. Vui lòng điền thông tin vào <a href="customer-billing-shipping-update.php"
                                style="color:red;text-decoration:underline;">link này</a>.
                        </div>
                    </div>
                    <?php else: ?>
                    <div class="col-md-4">

                        <div class="row">

                            <div class="col-md-12 form-group">
                                <label for=""><?php echo  $languages[34]; ?> *</label>
                                <select name="payment_method" class="form-control select2" id="advFieldsStatus">
                                    <option value=""><?php echo  $languages[35]; ?></option>
                                    <option value="PayPal"><?php echo  $languages[36]; ?></option>
                                    <option value="Bank Deposit"><?php echo  $languages[38]; ?></option>
                                </select>
                            </div>

                           
                                <?php require_once("./vnpay_php/config.php"); ?>             
                                <div class="container">
                                    <div class="table-responsive">
                                        <form class="paypal" id="paypal_form" action="./vnpay_php/vnpay_create_payment.php" id="frmCreateOrder" method="post">        
                                            <div class="form-group">
                                                <label for="amount">Số tiền</label>
                                                <input class="form-control" data-val="true" data-val-number="The field Amount must be a number." data-val-required="The Amount field is required." id="amount" max="100000000" min="1" name="amount" type="number" value="<?php echo $final_total; ?>" />
                                            </div>
                                            <h4>Chọn phương thức thanh toán</h4>
                                            <div class="form-group">
                                                <h5>Cách 1: Chuyển hướng sang Cổng VNPAY chọn phương thức thanh toán</h5>
                                            <input type="radio" Checked="True" id="bankCode" name="bankCode" value="">
                                            <label for="bankCode">Cổng thanh toán VNPAYQR</label><br>
                                            
                                            <h5>Cách 2: Tách phương thức tại site của đơn vị kết nối</h5>
                                            <input type="radio" id="bankCode" name="bankCode" value="VNPAYQR">
                                            <label for="bankCode">Thanh toán bằng ứng dụng hỗ trợ VNPAYQR</label><br>
                                            
                                            <input type="radio" id="bankCode" name="bankCode" value="VNBANK">
                                            <label for="bankCode">Thanh toán qua thẻ ATM/Tài khoản nội địa</label><br>
                                            
                                            <input type="radio" id="bankCode" name="bankCode" value="INTCARD">
                                            <label for="bankCode">Thanh toán qua thẻ quốc tế</label><br>
                                            
                                            </div>
                                            <div class="form-group">
                                                <h5>Chọn ngôn ngữ giao diện thanh toán:</h5>
                                                <input type="radio" id="language" Checked="True" name="language" value="vn">
                                                <label for="language">Tiếng việt</label><br>
                                                <input type="radio" id="language" name="language" value="en">
                                                <label for="language">Tiếng anh</label><br>
                                                
                                            </div>
                                            <button type="submit" class="btn btn-default" href>Thanh toán</button>
                                        </form>
                                    </div>
                                </div> 
                            <form action="payment/bank/init.php" method="post" id="bank_form">
                                <input type="hidden" name="amount" value="<?php echo $final_total; ?>">
                                <div class="col-md-12 form-group">
                                    <label for=""><?php echo  $languages[44]; ?> <br><span
                                            style="font-size:12px;font-weight:normal;">(<?php echo  $languages[45]; ?>)</span></label>
                                    <textarea name="transaction_info" class="form-control" cols="30"
                                        rows="10"></textarea>
                                </div>
                                <div class="col-md-12 form-group">
                                    <input type="submit" class="btn btn-primary" value="<?php echo  $languages[46]; ?>"
                                        name="form3">
                                </div>
                            </form>

                        </div>


                    </div>
                    <?php endif; ?>

                </div>


                <?php endif; ?>

            </div>
        </div>
    </div>
</div>


<?php require_once('footer.php'); ?>
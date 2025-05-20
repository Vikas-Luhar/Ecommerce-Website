<div id="ordersBtn" >
  <h2>Order Details</h2>
  <table class="table table-striped">
    <thead>
      <tr>
        <th>Order Id</th>
        <th>Product Id</th>
        <th>Customer</th>
        <th>Address</th>
        <!-- <th>Email</th> -->
        <!-- <th>OrderDate</th> -->
        <th>Payment Method</th>
        <th>Order Type</th>
        <th>Total Price</th>
        <th>Quantity</th>
        <!-- <th>Order Status</th> -->
        <!-- <th>Payment Status</th> -->
        <!-- <th>More Details</th> -->
     </tr>
    </thead>
     <?php
      include_once "../config/dbconnect.php";
      $sql="SELECT * from purchases";
      $result=$conn-> query($sql);
      
      if ($result-> num_rows > 0){
        while ($row=$result-> fetch_assoc()) {
    ?>
       <tr>
         <td><?=$row["Order_id"]?></td>
         <td><?=$row["product_id"]?></td>
         <td><?=$row["name"]?></td>
         <td><?=$row["Address"]?></td>
         <!-- <td><?=$row["email"]?></td> -->
          <!-- <td><?=$row["order_date"]?></td> -->
          <td><?=$row["pay_mode"]?></td>
          <td><?=$row["category_name"]?></td>
          <td><?=$row["total_price"]?></td>
          <td><?=$row["qty"]?></td>
           <!-- <?php 
                if($row["order_status"]==0){
                            
            ?>
                <td><button class="btn btn-danger" onclick="ChangeOrderStatus('<?=$row['Order_id']?>')">Pending </button></td>
            <?php
                        
                }else{
            ?>
                <td><button class="btn btn-success" onclick="ChangeOrderStatus('<?=$row['Order_id']?>')">Delivered</button></td>
        
            <?php
            }
                if($row["pay_status"]==0){
            ?>
                <td><button class="btn btn-danger"  onclick="ChangePay('<?=$row['Order_id']?>')">Unpaid</button></td>
            <?php
                        
            }else if($row["pay_status"]==1){
            ?>
                <td><button class="btn btn-success" onclick="ChangePay('<?=$row['Order_id']?>')">Paid </button></td>
            <?php
                }
            ?> -->
              
        <!-- <td><a class="btn btn-primary openPopup" data-href="./adminView/viewEachOrder.php?orderID=<?=$row['Order_id']?>" href="javascript:void(0);">View</a></td> -->
        </tr>
    <?php
            
        }
      }
    ?>
     
  </table>
   
</div>
<!-- Modal -->
<div class="modal fade" id="viewModal" role="dialog">
    <div class="modal-dialog modal-lg">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          
          <h4 class="modal-title">Order Details</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="order-view-modal modal-body">
        
        </div>
      </div><!--/ Modal content-->
    </div><!-- /Modal dialog-->
  </div>
<script>
     //for view order modal  
    $(document).ready(function(){
      $('.openPopup').on('click',function(){
        var dataURL = $(this).attr('data-href');
    
        $('.order-view-modal').load(dataURL,function(){
          $('#viewModal').modal({show:true});
        });
      });
    });
 </script>
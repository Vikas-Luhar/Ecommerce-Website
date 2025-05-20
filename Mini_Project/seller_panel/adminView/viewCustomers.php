<div>
  <h2>All Customers</h2>
  <table class="table">
    <thead>
      <tr>
        <th class="text-center">S.N.</th>
        <th class="text-center">Username</th>
        <th class="text-center">Email</th>
      </tr>
    </thead>
    <tbody>
    <?php
      include_once "../config/dbconnect.php";

      // Corrected SQL query
      $sql="SELECT * FROM user_form WHERE user_type='user'";
      $result = $conn->query($sql);
      $count = 1;

      if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
    ?>
        <tr>
          <td class="text-center"><?=$count?></td>
          <td class="text-center"><?=$row["name"]?></td>
          <td class="text-center"><?=$row["email"]?></td>
        </tr>
    <?php
          $count++;
        }
      } else {
        echo "<tr><td colspan='5' class='text-center'>No users found</td></tr>";
      }
    ?>
    </tbody>
  </table>
</div>

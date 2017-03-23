
    <div class="container">
		<div class="starter-template">
			</div>

		<?php 
	
			include_once 'inc/header.php'; 
			require_once('inc/paginator.php');
			
			$conn = new mysqli('127.0.0.1', 'master', 'master', 'employees');
			$limit = (isset($_GET["limit"])) ? $_GET["limit"] : 10;
			$page = (isset($_GET["page"])) ? $_GET["page"] : 1;
			$links = (isset($_GET["links"])) ? $_GET["links"] : 1;
			$letter = isset($_GET['letter']) ? $_GET['letter'] : 'A';
			
			//$query = isset($_GET['letter']) ? "SELECT * FROM employees WHERE first_name LIKE '$letter%'" : "SELECT * FROM employees";
			$query = "SELECT * FROM employees";
			
			$Paginator = new Paginator($conn, $query, $letter);
			$results = $Paginator->getData( $limit, $page, $letter );
		?>
			
		  <h3 class="sub-header">Our Employees</h3>
		
		  <?php echo $Paginator->wordLink(); ?>
		
		  <table class="table table-hover">
			<tr>
				<th>ID</th>
				<th>FirstName</th>
				<th>LastName</th>
				<th>Gender</th>
				<th>Birth date</th>
				<th>Hire date</th>
			</tr>
			<?php for( $i = 0; $i < count( $results->data ); $i++ ) : ?>
				<tr>
					<td><?php echo $results->data[$i]['emp_no']; ?></td>
					<td><?php echo $results->data[$i]['first_name']; ?></td>
					<td><?php echo $results->data[$i]['last_name']; ?></td>
					<td><?php echo $results->data[$i]['gender']; ?></td>
					<td><?php echo $results->data[$i]['birth_date']; ?></td>
					<td><?php echo $results->data[$i]['hire_date']; ?></td>
				</tr>
			<?php endfor; ?>
		  </table>
		  
		  <?php echo $Paginator->createLinks( $links, 'pagination pagination-md' ); ?> 
		  
    </div><!-- /.container -->

<?php include_once 'inc/footer.php'; ?>
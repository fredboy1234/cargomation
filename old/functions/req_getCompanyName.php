<?php
include("../connection.php");

		$sql = "SELECT
		dbo.tblCompany.userIDCompany,
		dbo.tblCompany.companyName
		FROM
		dbo.tblCompany";	
		$sqlCompany = sqlsrv_query( $conn, $sql);
		echo '<select class="form-control p-0" id="company_a2b" name="company_a2b" required ><option></option>';
		while( $row = sqlsrv_fetch_array( $sqlCompany, SQLSRV_FETCH_ASSOC)) 
		{
		echo "<option value='".$row['userIDCompany']."'>".$row['companyName']."</option>";           		
		}
		echo'</select>';
		
                                            
											
?>
<?php
include("../connection.php");
session_start(); 
?>	
<table id="ShipmentTable" class="display nowrap" style="width:100%">
        <thead>
            <tr>
                <th>Shipment ID</th>
                <th>Consol ID</th>
                <th>Trans</th>
                <th>House Bill #</th>
                <th>Master Bill</th>
                <th>ETD</th>
                <th>ETA</th>
                <th>Vessel</th>
                <th>Voyage</th>
                <th>Goods Description</th>
                <th>Sending Agent</th>
                <th>Receiving Agent</th>
                <th>Consignee</th>
                <th>Consignor</th>
                <th>ARN FILE</th>
                <th>CIV FILE</th>
                <th>HBL FILE</th>
                <th>MSC FILE</th>
                <th>PKD FILE</th>
                <th>OTHER FILE</th>
               </tr>
        </thead>
        <tbody>
            <?php
               $SQL_SHIPMENTRECORD ="
			    SELECT *
				FROM
				dbo.tblCW1_Shipment";
				$SQL_SHIPMENTRECORD = sqlsrv_query( $conn, $SQL_SHIPMENTRECORD);
				while( $row = sqlsrv_fetch_array( $SQL_SHIPMENTRECORD, SQLSRV_FETCH_ASSOC) ) 
				{
				$etd = $row['cw_ETD']->format('d/m/Y'); // the result will 01/12/2015
				$eta = $row['cw_ETA']->format('d/m/Y'); // the result will 01/12/2015
				$id = $row['cw_ID'];
				  $shipID = $row['cw_ShipmentNumber'];
				  echo "<tr><td>".$row['cw_ShipmentNumber']."</td>";
				  echo "<td>".$row['cw_ConsolNumber']."</td>";
				  echo "<td>".$row['cw_TransportMode']."</td>";
				  echo "<td>".$row['cw_HouseBill']."</td>";
				  echo "<td>".$row['cw_MasterBill']."</td>";
				  echo "<td>".$etd."</td>";
				  echo "<td>".$eta."</td>";
				  echo "<td>".$row['cw_VesselName']."</td>";
				  echo "<td>".$row['cw_VoyageFlightNo']."</td>";
				  echo "<td></td>";
				  echo "<td>".$row['cw_SendingAgent']."</td>";
				  echo "<td>".$row['cw_ReceivingAgent']."</td>";
				  echo "<td>".$row['cw_Consignee']."</td>";
				  echo "<td>".$row['cw_Consignor']."</td>";
				  $SQLDOCRECORD="SELECT
      				*
      				FROM
					
      				dbo.tblCW1_ShipmentDoc
      				WHERE
      				dbo.tblCW1_ShipmentDoc.cw_DocumentShipmentNumber = '$shipID' AND dbo.tblCW1_ShipmentDoc.cw_DocumentType='ARN'";
					$SQL_DOCRECORD = sqlsrv_query( $conn, $SQLDOCRECORD);
					echo "<td>";
					while( $docrow = sqlsrv_fetch_array( $SQL_DOCRECORD, SQLSRV_FETCH_ASSOC) ) 
				{
					
					$idDoc = $docrow['cw_IDdocument']."-".$shipID;
					$docType = $docrow['cw_DocumentType'];
					$docName = $docrow['cw_DocumentName'];
					$strArray = explode('.',$docName);
					$lastElement = end($strArray);
					if($lastElement == "pdf"){$ctype="pdf";}elseif($lastElement == "xls" || $lastElement == "xlsx"){$ctype="xls";}elseif($lastElement == "doc" || $lastElement == "docx"){$ctype="doc";}else{$ctype="file";}
					echo "<span><i data-toggle='modal' data-target='#doc$idDoc' class='fa fa-file-$ctype-o fa-3x'></i>&nbsp;&nbsp;$docName&nbsp;&nbsp;</span>"; 
				}   echo "</td>";
				
					$SQLDOCRECORD="SELECT
      				*
      				FROM
					
      				dbo.tblCW1_ShipmentDoc
      				WHERE
      				dbo.tblCW1_ShipmentDoc.cw_DocumentShipmentNumber = '$shipID' AND dbo.tblCW1_ShipmentDoc.cw_DocumentType='CIV'";
					$SQL_DOCRECORD = sqlsrv_query( $conn, $SQLDOCRECORD);
					echo "<td>";
					while( $docrow = sqlsrv_fetch_array( $SQL_DOCRECORD, SQLSRV_FETCH_ASSOC) ) 
				{
					
					$idDoc = $docrow['cw_IDdocument']."-".$shipID;
					$docType = $docrow['cw_DocumentType'];
					$docName = $docrow['cw_DocumentName'];
					$strArray = explode('.',$docName);
					$lastElement = end($strArray);
					if($lastElement == "pdf"){$ctype="pdf";}elseif($lastElement == "xls" || $lastElement == "xlsx"){$ctype="xls";}elseif($lastElement == "doc" || $lastElement == "docx"){$ctype="doc";}else{$ctype="file";}
					echo "<span><i data-toggle='modal' data-target='#doc$idDoc' class='fa fa-file-$ctype-o fa-3x'></i>&nbsp;&nbsp;$docName&nbsp;&nbsp;</span>"; 
				}   echo "</td>";
				
				
				$SQLDOCRECORD="SELECT
      				*
      				FROM
					
      				dbo.tblCW1_ShipmentDoc
      				WHERE
      				dbo.tblCW1_ShipmentDoc.cw_DocumentShipmentNumber = '$shipID' AND dbo.tblCW1_ShipmentDoc.cw_DocumentType='HBL'";
					$SQL_DOCRECORD = sqlsrv_query( $conn, $SQLDOCRECORD);
					echo "<td>";
					while( $docrow = sqlsrv_fetch_array( $SQL_DOCRECORD, SQLSRV_FETCH_ASSOC) ) 
				{
					
					$idDoc = $docrow['cw_IDdocument']."-".$shipID;
					$docType = $docrow['cw_DocumentType'];
					$docName = $docrow['cw_DocumentName'];
					$strArray = explode('.',$docName);
					$lastElement = end($strArray);
					if($lastElement == "pdf"){$ctype="pdf";}elseif($lastElement == "xls" || $lastElement == "xlsx"){$ctype="xls";}elseif($lastElement == "doc" || $lastElement == "docx"){$ctype="doc";}else{$ctype="file";}
					echo "<span><i data-toggle='modal' data-target='#doc$idDoc' class='fa fa-file-$ctype-o fa-3x'></i>&nbsp;&nbsp;$docName&nbsp;&nbsp;</span>"; 
				}   echo "</td>";
				
				$SQLDOCRECORD="SELECT
      				*
      				FROM
					
      				dbo.tblCW1_ShipmentDoc
      				WHERE
      				dbo.tblCW1_ShipmentDoc.cw_DocumentShipmentNumber = '$shipID' AND dbo.tblCW1_ShipmentDoc.cw_DocumentType='MSC'";
					$SQL_DOCRECORD = sqlsrv_query( $conn, $SQLDOCRECORD);
					echo "<td>";
					while( $docrow = sqlsrv_fetch_array( $SQL_DOCRECORD, SQLSRV_FETCH_ASSOC) ) 
				{
					
					$idDoc = $docrow['cw_IDdocument']."-".$shipID;
					$docType = $docrow['cw_DocumentType'];
					$docName = $docrow['cw_DocumentName'];
					$strArray = explode('.',$docName);
					$lastElement = end($strArray);
					if($lastElement == "pdf"){$ctype="pdf";}elseif($lastElement == "xls" || $lastElement == "xlsx"){$ctype="xls";}elseif($lastElement == "doc" || $lastElement == "docx"){$ctype="doc";}else{$ctype="file";}
					echo "<span><i data-toggle='modal' data-target='#doc$idDoc' class='fa fa-file-$ctype-o fa-3x'></i>&nbsp;&nbsp;$docName&nbsp;&nbsp;</span>"; 
				}   echo "</td>";
				
				$SQLDOCRECORD="SELECT
      				*
      				FROM
					
      				dbo.tblCW1_ShipmentDoc
      				WHERE
      				dbo.tblCW1_ShipmentDoc.cw_DocumentShipmentNumber = '$shipID' AND dbo.tblCW1_ShipmentDoc.cw_DocumentType='PKD'";
					$SQL_DOCRECORD = sqlsrv_query( $conn, $SQLDOCRECORD);
					echo "<td>";
					while( $docrow = sqlsrv_fetch_array( $SQL_DOCRECORD, SQLSRV_FETCH_ASSOC) ) 
				{
					
					$idDoc = $docrow['cw_IDdocument']."-".$shipID;
					$docType = $docrow['cw_DocumentType'];
					$docName = $docrow['cw_DocumentName'];
					//$ftype=	end(split('.',$docName));
					$strArray = explode('.',$docName);
					$lastElement = end($strArray);
					if($lastElement == "pdf"){$ctype="pdf";}elseif($lastElement == "xls" || $lastElement == "xlsx"){$ctype="xls";}elseif($lastElement == "doc" || $lastElement == "docx"){$ctype="doc";}else{$ctype="file";}
					echo "<span><i data-toggle='modal' data-target='#doc$idDoc' class='fa fa-file-$ctype-o fa-3x'></i>&nbsp;&nbsp;$docName&nbsp;&nbsp;</span>"; 
				}   echo "</td>";
				
				$SQLDOCRECORD="SELECT
      				*
      				FROM
					
      				dbo.tblCW1_ShipmentDoc
      				WHERE
      				dbo.tblCW1_ShipmentDoc.cw_DocumentShipmentNumber = '$shipID' AND dbo.tblCW1_ShipmentDoc.cw_DocumentType <> 'PKD' AND dbo.tblCW1_ShipmentDoc.cw_DocumentType <> 'MSC' AND dbo.tblCW1_ShipmentDoc.cw_DocumentType <> 'HBL' AND dbo.tblCW1_ShipmentDoc.cw_DocumentType <> 'CIV' AND dbo.tblCW1_ShipmentDoc.cw_DocumentType <> 'ARN'";
					$SQL_DOCRECORD = sqlsrv_query( $conn, $SQLDOCRECORD);
					echo "<td>";
					while( $docrow = sqlsrv_fetch_array( $SQL_DOCRECORD, SQLSRV_FETCH_ASSOC) ) 
				{
					
					$idDoc = $docrow['cw_IDdocument']."-".$shipID;
					$docType = $docrow['cw_DocumentType'];
					$docName = $docrow['cw_DocumentName'];
					if($lastElement == "pdf"){$ctype="pdf";}elseif($lastElement == "xls" || $lastElement == "xlsx"){$ctype="xls";}elseif($lastElement == "doc" || $lastElement == "docx"){$ctype="doc";}else{$ctype="file";}
					echo "<span><i data-toggle='modal' data-target='#doc$idDoc' class='fa fa-file-$ctype-o fa-3x'></i>&nbsp;&nbsp;$docName&nbsp;&nbsp;</span>"; 
				}   echo "</td>";
					
				  echo"</tr>";
				  
				   $SQLDOCRECORD="SELECT
      				*
      				FROM
      				dbo.tblCW1_ShipmentDoc
      				WHERE
      				dbo.tblCW1_ShipmentDoc.cw_DocumentShipmentNumber = '$shipID'";
					$SQL_DOCRECORD = sqlsrv_query( $conn, $SQLDOCRECORD);
					while( $docrow = sqlsrv_fetch_array( $SQL_DOCRECORD, SQLSRV_FETCH_ASSOC) ) 
				{
					$idDoc = $docrow['cw_IDdocument']."-".$shipID;
					$docType = $docrow['cw_DocumentType'];
					$docName = $docrow['cw_DocumentName'];
				   
				   echo " <div class='modal' id='doc$idDoc' tabindex='-1' role='dialog'  aria-hidden='true'>
                                    <div class='modal-dialog modal-xl'>
                                        <div class='modal-content'>
                                            <div class='modal-body'>
                                               <iframe style='width:100%;height:100%;' src = 'http://a2bfreighthub.com/ViewerJS/#../Vdirect_cwxml_C00001/$shipID/$docType/$docName' allowfullscreen webkitallowfullscreen></iframe>
                                            </div>
                                            <div class='modal-footer'>
                                                <button type='button' class='btn btn-danger waves-effect text-left' data-dismiss='modal'>Close</button>
                                            </div>
                                        </div>
                                  </div>
                        </div>";
				}
				}
				
				   
			?>
           
        </tbody>
    </table>
	<script>
	$(document).ready(function() {
    $('#ShipmentTable').DataTable( {
        responsive: true,
        colReorder: true
    } );
} );
</script>
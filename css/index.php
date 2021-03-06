<!-- Connect to the database -->
<?php
    try
    {
        $db = new PDO("mysql:host=localhost;dbname=itss_srv", "root", "");
        $db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        $db->exec("SET NAMES 'utf8'");
    }
    catch(Exception $e)
    {
        echo "ERROR: Could not connect to the database.";
        exit;
    }

    try
    {
        $services = $db->query("SELECT * FROM services");
    }
    catch(Exception $e)
    {
        echo "ERROR: This action cannot be performed.";
        exit;
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>SDSC Services Estimation Tool</title>
        <link rel="stylesheet" href="//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css">
        <link rel="stylesheet" type="text/css" href="css/global.css">
        <script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
        <script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script>
        <script src="js/begin.js"></script>
        <script src="js/functions.js"></script>
        <script>
            $(function() {
                $( document ).tooltip({ track: true });
            });
        </script>
        <style>
            label {
                display: inline-block;
                width: 5em;
            }
        </style>
    </head>
    
    
    <!-- BEGIN THE HTML HERE -->
    <body>
       <div class="content">
          <img src="images/SDSClogo.jpg"/><br/><br/><br/>
           <div id = "product-col">
               <?php
                while($row = $services->fetch(PDO::FETCH_ASSOC))
                {
            ?>
                    <button class="add-button" onclick="addProduct('<?php echo $row['type']; ?>');">+
    </button>
                    <div class="vm-services">
                        <span class="service-name" title="<?php echo $row['desc']; ?>"> 
                           <?php echo $row['name']; ?>
                        </span>
                        <span class="service-price">
                            $<?php echo $row['monthly']; ?>
                        </span>
                    </div>
                <?php
                }
                ?>
           </div>
           
           <button type="button" id="pdfbutton" onclick="validateForm();">Generate PDF</button>
            <div id = "quote-content">
                <!--<strong id="table-title">Your SDSC Cost Estimate </strong>-->
                <form name="quote" id="quote" action="./tcpdf/pdf/generatepdf.php" method="post">
                    <br/><br/>
                    <table class="tables" id="vm-table" colspan="5" cellspacing="0" style="font-size: 11px">
                        <tr style="font-size: 11px; background-color: #ccc; font-weight: bold; ">
                            <td colspan="1" width="20">&nbsp;</td>
                            <td colspan="1" width="170">
                                Virtualization (VM) Services
                            </td>
                            <td colspan="1" width="152">
                                Price/Unit
                            </td>
                            <td colspan="1" width="63">
                                Quantity
                            </td>
                            <td colspan="1" width="135">
                                Subtotal
                            </td>
                        </tr>
                    </table>
                    <table class="total-table" id="vm-table-totals" colspan="5" width="790">
                        <tr>
                            <td colspan="4" width="383" height="40" valign="bottom">
                                <b>VM Total: </b>
                            </td>
                            <td colspan="1" width="135">
                                <input type="text" id="vm-sub-total" class="sub" name="vm-sub-total" size="20" readonly>
                            </td>
                        </tr>
                    </table>
                    
                    <table class="tables" id="str-table" colspan="5" cellspacing="0" style="font-size: 11px">
                        <tr style="font-size: 11px; background-color: #ccc; font-weight: bold;">
                            <td colspan="1" width="20">&nbsp;</td>
                            <td colspan="1" width="170">
                                Storage Services
                            </td>
                            <td colspan="1" width="152">
                                Price/Unit
                            </td>
                            <td colspan="1" width="63">
                                Quantity
                            </td>
                            <td colspan="1" width="135">
                                Subtotal
                            </td>
                        </tr>
                    </table>
                    <table class="total-table" id="str-table-totals" colspan="5" width="790">
                        <tr>
                            <td colspan="4" width="383" height="40" valign="bottom">
                                <b>Storage Total: </b>
                            </td>
                            <td colspan="1" width="135">
                                <input type="text" id="str-sub-total" class="sub" name="vm-sub-total" size="20" readonly>
                            </td>
                        </tr>
                    </table>
                    
                    <table class="tables" id="pa-table" colspan="5" cellspacing="0" style="font-size: 11px">
                        <tr style="font-size: 11px; background-color: #ccc; font-weight: bold;">
                            <td colspan="1" width="20">&nbsp;</td>
                            <td colspan="1" width="170">
                                Physical Administration
                            </td>
                            <td colspan="1" width="152">
                                Price/Unit
                            </td>
                            <td colspan="1" width="63">
                                Quantity
                            </td>
                            <td colspan="1" width="135">
                                Subtotal
                            </td>
                        </tr>
                    </table>
                    <table class="total-table" id="pa-table-totals" colspan="5" width="790">
                        <tr>
                            <td colspan="4" width="383" height="40" valign="bottom">
                                <b>Physical Administration Total: </b>
                            </td>
                            <td colspan="1" width="135">
                                <input type="text" id="pa-sub-total" class="sub" name="vm-sub-total" size="20" readonly>
                            </td>
                        </tr>
                    </table>
                    
                    <table class="tables" id="backup-table" colspan="5" cellspacing="0" style="font-size: 11px">
                        <tr style="font-size: 11px; background-color: #ccc; font-weight: bold;">
                            <td colspan="1" width="20">&nbsp;</td>
                            <td colspan="1" width="170">
                                CommVault Backup
                            </td>
                            <td colspan="1" width="152">
                                Price/Unit
                            </td>
                            <td colspan="1" width="63">
                                Quantity
                            </td>
                            <td colspan="1" width="135">
                                Subtotal
                            </td>
                        </tr>
                    </table>
                    <table class="total-table" id="backup-table-totals" colspan="5" width="790">
                        <tr>
                            <td colspan="4" width="383" height="40" valign="bottom">
                                <b>Backup Total: </b>
                            </td>
                            <td colspan="1" width="135">
                                <input type="text" id="backup-sub-total" class="sub" name="vm-sub-total" size="20" readonly>
                            </td>
                        </tr>
                    </table>
                    
                    <table class="tables" id="consult-table" colspan="5" cellspacing="0" style="font-size: 11px">
                        <tr style="font-size: 11px; background-color: #ccc; font-weight: bold;">
                            <td colspan="1" width="20">&nbsp;</td>
                            <td colspan="1" width="170">
                                Consulting
                            </td>
                            <td colspan="1" width="152">
                                Price/Unit
                            </td>
                            <td colspan="1" width="63">
                                Quantity
                            </td>
                            <td colspan="1" width="135">
                                Subtotal
                            </td>
                        </tr>
                    </table>
                    <table class="total-table" id="consult-table-totals" colspan="5" width="790">
                        <tr>
                            <td colspan="4" width="383" height="40" valign="bottom">
                                <b>Consulting Total: </b>
                            </td>
                            <td colspan="1" width="135">
                                <input type="text" id="consult-sub-total" name="vm-sub-total" size="20" readonly>
                            </td>
                        </tr>
                    </table>
                    
                    <table class="tables" id="sp-table" colspan="5" cellspacing="0" style="font-size: 11px">
                        <tr style="font-size: 11px; background-color: #ccc; font-weight: bold;">
                            <td colspan="1" width="20">&nbsp;</td>
                               <td colspan="1" width="170">
                                SharePoint
                            </td>
                            <td colspan="1" width="152">
                                Price/Unit
                            </td>
                            <td colspan="1" width="63">
                                Quantity
                            </td>
                            <td colspan="1" width="135">
                                Subtotal
                            </td>
                        </tr>
                    </table>
                    <table class="total-table" id="sp-table-totals" colspan="5" width="790">
                        <tr>
                            <td colspan="4" width="383" height="40" valign="bottom">
                                <b>Sharepoint Total: </b>
                            </td>
                            <td colspan="1" width="135">
                                <input type="text" id="sp-sub-total" name="vm-sub-total" size="20" readonly>
                            </td>
                        </tr>
                    </table>
                    
                    <table class="total-table" id="totals" colspan="5" width="790">
                        <tr>
                            <td colspan="4" width="383" height="40" valign="bottom">
                                <strong>Monthly Total: </strong>
                            </td>
                            <td colspan="1" width="135">
                                <input type="text" id="sub-total" name="sub-total" size="20" readonly>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="4" width="383" height="40" valign="bottom">
                                <strong>One-time Fees: </strong>
                            </td>
                            <td colspan="1" width="135">
                                <input type="text" id="onetime-total" size="20" name = "onetime-totals" readonly>
                            </td>
                        </tr>
                    </table>
                    
                    <input id="formcode" name="formcode" type="hidden">
                </form>
            </div>
       </div>
    </body>
</html>
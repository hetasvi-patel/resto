<?php
include_once(__DIR__ . "/../config/connection.php");
class mdl_customerwiseitempreservationpricelistdetail 
{                         
    public $customer_wise_item_preservation_price_list_detail_id;     
    public $customer_wise_item_preservation_price_list_id;     
    public $packing_unit_id;     
    public $rent_per_qty_month;     
    public $rent_per_qty_season;     
    public $detailtransactionmode;
}

class bll_customerwiseitempreservationpricelistdetail
{   
    public $_mdl;
    public $_dal;

    public function __construct()    
    {
        $this->_mdl = new mdl_customerwiseitempreservationpricelistdetail(); 
        $this->_dal = new dal_customerwiseitempreservationpricelistdetail();
    }

public function pageSearch()
{
    global $_dbh;

    // Retrieve item_id and customer_id from POST
    $item_id = isset($_POST['item_id']) ? intval($_POST['item_id']) : 0;
    $customer_id = isset($_POST['customer_id']) ? intval($_POST['customer_id']) : 0;

    // Start grid structure
    $_grid = "
    <div id=\"gridContainer\" class=\"table-responsive\" style=\"width: 100%; display: block;\">
        <table id=\"dataGrid\" class=\"table table-bordered table-striped text-center align-middle\">
            <thead class=\"thead-dark\">
                <tr>
                    <th>Packing Unit Name</th>
                    <th>Rent/Month/Qty</th>
                    <th>Rent/Season/Qty</th>
                   
                </tr>
            </thead>
            <tbody>";

    if ($item_id > 0 && $customer_id > 0) {
        // Prepare SQL using JOIN instead of subquery
        $stmt = $_dbh->prepare("
            SELECT 
                pum.packing_unit_id,
                pum.packing_unit_name,
                COALESCE(cippld.rent_per_qty_month, 0.00) AS rent_per_qty_month,
                COALESCE(cippld.rent_per_qty_season, 0.00) AS rent_per_qty_season
            FROM tbl_packing_unit_master pum
            LEFT JOIN tbl_customer_wise_item_preservation_price_list_master cipplm
                ON cipplm.item_id = :item_id AND cipplm.customer_id = :customer_id
            LEFT JOIN tbl_customer_wise_item_preservation_price_list_detail cippld
                ON cippld.customer_wise_item_preservation_price_list_id = cipplm.customer_wise_item_preservation_price_list_id
                AND pum.packing_unit_id = cippld.packing_unit_id
            WHERE pum.status = 1
        ");

        $stmt->bindParam(':item_id', $item_id, PDO::PARAM_INT);
        $stmt->bindParam(':customer_id', $customer_id, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($result)) {
            foreach ($result as $_rs) {
                $_grid .= "
                <tr data-id=\"{$_rs['packing_unit_id']}\">
                    <td>{$_rs['packing_unit_name']}</td>
                     <td contenteditable=\"true\" class=\"editable rent-monthly\" data-field=\"rent_per_qty_month\">{$_rs['rent_per_qty_month']}</td>
                        <td contenteditable=\"true\" class=\"editable rent-seasonal\" data-field=\"rent_per_qty_season\">{$_rs['rent_per_qty_season']}</td>
                    
                </tr>";
            }
        } else {
            $_grid .= "
            <tr class=\"norecords\">
                <td colspan=\"4\">No records available.</td>
            </tr>";
        }
    } else {
        $_grid .= "
        <tr class=\"norecords\">
            <td colspan=\"4\">Please select Item and Customer.</td>
        </tr>";
    }

    $_grid .= "
            </tbody>
        </table>
    </div>";

    echo $_grid;
}

}

if (isset($_REQUEST['action'])) {
    $action = $_REQUEST['action'];

    if ($action === 'fetch_units' && isset($_POST['item_id']) && isset($_POST['customer_id'])) {
        $bll = new bll_customerwiseitempreservationpricelistdetail();
        $bll->pageSearch();
        exit;
    }
}


class dal_customerwiseitempreservationpricelistdetail
{
    public function dbTransaction($_mdl)
    {
        global $_dbh;

        $_dbh->exec("set @p0 = ".$_mdl->customer_wise_item_preservation_price_list_detail_id);
        $_pre = $_dbh->prepare("CALL customer_wise_item_preservation_price_list_detail_transaction (@p0,?,?,?,?,?) ");
        $_pre->bindParam(1, $_mdl->customer_wise_item_preservation_price_list_id);
        $_pre->bindParam(2, $_mdl->packing_unit_id);
        $_pre->bindParam(3, $_mdl->rent_per_qty_month);
        $_pre->bindParam(4, $_mdl->rent_per_qty_season);
        $_pre->bindParam(5, $_mdl->detailtransactionmode);
        $_pre->execute();
    }
}

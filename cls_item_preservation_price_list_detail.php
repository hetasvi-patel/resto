<?php
class mdl_itempreservationpricelistdetail 
{                        
    public $item_preservation_price_list_detail_id;     
    public $item_preservation_price_list_id;     
    public $packing_unit_id;     
    public $rent_per_qty_month;     
    public $rent_per_qty_season;     
    public $detailtransactionmode;
}

class bll_itempreservationpricelistdetail                           
{   
    public $_mdl;
    public $_dal;

    public function __construct()    
    {
        $this->_mdl = new mdl_itempreservationpricelistdetail(); 
        $this->_dal = new dal_itempreservationpricelistdetail();
    }

    public function dbTransaction()
    {
        $this->_dal->dbTransaction($this->_mdl);
    }

public function pageSearch()
{
    global $_dbh;

    $_grid = "
    <div id=\"gridContainer\" class=\"table-responsive\" style=\"width: 100%; display: block;\">
        <table id=\"dataGrid\" class=\"table table-bordered table-striped text-center align-middle\">
            <thead class=\"thead-dark\">
                <tr>
                    <th>Packing Unit Name</th>
                    <th>Rent/Month/Qty</th>
                    <th>Rent/Season/Qty</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id=\"gridBody\">";

    // Use correct item_id from request
    $main_id_name = "item_id";  // 🔄 Changed from item_preservation_price_list_id
    $item_id = isset($_POST[$main_id_name]) ? $_POST[$main_id_name] : null;

    $result = [];
    if ($item_id) {
        try {
            $sql = "SELECT 
                pum.packing_unit_id, 
                pum.packing_unit_name, 
                COALESCE(ippl.rent_per_qty_month, '0.00') AS rent_per_qty_month, 
                COALESCE(ippl.rent_per_qty_season, '0.00') AS rent_per_qty_season
            FROM 
                tbl_packing_unit_master pum
            LEFT JOIN 
                tbl_item_preservation_price_list_detail ippl 
                ON pum.packing_unit_id = ippl.packing_unit_id
            LEFT JOIN 
                tbl_item_preservation_price_list_master ipm 
                ON ippl.item_preservation_price_list_id = ipm.item_preservation_price_list_id 
                AND ipm.item_id = :item_id
            WHERE 
                pum.status = 1";

            $stmt = $_dbh->prepare($sql);
            $stmt->bindParam(':item_id', $item_id, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error in pageSearch SQL: " . $e->getMessage());
            echo "<div class='alert alert-danger'>Error fetching data. Please try again later.</div>";
            return;
        }
    }

    if (!empty($result)) {
        foreach ($result as $_rs) {
            $_grid .= "
                <tr data-id=\"{$_rs['packing_unit_id']}\">
                    <td>{$_rs['packing_unit_name']}</td>
                    <td>{$_rs['rent_per_qty_month']}</td>
                    <td>{$_rs['rent_per_qty_season']}</td>
                    <td>
                        <button class=\"btn btn-info btn-sm edit-btn\" data-id=\"{$_rs['packing_unit_id']}\">Edit</button>
                        <button class=\"btn btn-danger btn-sm delete-btn\" data-id=\"{$_rs['packing_unit_id']}\">Delete</button>
                    </td>
                </tr>";
        }
    } else {
        $_grid .= "
            <tr id=\"norecords\" class=\"norecords\">
                <td colspan=\"4\">No records available.</td>
            </tr>";
    }

    $_grid .= "
            </tbody>
        </table>
    </div>";

    echo $_grid;
}

}

class dal_itempreservationpricelistdetail                         
{
    public function dbTransaction($mdl)                     
    {
        global $_dbh;

        $_dbh->exec("SET @p0 = " . $mdl->item_preservation_price_list_detail_id);
        $_pre = $_dbh->prepare("CALL item_preservation_price_list_detail_transaction (@p0,?,?,?,?,?)");
        $_pre->bindParam(1, $mdl->item_preservation_price_list_id);
        $_pre->bindParam(2, $mdl->packing_unit_id);
        $_pre->bindParam(3, $mdl->rent_per_qty_month);
        $_pre->bindParam(4, $mdl->rent_per_qty_season);
        $_pre->bindParam(5, $mdl->detailtransactionmode);
        $_pre->execute();
    }
}
?>
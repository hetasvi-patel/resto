<?php  
include_once(__DIR__ . "/../config/connection.php");
include("cls_customer_wise_item_preservation_price_list_detail.php"); 
        class mdl_customerwiseitempreservationpricelistmaster 
{                        
public $_customer_wise_item_preservation_price_list_id;          
    public $_customer_id;          
    public $_item_id;          
    public $_rent_per_kg_month;          
    public $_rent_per_kg_season;          
    public $_created_date;          
    public $_created_by;          
    public $_modified_date;          
    public $_modified_by;          
    public $_company_id;          
    public $_transactionmode;
    
                    /** FOR DETAIL **/
                    public $_hidden_array;
                     
                    /** \FOR DETAIL **/
                    
}

class bll_customerwiseitempreservationpricelistmaster                           
{   
    public $_mdl;
    public $_dal;

    public function __construct()    
    {
        $this->_mdl =new mdl_customerwiseitempreservationpricelistmaster(); 
        $this->_dal =new dal_customerwiseitempreservationpricelistmaster();
    }

    public function dbTransaction()
    {
        $this->_dal->dbTransaction($this->_mdl);
               
       /** FOR DETAIL **/
               
                  $_bllitem= new bll_customerwiseitempreservationpricelistdetail();
                    if($this->_mdl->_transactionmode!="D")
                    {
                        if (isset($_POST["hidden_array"])) {
    $detailArray = json_decode($_POST["hidden_array"], true);

    if (!empty($detailArray)) {
        foreach ($detailArray as $detail) {
            // Prepare detail object
            $detailModel = new mdl_customerwiseitempreservationpricelistdetail();
            $detailModel->customer_wise_item_preservation_price_list_id = $masterId; // Master ID
            $detailModel->packing_unit_id = $detail['packing_unit_id'];
            $detailModel->rent_per_qty_month = $detail['rent_per_qty_month'];
            $detailModel->rent_per_qty_season = $detail['rent_per_qty_season'];
            $detailModel->detailtransactionmode = "I"; // Insert mode

            // Save detail record
            $detailBLL = new bll_customerwiseitempreservationpricelistdetail();
            $detailBLL->dbTransaction($detailModel);
        }
    }
}
                  }
               /** \FOR DETAIL **/
        
            
       if($this->_mdl->_transactionmode =="D")
       {
            header("Location:../srh_customer_wise_item_preservation_price_list_master.php");
       }
       if($this->_mdl->_transactionmode =="U")
       {
            header("Location:../srh_customer_wise_item_preservation_price_list_master.php");
       }
       if($this->_mdl->_transactionmode =="I")
       {
            header("Location:../frm_customer_wise_item_preservation_price_list_master.php");
       }

    }
 
    public function fillModel()
    {
        global $_dbh;
        $this->_dal->fillModel($this->_mdl);
    
    
    }
     public function pageSearch()
    {
        global $_dbh;

        $sql="CAll csms1_search('t1.customer as val1, t2.item_name as val2, t.rent_per_kg_month, t.rent_per_kg_season, t.customer_wise_item_preservation_price_list_id','tbl_customer_wise_item_preservation_price_list_master t INNER JOIN tbl_customer_master t1 ON t.customer_id=t1.customer_id INNER JOIN tbl_item_master t2 ON t.item_id=t2.item_id')";
        echo "
        <table  id=\"searchMaster\" class=\"ui celled table display\">
        <thead>
            <tr>
            <th>Action</th> 
            <th> Customer  <br><input type=\"text\" data-index=\"1\" placeholder=\"Search Customer \" /></th> 
                         <th> Item  <br><input type=\"text\" data-index=\"2\" placeholder=\"Search Item \" /></th> 
                         <th> Rent / Kg./Month <br><input type=\"text\" data-index=\"3\" placeholder=\"Search Rent / Kg./Month\" /></th> 
                         <th> Rent / Kg. <br><input type=\"text\" data-index=\"4\" placeholder=\"Search Rent / Kg.\" /></th> 
                         </tr>
        </thead>
        <tbody>";
         $_grid="";
         $j=0;
        foreach($_dbh-> query($sql) as $_rs)
        {
            $j++;
        
        $_grid.="<tr>
        <td> 
            <form  method=\"post\" action=\"frm_customer_wise_item_preservation_price_list_master.php\" style=\"display:inline; margin-rigth:5px;\">
            <i class=\"fa fa-edit update\" style=\"cursor: pointer;\"></i>
            <input type=\"hidden\" name=\"customer_wise_item_preservation_price_list_id\" value=\"".$_rs["customer_wise_item_preservation_price_list_id"]."\" />
            <input type=\"hidden\" name=\"transactionmode\" value=\"U\"  />
            </form> <form  method=\"post\" action=\"classes/cls_customer_wise_item_preservation_price_list_master.php\" style=\"display:inline;\">
            <i class=\"fa fa-trash delete\" style=\"cursor: pointer;\"></i>
            <input type=\"hidden\" name=\"customer_wise_item_preservation_price_list_id\" value=\"".$_rs["customer_wise_item_preservation_price_list_id"]."\" />
            <input type=\"hidden\" name=\"transactionmode\" value=\"D\"  />
            </form>
            </td>";
        $fieldvalue=$_rs["val1"];
                            $_grid.= "
                            <td> ".$fieldvalue." </td>"; 
                       $fieldvalue=$_rs["val2"];
                            $_grid.= "
                            <td> ".$fieldvalue." </td>"; 
                       $fieldvalue=$_rs["rent_per_kg_month"];
                            $_grid.= "
                            <td> ".$fieldvalue." </td>"; 
                       $fieldvalue=$_rs["rent_per_kg_season"];
                            $_grid.= "
                            <td> ".$fieldvalue." </td>"; 
                       $_grid.= "</tr>\n";
           
            
        }   
         if($j==0) {
                $_grid.= "<tr>";
                $_grid.="<td colspan=\"9\">No records available.</td>";
                $_grid.="<td style=\"display:none\">&nbsp;</td>";
                         $_grid.="<td style=\"display:none\">&nbsp;</td>";
                         $_grid.="<td style=\"display:none\">&nbsp;</td>";
                         $_grid.="<td style=\"display:none\">&nbsp;</td>";
                         $_grid.="</tr>";
            }
        $_grid.="</tbody>
        </table> ";
        echo $_grid; 
    }
    function checkDuplicate($column_name,$column_value,$id_name,$id_value,$table_name) {
        global $_dbh;
        try {
            $sql="CAll csms1_check_duplicate('".$column_name."','".$column_value."','".$id_name."','".$id_value."','".$table_name."',@is_duplicate)";
            $stmt=$_dbh->prepare($sql);
            $stmt->execute();
            $result = $_dbh->query("SELECT @is_duplicate");
            $is_default = $result->fetchColumn();
            return $is_default;
        }
        catch (PDOException $e) {
            //echo "Error: " . $e->getMessage();
            return 0;
        }
        return 0;
    }
}
 class dal_customerwiseitempreservationpricelistmaster                         
{
    public function dbTransaction($_mdl)                     
    {
        global $_dbh;

        
        $_dbh->exec("set @p0 = ".$_mdl->_customer_wise_item_preservation_price_list_id);
        $_pre=$_dbh->prepare("CALL customer_wise_item_preservation_price_list_master_transaction (@p0,?,?,?,?,?,?,?,?,?,?) ");
        $_pre->bindParam(1,$_mdl->_customer_id);
        $_pre->bindParam(2,$_mdl->_item_id);
        $_pre->bindParam(3,$_mdl->_rent_per_kg_month);
        $_pre->bindParam(4,$_mdl->_rent_per_kg_season);
        $_pre->bindParam(5,$_mdl->_created_date);
        $_pre->bindParam(6,$_mdl->_created_by);
        $_pre->bindParam(7,$_mdl->_modified_date);
        $_pre->bindParam(8,$_mdl->_modified_by);
        $_pre->bindParam(9,$_mdl->_company_id);
        $_pre->bindParam(10,$_mdl->_transactionmode);
        $_pre->execute();
        
           /*** FOR DETAIL ***/
           if($_mdl->_transactionmode=="I") {
                // Retrieve the output parameter
                $result = $_dbh->query("SELECT @p0 AS inserted_id");
                // Get the inserted ID
                $insertedId = $result->fetchColumn();
                $_mdl->_customer_wise_item_preservation_price_list_id=$insertedId;
            }
            /*** /FOR DETAIL ***/
    
    }
    public function fillModel($_mdl)
    {
        global $_dbh;
        $_pre=$_dbh->prepare("CALL customer_wise_item_preservation_price_list_master_fillmodel (?) ");
        $_pre->bindParam(1,$_REQUEST["customer_wise_item_preservation_price_list_id"]);
        $_pre->execute();
        $_rs=$_pre->fetchAll(); 
        if(!empty($_rs)) {

        $_mdl->_customer_wise_item_preservation_price_list_id=$_rs[0]["customer_wise_item_preservation_price_list_id"];
        $_mdl->_customer_id=$_rs[0]["customer_id"];
        $_mdl->_item_id=$_rs[0]["item_id"];
        $_mdl->_rent_per_kg_month=$_rs[0]["rent_per_kg_month"];
        $_mdl->_rent_per_kg_season=$_rs[0]["rent_per_kg_season"];
        $_mdl->_created_date=$_rs[0]["created_date"];
        $_mdl->_created_by=$_rs[0]["created_by"];
        $_mdl->_modified_date=$_rs[0]["modified_date"];
        $_mdl->_modified_by=$_rs[0]["modified_by"];
        $_mdl->_company_id=$_rs[0]["company_id"];
        $_mdl->_transactionmode =$_REQUEST["transactionmode"];
        }
    }
}
$_bll=new bll_customerwiseitempreservationpricelistmaster();


    /*** FOR DETAIL ***/
    $_blldetail=new bll_customerwiseitempreservationpricelistdetail();
    /*** /FOR DETAIL ***/
if(isset($_REQUEST["action"]))
{
    $action=$_REQUEST["action"];
    $_bll->$action();
}
if(isset($_POST["type"]) && $_POST["type"]=="ajax") {
    $column_name="";$column_value="";$id_name="";$id_value="";$table_name="";
    if(isset($_POST["column_name"]))
        $column_name=$_POST["column_name"];
    if(isset($_POST["column_value"]))
        $column_value=$_POST["column_value"];
    if(isset($_POST["id_name"]))
        $id_name=$_POST["id_name"];
    if(isset($_POST["id_value"]))
        $id_value=$_POST["id_value"];
    if(isset($_POST["table_name"]))
        $table_name=$_POST["table_name"];
    echo $_bll->checkDuplicate($column_name,$column_value,$id_name,$id_value,$table_name);
    exit;
}
if(isset($_POST["masterHidden"]) && ($_POST["masterHidden"]=="save"))
{
  
            
            if(isset($_REQUEST["customer_wise_item_preservation_price_list_id"]) && !empty($_REQUEST["customer_wise_item_preservation_price_list_id"]))
                $field=trim($_REQUEST["customer_wise_item_preservation_price_list_id"]);
            else {
                    $field=0;
           }
    $_bll->_mdl->_customer_wise_item_preservation_price_list_id=$field;

            
            if(isset($_REQUEST["customer_id"]) && !empty($_REQUEST["customer_id"]))
                $field=trim($_REQUEST["customer_id"]);
            else {
                    $field=null;
           }
    $_bll->_mdl->_customer_id=$field;

            
            if(isset($_REQUEST["item_id"]) && !empty($_REQUEST["item_id"]))
                $field=trim($_REQUEST["item_id"]);
            else {
                    $field=null;
           }
    $_bll->_mdl->_item_id=$field;

            
            if(isset($_REQUEST["rent_per_kg_month"]) && !empty($_REQUEST["rent_per_kg_month"]))
                $field=trim($_REQUEST["rent_per_kg_month"]);
            else {
                    $field=null;
           }
    $_bll->_mdl->_rent_per_kg_month=$field;

            
            if(isset($_REQUEST["rent_per_kg_season"]) && !empty($_REQUEST["rent_per_kg_season"]))
                $field=trim($_REQUEST["rent_per_kg_season"]);
            else {
                    $field=null;
           }
    $_bll->_mdl->_rent_per_kg_season=$field;

            
            if(isset($_REQUEST["created_date"]) && !empty($_REQUEST["created_date"]))
                $field=trim($_REQUEST["created_date"]);
            else {
                    $field=null;
           }
    $_bll->_mdl->_created_date=$field;

            
            if(isset($_REQUEST["created_by"]) && !empty($_REQUEST["created_by"]))
                $field=trim($_REQUEST["created_by"]);
            else {
                    $field=null;
           }
    $_bll->_mdl->_created_by=$field;

            
            if(isset($_REQUEST["modified_date"]) && !empty($_REQUEST["modified_date"]))
                $field=trim($_REQUEST["modified_date"]);
            else {
                    $field=null;
           }
    $_bll->_mdl->_modified_date=$field;

            
            if(isset($_REQUEST["modified_by"]) && !empty($_REQUEST["modified_by"]))
                $field=trim($_REQUEST["modified_by"]);
            else {
                    $field=null;
           }
    $_bll->_mdl->_modified_by=$field;

            
            if(isset($_REQUEST["company_id"]) && !empty($_REQUEST["company_id"]))
                $field=trim($_REQUEST["company_id"]);
            else {
                    $field=null;
           }
    $_bll->_mdl->_company_id=$field;

            if(isset($_REQUEST["transactionmode"]))
                $tmode=$_REQUEST["transactionmode"];
            else
                $tmode="I";
            $_bll->_mdl->_transactionmode =$tmode;
         
               /*** FOR DETAIL ***/
                  if (isset($_POST["hidden_array"])) {
        $detailRecords = json_decode($_POST["hidden_array"], true);
        $_bll->_mdl->_array_itemdetail = new ArrayObject($detailRecords);
    }
                /*** \FOR DETAIL ***/
            $_bll->dbTransaction();
}

if(isset($_REQUEST["transactionmode"]) && $_REQUEST["transactionmode"]=="D")       
{   
     $_bll->fillModel();
     $_bll->dbTransaction();
}

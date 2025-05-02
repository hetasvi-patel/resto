<?php  
include_once(__DIR__ . "/../config/connection.php");
include("cls_inward_detail.php"); 
        class mdl_inwardmaster 
{                        
public $_inward_id;          
    public $_inward_no;          
    public $_customer;          
    public $_broker;          
    public $_billing_starts_from;          
    public $_unloading_charge;          
    public $_sp_note;          
    public $_total_qty;          
    public $_total_wt;          
    public $_weigh_bridge_slip_no;          
    public $_gross_wt;          
    public $_tare_wt;          
    public $_net_wt;          
    public $_vehicle_no;          
    public $_driver_name;          
    public $_driver_mobile_no;          
    public $_transporter;          
    public $_other_expense1;          
    public $_other_expense2;          
    public $_created_by;          
    public $_created_date;          
    public $_modified_by;          
    public $_modified_date;          
    public $_company_id;          
    public $_company_year_id;          
    public $_transactionmode;
    
                    /** FOR DETAIL **/
                    public $_array_itemdetail;
                     public $_array_itemdelete;
                    /** \FOR DETAIL **/
                    
}

class bll_inwardmaster                           
{   
    public $_mdl;
    public $_dal;

    public function __construct()    
    {
        $this->_mdl =new mdl_inwardmaster(); 
        $this->_dal =new dal_inwardmaster();
    }

    public function dbTransaction()
    {
        $this->_dal->dbTransaction($this->_mdl);
               
       /** FOR DETAIL **/
               
                  $_bllitem= new bll_inwarddetail();
                    if($this->_mdl->_transactionmode!="D")
                    {
                        if(!empty($this->_mdl->_array_itemdetail)) {
                             for($iterator= $this->_mdl->_array_itemdetail->getIterator();$iterator->valid();$iterator->next())
                             {
                                     $detailrow=$iterator->current();
                                    if(is_array($detailrow)) {
                                        foreach($detailrow as $name=>$value) {
                                            $_bllitem->_mdl->{$name}=$value;
                                        }
                                    }
                                    $_bllitem->_mdl->inward_id = $this->_mdl->_inward_id;
                                    $_bllitem->dbTransaction();
                             }
                        }
                         if(!empty($this->_mdl->_array_itemdelete)) {
                            for($iterator= $this->_mdl->_array_itemdelete->getIterator();$iterator->valid();$iterator->next())
                             {
                                     $detailrow=$iterator->current();
                                    if(is_array($detailrow)) {
                                        foreach($detailrow as $name=>$value) {
                                            $_bllitem->_mdl->{$name}=$value;
                                        }
                                    }
                                    $_bllitem->_mdl->inward_id = $this->_mdl->_inward_id;
                                    $_bllitem->dbTransaction();
                             }
                         }
                  }
               /** \FOR DETAIL **/
        
            
       if($this->_mdl->_transactionmode =="D")
       {
            header("Location:../srh_inward_master.php");
       }
       if($this->_mdl->_transactionmode =="U")
       {
            header("Location:../srh_inward_master.php");
       }
       if($this->_mdl->_transactionmode =="I")
       {
            header("Location:../frm_inward_master.php");
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

        $sql="CAll csms1_search('*','tbl_inward_master t')";
        echo "
        <table  id=\"searchMaster\" class=\"ui celled table display\">
        <thead>
            <tr>
            <th>Action</th> 
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
            <form  method=\"post\" action=\"frm_inward_master.php\" style=\"display:inline; margin-rigth:5px;\">
            <i class=\"fa fa-edit update\" style=\"cursor: pointer;\"></i>
            <input type=\"hidden\" name=\"inward_id\" value=\"".$_rs["inward_id"]."\" />
            <input type=\"hidden\" name=\"transactionmode\" value=\"U\"  />
            </form> <form  method=\"post\" action=\"classes/cls_inward_master.php\" style=\"display:inline;\">
            <i class=\"fa fa-trash delete\" style=\"cursor: pointer;\"></i>
            <input type=\"hidden\" name=\"inward_id\" value=\"".$_rs["inward_id"]."\" />
            <input type=\"hidden\" name=\"transactionmode\" value=\"D\"  />
            </form>
            </td>";
        $_grid.= "</tr>\n";
           
            
        }   
         if($j==0) {
                $_grid.= "<tr>";
                $_grid.="<td colspan=\"24\">No records available.</td>";
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
 class dal_inwardmaster                         
{
    public function dbTransaction($_mdl)                     
    {
        global $_dbh;

        
        $_dbh->exec("set @p0 = ".$_mdl->_inward_id);
        $_pre=$_dbh->prepare("CALL inward_master_transaction (@p0,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?) ");
        $_pre->bindParam(1,$_mdl->_inward_no);
        $_pre->bindParam(2,$_mdl->_customer);
        $_pre->bindParam(3,$_mdl->_broker);
        $_pre->bindParam(4,$_mdl->_billing_starts_from);
        $_pre->bindParam(5,$_mdl->_unloading_charge);
        $_pre->bindParam(6,$_mdl->_sp_note);
        $_pre->bindParam(7,$_mdl->_total_qty);
        $_pre->bindParam(8,$_mdl->_total_wt);
        $_pre->bindParam(9,$_mdl->_weigh_bridge_slip_no);
        $_pre->bindParam(10,$_mdl->_gross_wt);
        $_pre->bindParam(11,$_mdl->_tare_wt);
        $_pre->bindParam(12,$_mdl->_net_wt);
        $_pre->bindParam(13,$_mdl->_vehicle_no);
        $_pre->bindParam(14,$_mdl->_driver_name);
        $_pre->bindParam(15,$_mdl->_driver_mobile_no);
        $_pre->bindParam(16,$_mdl->_transporter);
        $_pre->bindParam(17,$_mdl->_other_expense1);
        $_pre->bindParam(18,$_mdl->_other_expense2);
        $_pre->bindParam(19,$_mdl->_created_by);
        $_pre->bindParam(20,$_mdl->_created_date);
        $_pre->bindParam(21,$_mdl->_modified_by);
        $_pre->bindParam(22,$_mdl->_modified_date);
        $_pre->bindParam(23,$_mdl->_company_id);
        $_pre->bindParam(24,$_mdl->_company_year_id);
        $_pre->bindParam(25,$_mdl->_transactionmode);
        $_pre->execute();
        
           /*** FOR DETAIL ***/
           if($_mdl->_transactionmode=="I") {
                // Retrieve the output parameter
                $result = $_dbh->query("SELECT @p0 AS inserted_id");
                // Get the inserted ID
                $insertedId = $result->fetchColumn();
                $_mdl->_inward_id=$insertedId;
            }
            /*** /FOR DETAIL ***/
    
    }
    public function fillModel($_mdl)
    {
        global $_dbh;
        $_pre=$_dbh->prepare("CALL inward_master_fillmodel (?) ");
        $_pre->bindParam(1,$_REQUEST["inward_id"]);
        $_pre->execute();
        $_rs=$_pre->fetchAll(); 
        if(!empty($_rs)) {

        $_mdl->_inward_id=$_rs[0]["inward_id"];
        $_mdl->_inward_no=$_rs[0]["inward_no"];
        $_mdl->_customer=$_rs[0]["customer"];
        $_mdl->_broker=$_rs[0]["broker"];
        $_mdl->_billing_starts_from=$_rs[0]["billing_starts_from"];
        $_mdl->_unloading_charge=$_rs[0]["unloading_charge"];
        $_mdl->_sp_note=$_rs[0]["sp_note"];
        $_mdl->_total_qty=$_rs[0]["total_qty"];
        $_mdl->_total_wt=$_rs[0]["total_wt"];
        $_mdl->_weigh_bridge_slip_no=$_rs[0]["weigh_bridge_slip_no"];
        $_mdl->_gross_wt=$_rs[0]["gross_wt"];
        $_mdl->_tare_wt=$_rs[0]["tare_wt"];
        $_mdl->_net_wt=$_rs[0]["net_wt"];
        $_mdl->_vehicle_no=$_rs[0]["vehicle_no"];
        $_mdl->_driver_name=$_rs[0]["driver_name"];
        $_mdl->_driver_mobile_no=$_rs[0]["driver_mobile_no"];
        $_mdl->_transporter=$_rs[0]["transporter"];
        $_mdl->_other_expense1=$_rs[0]["other_expense1"];
        $_mdl->_other_expense2=$_rs[0]["other_expense2"];
        $_mdl->_created_by=$_rs[0]["created_by"];
        $_mdl->_created_date=$_rs[0]["created_date"];
        $_mdl->_modified_by=$_rs[0]["modified_by"];
        $_mdl->_modified_date=$_rs[0]["modified_date"];
        $_mdl->_company_id=$_rs[0]["company_id"];
        $_mdl->_company_year_id=$_rs[0]["company_year_id"];
        $_mdl->_transactionmode =$_REQUEST["transactionmode"];
        }
    }
}
$_bll=new bll_inwardmaster();


    /*** FOR DETAIL ***/
    $_blldetail=new bll_inwarddetail();
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
 
            
            if(isset($_REQUEST["inward_id"]) && !empty($_REQUEST["inward_id"]))
                $field=trim($_REQUEST["inward_id"]);
            else {
                    $field=0;
           }
    $_bll->_mdl->_inward_id=$field;

            
            if(isset($_REQUEST["inward_no"]) && !empty($_REQUEST["inward_no"]))
                $field=trim($_REQUEST["inward_no"]);
            else {
                    $field=null;
           }
    $_bll->_mdl->_inward_no=$field;

            
            if(isset($_REQUEST["customer"]) && !empty($_REQUEST["customer"]))
                $field=trim($_REQUEST["customer"]);
            else {
                    $field=null;
           }
    $_bll->_mdl->_customer=$field;

            
            if(isset($_REQUEST["broker"]) && !empty($_REQUEST["broker"]))
                $field=trim($_REQUEST["broker"]);
            else {
                    $field=null;
           }
    $_bll->_mdl->_broker=$field;

            
            if(isset($_REQUEST["billing_starts_from"]) && !empty($_REQUEST["billing_starts_from"]))
                $field=trim($_REQUEST["billing_starts_from"]);
            else {
                    $field=null;
           }
    $_bll->_mdl->_billing_starts_from=$field;

            
            if(isset($_REQUEST["unloading_charge"]) && !empty($_REQUEST["unloading_charge"]))
                $field=trim($_REQUEST["unloading_charge"]);
            else {
                    $field=null;
           }
    $_bll->_mdl->_unloading_charge=$field;

            
            if(isset($_REQUEST["sp_note"]) && !empty($_REQUEST["sp_note"]))
                $field=trim($_REQUEST["sp_note"]);
            else {
                    $field=null;
           }
    $_bll->_mdl->_sp_note=$field;

            
            if(isset($_REQUEST["total_qty"]) && !empty($_REQUEST["total_qty"]))
                $field=trim($_REQUEST["total_qty"]);
            else {
                    $field=null;
           }
    $_bll->_mdl->_total_qty=$field;

            
            if(isset($_REQUEST["total_wt"]) && !empty($_REQUEST["total_wt"]))
                $field=trim($_REQUEST["total_wt"]);
            else {
                    $field=null;
           }
    $_bll->_mdl->_total_wt=$field;

            
            if(isset($_REQUEST["weigh_bridge_slip_no"]) && !empty($_REQUEST["weigh_bridge_slip_no"]))
                $field=trim($_REQUEST["weigh_bridge_slip_no"]);
            else {
                    $field=null;
           }
    $_bll->_mdl->_weigh_bridge_slip_no=$field;

            
            if(isset($_REQUEST["gross_wt"]) && !empty($_REQUEST["gross_wt"]))
                $field=trim($_REQUEST["gross_wt"]);
            else {
                    $field=null;
           }
    $_bll->_mdl->_gross_wt=$field;

            
            if(isset($_REQUEST["tare_wt"]) && !empty($_REQUEST["tare_wt"]))
                $field=trim($_REQUEST["tare_wt"]);
            else {
                    $field=null;
           }
    $_bll->_mdl->_tare_wt=$field;

            
            if(isset($_REQUEST["net_wt"]) && !empty($_REQUEST["net_wt"]))
                $field=trim($_REQUEST["net_wt"]);
            else {
                    $field=null;
           }
    $_bll->_mdl->_net_wt=$field;

            
            if(isset($_REQUEST["vehicle_no"]) && !empty($_REQUEST["vehicle_no"]))
                $field=trim($_REQUEST["vehicle_no"]);
            else {
                    $field=null;
           }
    $_bll->_mdl->_vehicle_no=$field;

            
            if(isset($_REQUEST["driver_name"]) && !empty($_REQUEST["driver_name"]))
                $field=trim($_REQUEST["driver_name"]);
            else {
                    $field=null;
           }
    $_bll->_mdl->_driver_name=$field;

            
            if(isset($_REQUEST["driver_mobile_no"]) && !empty($_REQUEST["driver_mobile_no"]))
                $field=trim($_REQUEST["driver_mobile_no"]);
            else {
                    $field=null;
           }
    $_bll->_mdl->_driver_mobile_no=$field;

            
            if(isset($_REQUEST["transporter"]) && !empty($_REQUEST["transporter"]))
                $field=trim($_REQUEST["transporter"]);
            else {
                    $field=null;
           }
    $_bll->_mdl->_transporter=$field;

            
            if(isset($_REQUEST["other_expense1"]) && !empty($_REQUEST["other_expense1"]))
                $field=trim($_REQUEST["other_expense1"]);
            else {
                    $field=null;
           }
    $_bll->_mdl->_other_expense1=$field;

            
            if(isset($_REQUEST["other_expense2"]) && !empty($_REQUEST["other_expense2"]))
                $field=trim($_REQUEST["other_expense2"]);
            else {
                    $field=null;
           }
    $_bll->_mdl->_other_expense2=$field;

            
            if(isset($_REQUEST["created_by"]) && !empty($_REQUEST["created_by"]))
                $field=trim($_REQUEST["created_by"]);
            else {
                    $field=null;
           }
    $_bll->_mdl->_created_by=$field;

            
            if(isset($_REQUEST["created_date"]) && !empty($_REQUEST["created_date"]))
                $field=trim($_REQUEST["created_date"]);
            else {
                    $field=null;
           }
    $_bll->_mdl->_created_date=$field;

            
            if(isset($_REQUEST["modified_by"]) && !empty($_REQUEST["modified_by"]))
                $field=trim($_REQUEST["modified_by"]);
            else {
                    $field=null;
           }
    $_bll->_mdl->_modified_by=$field;

            
            if(isset($_REQUEST["modified_date"]) && !empty($_REQUEST["modified_date"]))
                $field=trim($_REQUEST["modified_date"]);
            else {
                    $field=null;
           }
    $_bll->_mdl->_modified_date=$field;

            
            if(isset($_REQUEST["company_id"]) && !empty($_REQUEST["company_id"]))
                $field=trim($_REQUEST["company_id"]);
            else {
                    $field=null;
           }
    $_bll->_mdl->_company_id=$field;

            
            if(isset($_REQUEST["company_year_id"]) && !empty($_REQUEST["company_year_id"]))
                $field=trim($_REQUEST["company_year_id"]);
            else {
                    $field=null;
           }
    $_bll->_mdl->_company_year_id=$field;

            if(isset($_REQUEST["transactionmode"]))
                $tmode=$_REQUEST["transactionmode"];
            else
                $tmode="I";
            $_bll->_mdl->_transactionmode =$tmode;
         
               /*** FOR DETAIL ***/
                $_bll->_mdl->_array_itemdetail=array();
                $_bll->_mdl->_array_itemdelete=array();
                if(isset($_REQUEST["detail_records"])) {
                  $detail_records=json_decode($_REQUEST["detail_records"],true);
                   if(!empty($detail_records)) {
                        $arrayobject = new ArrayObject($detail_records);
                          $_bll->_mdl->_array_itemdetail=$arrayobject;
                    }
                }
                if(isset($_REQUEST["deleted_records"])) {
                  $deleted_records=json_decode($_REQUEST["deleted_records"],true);
                   if(!empty($deleted_records)) {
                        $deleteobject = new ArrayObject($deleted_records);
                          $_bll->_mdl->_array_itemdelete=$deleteobject;
                    }
                }
                /*** \FOR DETAIL ***/
            $_bll->dbTransaction();
}

if(isset($_REQUEST["transactionmode"]) && $_REQUEST["transactionmode"]=="D")       
{   
     $_bll->fillModel();
     $_bll->dbTransaction();
}

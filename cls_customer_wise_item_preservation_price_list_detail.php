<?php
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
        $this->_mdl =new mdl_customerwiseitempreservationpricelistdetail(); 
        $this->_dal =new dal_customerwiseitempreservationpricelistdetail();
    }

    public function dbTransaction()
    {
        $this->_dal->dbTransaction($this->_mdl);
               
       
    }
     public function pageSearch()
    {
        global $_dbh;
        $_grid="";
        $_grid="
        <table  id=\"searchDetail\" class=\"table table-bordered table-striped\" style=\"width:100%;\">
        <thead id=\"tableHead\">
            <tr>
            <th>Action</th>";
        $_grid.="</tr>
        </thead>";
        $i=0;
        $result=array();
        $main_id_name="customer_wise_item_preservation_price_list_id";
          if(isset($_POST[$main_id_name]))
            $main_id=$_POST[$main_id_name];
        else 
            $main_id=$this->_mdl->$main_id_name;
            
            if($main_id) {
                $sql="CAll csms1_search_detail('t.customer_wise_item_preservation_price_list_detail_id, t.customer_wise_item_preservation_price_list_id, t.packing_unit_id, t.rent_per_qty_month, t.rent_per_qty_season, t.customer_wise_item_preservation_price_list_detail_id','tbl_customer_wise_item_preservation_price_list_detail t','t.".$main_id_name."=".$main_id."')";
                $result=$_dbh->query($sql, PDO::FETCH_ASSOC);
            }
            
        $_grid.="<tbody id=\"tableBody\">";
        if(!empty($result))
        {
            foreach($result as $_rs)
            {
                $detail_id_label="customer_wise_item_preservation_price_list_detail_id";
                $detail_id=$_rs[$detail_id_label];
                $_grid.="<tr data-label=\"".$detail_id_label."\" data-id=\"".$detail_id."\" id=\"row".$i."\">";
                $_grid.="
                <td data-label=\"Action\" class=\"actions\"> 
                    <button class=\"btn btn-info btn-sm me-2 edit-btn\" data-id=\"".$detail_id."\" data-index=\"".$i."\">Edit</button>
                    <button class=\"btn btn-danger btn-sm delete-btn\" data-id=\"".$detail_id."\" data-index=\"".$i."\">Delete</button>
                </td>";

            
                $_grid.="
                <td data-label=\"customer_wise_item_preservation_price_list_id\" style=\"display:none\">".$_rs['customer_wise_item_preservation_price_list_id']."</td>"; 
           
                $_grid.="
                <td data-label=\"packing_unit_id\" style=\"display:none\">".$_rs['packing_unit_id']."</td>"; 
           
                $_grid.="
                <td data-label=\"rent_per_qty_month\" style=\"display:none\">".$_rs['rent_per_qty_month']."</td>"; 
           
                $_grid.="
                <td data-label=\"rent_per_qty_season\" style=\"display:none\">".$_rs['rent_per_qty_season']."</td>"; 
           $_grid.= "</tr>\n";
        $i++;
        }
        if($i==0) {
            $_grid.= "<tr id=\"norecords\" class=\"norecords\">";
            $_grid.="<td colspan=\"4\">No records available.</td>";$_grid.="</tr>";
        }
    } else {
            $_grid.= "<tr id=\"norecords\" class=\"norecords\">";
            $_grid.="<td colspan=\"4\">No records available.</td>";
            $_grid.="</tr>";
    }
        $_grid.="</tbody>
        </table> ";
        echo $_grid; 
    }   
}
 class dal_customerwiseitempreservationpricelistdetail                         
{
    public function dbTransaction($_mdl)                     
    {
        global $_dbh;
        
        $_dbh->exec("set @p0 = ".$_mdl->customer_wise_item_preservation_price_list_detail_id);
        $_pre=$_dbh->prepare("CALL customer_wise_item_preservation_price_list_detail_transaction (@p0,?,?,?,?,?) ");
        $_pre->bindParam(1,$_mdl->customer_wise_item_preservation_price_list_id);
        $_pre->bindParam(2,$_mdl->packing_unit_id);
        $_pre->bindParam(3,$_mdl->rent_per_qty_month);
        $_pre->bindParam(4,$_mdl->rent_per_qty_season);
        $_pre->bindParam(5,$_mdl->detailtransactionmode);
        $_pre->execute();
        
    }
}
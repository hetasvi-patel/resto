<?php  
include_once(__DIR__ . "/../config/connection.php");
require_once(__DIR__ . "/../include/functions.php");
class mdl_citymaster 
{   
    public $generator_table_layout;
    public $generator_fields_names;
    public $generator_fields_types;
    public $generator_field_scale;
    public $generator_dropdown_table;
    public $generator_label_column;
    public $generator_value_column;
    public $generator_where_condition;
    public $generator_fields_labels;
    public $generator_field_display;
    public $generator_field_required;
    public $generator_allow_zero;
    public $generator_allow_minus;
    public $generator_chk_duplicate;
    public $generator_field_data_type;
    public $generator_field_is_disabled;
    public $generator_after_detail;
    protected $fields = [];

    public function __get($name) {
        return $this->fields[$name] ?? null;
    }

    public function __set($name, $value) {
        $this->fields[$name] = $value;
    }
    public function __construct() {
        global $_dbh;
        global $database_name;
        global $tbl_generator_master;
        global $tbl_city_master;
        $select = $_dbh->prepare("SELECT `generator_options` FROM `{$tbl_generator_master}` WHERE `table_name` = ?");
        $select->bindParam(1,  $tbl_city_master);
        $select->execute();
        $row = $select->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            $generator_options = json_decode($row["generator_options"]);
            if ($generator_options) {
                $this->generator_table_layout=$generator_options->table_layout;
                $this->generator_fields_names=$generator_options->field_name;
                $this->generator_fields_types=$generator_options->field_type;
                $this->generator_field_scale=$generator_options->field_scale;
                $this->generator_dropdown_table=$generator_options->dropdown_table;
                $this->generator_label_column=$generator_options->label_column;
                $this->generator_value_column=$generator_options->value_column;
                $this->generator_where_condition=$generator_options->where_condition;
                $this->generator_fields_labels=$generator_options->field_label;
                $this->generator_field_display=$generator_options->field_display;
                $this->generator_field_required=$generator_options->field_required;
                $this->generator_allow_zero=$generator_options->allow_zero;
                $this->generator_allow_minus=$generator_options->allow_minus;
                $this->generator_chk_duplicate=$generator_options->chk_duplicate;
                $this->generator_field_data_type=$generator_options->field_data_type;
                $this->generator_field_is_disabled=$generator_options->is_disabled;
                $this->generator_after_detail=$generator_options->after_detail;
            }
        }
    }

}

class bll_citymaster                           
{   
    public $_mdl;
    public $_dal;

    public function __construct()    
    {
        $this->_mdl =new mdl_citymaster(); 
        $this->_dal =new dal_citymaster();
    }

    public function dbTransaction()
    {
        $this->_dal->dbTransaction($this->_mdl);
               
       
            
       if($this->_mdl->_transactionmode =="D")
       {
           if(!$_SESSION["sess_message"] || $_SESSION["sess_message"]=="") {
               $_SESSION["sess_message"]="Record Deleted Successfully.";
               $_SESSION["sess_message_cls"]="success";
               $_SESSION["sess_message_title"]="Success!";
               $_SESSION["sess_message_icon"]="check-circle-fill";
            }
            header("Location:../srh_city_master.php");
       }
       if($this->_mdl->_transactionmode =="U")
       {
            header("Location:../srh_city_master.php");
       }
       if($this->_mdl->_transactionmode =="I")
       {
            header("Location:../frm_city_master.php");
       }

    }
 
    public function fillModel()
    {
        $this->_dal->fillModel($this->_mdl);
    
    }
     public function pageSearch()
    {
        global $_dbh;
        global $database_name;
        global $canUpdate;
        global $canDelete;
        if(COMPANY_ID!=ADMIN_COMPANY_ID) 
            $where_condition=" t.company_id=".COMPANY_ID;
        else
            $where_condition=" 1=1 ";
        $sql="CAll ".$database_name."_search_detail('t.city_name, t2.state_name as val2, t.country_id, t.modified_date, u2.person_name as modified_by, t.city_id','tbl_city_master t INNER JOIN tbl_state_master t2 ON t.state_id=t2.state_id LEFT JOIN tbl_user_master u2  ON t.modified_by=u2.user_id ','{$where_condition}')";
        echo "<!-- Filter row -->
                <div class=\"row gx-2 gy-1 align-items-center\" id=\"search-filters\">";
                $k=0;
                             $k++;echo "<div class=\"col-auto\">
                            <input type=\"text\" class=\"form-control \" placeholder=\"Search City Name\" data-index=\"".$k."\" />
                        </div>";
                             $k++;echo "<div class=\"col-auto\">
                            <input type=\"text\" class=\"form-control \" placeholder=\"Search State Name\" data-index=\"".$k."\" />
                        </div>";
                             $k++;echo "<div class=\"col-auto\">
                            <input type=\"text\" class=\"form-control \" placeholder=\"Search Country Name\" data-index=\"".$k."\" />
                        </div>";
                             $k++;echo "<div class=\"col-auto\">
                            <input type=\"text\" class=\"form-control date-filter\" placeholder=\"Search Modified Date\" data-index=\"".$k."\" />
                        </div>";
                             $k++;echo "<div class=\"col-auto\">
                            <input type=\"text\" class=\"form-control \" placeholder=\"Search Modified By\" data-index=\"".$k."\" />
                        </div>";echo "</div>";
        echo "
        <table  id=\"searchMaster\" class=\"ui celled table display\">
        <thead>
            <tr>";
            if($canUpdate || $canDelete) {
                echo "<th>Action</th>";
            }
            $hstr="";$hstr.="<th> City Name</th>";
                         $hstr.="<th> State Name</th>";
                         $hstr.="<th> Country Name</th>";
                         $hstr.="<th> Modified Date</th>";
                         $hstr.="<th> Modified By</th>";
                         echo $hstr;
               echo "</tr>
        </thead>
        <tbody>";
         $_grid="";
         $j=0;
        foreach($_dbh-> query($sql) as $_rs)
        {
            $j++;
        
        $_grid.="<tr>";
        if($canUpdate || $canDelete) {
        $_grid.="<td>";
        }
        if($canUpdate) {
        $_grid.="<form  method=\"post\" action=\"frm_city_master.php\" style=\"display:inline; margin-rigth:5px;\">
            <i class=\"fa fa-edit update\" style=\"cursor: pointer;\"></i>
            <input type=\"hidden\" name=\"city_id\" value=\"".$_rs["city_id"]."\" />
            <input type=\"hidden\" name=\"transactionmode\" value=\"U\"  />
            </form>";
        }
        if($canDelete) { 
        $_grid.="<form  method=\"post\" action=\"classes/cls_city_master.php\" style=\"display:inline;\">
            <i class=\"fa fa-trash delete\" style=\"cursor: pointer;\"></i>
            <input type=\"hidden\" name=\"city_id\" value=\"".$_rs["city_id"]."\" />
            <input type=\"hidden\" name=\"transactionmode\" value=\"D\"  />
            </form>";
        }
        if($canUpdate || $canDelete) {
        $_grid.="</td>";
        }
        $fieldvalue=$_rs["city_name"];
                                $_grid.= "<td> ".$fieldvalue." </td>"; 
                        
                                $fieldvalue=$_rs["val2"];
                                $_grid.= "<td> ".$fieldvalue." </td>"; 
                        
                                $fieldvalue=$_rs["country_id"];
                                $_grid.= "<td> ".$fieldvalue." </td>"; 
                        
                                
                                if(!empty($_rs["modified_date"])) {
                                $fieldvalue=date("d/m/Y",strtotime($_rs["modified_date"]));
                                $fieldvalue.="<br><small> ".date("h:i:s a",strtotime($_rs["modified_date"]))."</small>";
                                }
                                
                                $_grid.= "<td> ".$fieldvalue." </td>"; 
                        
                                $fieldvalue=$_rs["modified_by"];
                                $_grid.= "<td> ".$fieldvalue." </td>"; 
                        
                                $_grid.= "</tr>\n";
           
            
        }   
         if($j==0) {
                $_grid.= "<tr>";
                $_grid.="<td colspan=\"8\">No records available.</td>";
                $_grid.="<td style=\"display:none\">&nbsp;</td>";
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
     public function fetchCountry() {
        global $_dbh;
        
        $state_id = $_POST['state_id'];
        $columns = 'c.country_id AS country_id, c.country_name AS country_name';
        $tableName = 'tbl_state_master s JOIN tbl_country_master c ON s.country_id = c.country_id';
        $whereCondition = "s.state_id = :state_id";
        $stmt = $_dbh->prepare("CALL csms1_search_detail(:columns, :tableName, :whereCondition)");
        $stmt->bindParam(':columns', $columns, PDO::PARAM_STR);
        $stmt->bindParam(':tableName', $tableName, PDO::PARAM_STR);
        $whereCondition = "s.state_id = $state_id";
        $stmt->bindParam(':whereCondition', $whereCondition, PDO::PARAM_STR);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        echo json_encode($row ?: []);
    }
    public function checkDuplicate() {
        global $_dbh;
        global $database_name;
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
        try {
            $sql="CAll ".$database_name."_check_duplicate('".$column_name."','".$column_value."','".$id_name."','".$id_value."','".$table_name."',@is_duplicate)";
            $stmt=$_dbh->prepare($sql);
            $stmt->execute();
            $result = $_dbh->query("SELECT @is_duplicate");
            $is_duplicate = $result->fetchColumn();
            echo $is_duplicate;
            exit;
        }
        catch (PDOException $e) {
            //echo "Error: " . $e->getMessage();
            echo 0;
            exit;
        }
        echo 0;
        exit;
    }
    public function getForm($transactionmode="I",$label_classes="col-12 col-sm-3 col-md-2 col-lg-2 col-xl-2 col-xxl-1", $field_classes="col-12 col-sm-6 col-md-4 col-lg-4 col-xl-3 col-xxl-2") {
        $output=""; $hidden_str="";
         if(isset($this->_mdl->generator_table_layout))
            $table_layout=$this->_mdl->generator_table_layout;
        else
            $table_layout="vertical";
        if(is_array($this->_mdl->generator_fields_names) && !empty($this->_mdl->generator_fields_names)){
            if($table_layout=="horizontal") {
                $label_layout_classes="col-4 col-sm-2 col-md-1 col-lg-1 control-label";
                $field_layout_classes="col-8 col-sm-4 col-md-3 col-lg-2";
            } else {
                $label_layout_classes=$label_classes." col-form-label";
                $field_layout_classes=$field_classes;
            }
            $output.='<div class="box-body">
                <div class="form-group row gy-2">';
            foreach($this->_mdl->generator_fields_names as $i=>$fieldname)
            {
                $required="";$checked="";$field_str="";$lbl_str="";$required_str="";$min_str="";$step_str="";$error_container="";$duplicate_str="";$cls_field_name="_".$fieldname;$is_disabled=0;$disabled_str="";$add_modal_cls="";

                if(!empty($this->_mdl->generator_field_required) && in_array($fieldname,$this->_mdl->generator_field_required)) {
                    $required=1;
                }
                if(!empty($this->_mdl->generator_field_is_disabled) && in_array($fieldname,$this->_mdl->generator_field_is_disabled)) {
                    $is_disabled=1;
                }
                if(!empty($this->_mdl->generator_chk_duplicate) && in_array($fieldname,$this->_mdl->generator_chk_duplicate)) {
                    $error_container='<div class="invalid-feedback"></div>';
                    $duplicate_str="duplicate";
                }
                if($this->_mdl->generator_fields_labels[$i]) {
                    $lbl_str='<label for="'.$fieldname.'" class="'.$label_layout_classes.'">'.$this->_mdl->generator_fields_labels[$i].'';
                        if($table_layout=="vertical") {
                            $field_layout_classes=$field_classes;
                        } 
                } else {
                    if($table_layout=="vertical") {
                        $field_layout_classes="col-12";
                    } 
                }   
                if($required) {
                    $required_str="required";
                    $error_container='<div class="invalid-feedback"></div>';
                    $lbl_str.="*";
                }
                if($is_disabled) {
                    $disabled_str="disabled";
                }
                $lbl_str.="</label>";
                switch($this->_mdl->generator_fields_types[$i]) {
                    case "text":
                    case "email":
                    case "file":
                    case "date":
                    case "datetime-local":
                    case "radio":
                    case "checkbox":
                    case "number":
                    case "select":
                        $value="";$field_str="";$cls="";$flag=0;
                            $table=explode("_",$fieldname);
                            $field_name=$table[0]."_name";
                            $fields=$fieldname.", ".$table[0]."_name";
                            $tablename="tbl_".$table[0]."_master";
                            $selected_val="";
                            if($this->_mdl->$cls_field_name) {
                                $selected_val=$this->_mdl->$cls_field_name;
                            }
                            if(!empty($this->_mdl->generator_where_condition[$i]))
                                $where_condition_val=$this->_mdl->generator_where_condition[$i];
                            else {
                                $where_condition_val=null;
                            }
                            if($this->_mdl->generator_fields_types[$i]=="checkbox" || $this->_mdl->generator_fields_types[$i]=="radio") {
                                    $cls.=$required_str;
                                    if(!empty($this->_mdl->generator_dropdown_table[$i]) && !empty($this->_mdl->generator_label_column[$i]) && !empty($this->_mdl->generator_value_column[$i])) {
                                        $flag=1;
                                        $field_str.=getChecboxRadios($this->_mdl->generator_dropdown_table[$i],$this->_mdl->generator_value_column[$i],$this->_mdl->generator_label_column[$i],$where_condition_val,$fieldname,$selected_val, $cls, $required_str, $this->_mdl->generator_fields_types[$i]).$error_container;
                                    }
                                    else{
                                            if($transactionmode=="U" && $this->_mdl->$cls_field_name==1) {
                                                $chk_str="checked='checked'";
                                            }
                                            $value="1";
                                            $field_str.=addHidden($fieldname,0);
                                    }
                            } else {
                                $cls.="form-control ".$required_str." ".$duplicate_str;
                                $chk_str="";
                                    if(isset($this->_mdl)) {
                                        $value=$this->_mdl->$cls_field_name; 
                                }
                            }
                            if(!empty($value) && ($this->_mdl->generator_fields_types[$i]=="date" || $this->_mdl->generator_fields_types[$i]=="datetime-local" || $this->_mdl->generator_fields_types[$i]=="datetime" || $this->_mdl->generator_fields_types[$i]=="timestamp")) {
                                $value=date("Y-m-d",strtotime($value));
                            }
                            if($this->_mdl->generator_fields_types[$i]=="number") {
                                $step="";
                                if(!empty($this->_mdl->generator_field_scale[$i]) && $this->_mdl->generator_field_scale[$i]>0) {
                                    for($k=1;$k<$this->_mdl->generator_field_scale[$i];$k++) {
                                        $step.=0;
                                    }
                                    $step="0.".$step."1";
                                } else {
                                    $step=1;
                                }
                                $step_str='step="'.$step.'"';
                                $min=1; 
                                if(!empty($this->_mdl->generator_allow_zero) && in_array($fieldname,$this->_mdl->generator_allow_zero)) 
                                    $min=0;
                                if(!empty($this->_mdl->generator_allow_minus) && in_array($fieldname,$this->_mdl->generator_allow_minus)) 
                                $min="";

                                $min_str='min="'.$min.'"';
                                $field_str.=addNumber($fieldname,$value,$required_str,$disabled_str,$cls,$duplicate_str,$min_str,$step_str).$error_container;
                            }
                            else if($this->_mdl->generator_fields_types[$i]=="select") {
                                $cls="form-select ".$required_str." ".$duplicate_str;
                                
                                if(!empty($this->_mdl->generator_dropdown_table[$i]) && !empty($this->_mdl->generator_label_column[$i]) && !empty($this->_mdl->generator_value_column[$i]))
                                    $field_str.=getDropdown($this->_mdl->generator_dropdown_table[$i],$this->_mdl->generator_value_column[$i],$this->_mdl->generator_label_column[$i],$where_condition_val,$fieldname,$selected_val, $cls, $required_str).$error_container;
                                            if($fieldname=="state_id") {
                                                    $add_modal_cls="add_modal_icon";
                                                    $field_str.='<i class="bi bi-plus" data-bs-toggle="modal" data-bs-target="#addStateModal" title="Add State"></i>';
                                                }
                                
                            }
                        else {
                                if($flag==0) {
                                    $field_str.=addInput($this->_mdl->generator_fields_types[$i],$fieldname,$value,$required_str,$disabled_str,$cls,$duplicate_str,$chk_str).$error_container;
                                }
                            }
                        break;
                    case "hidden":
                        $lbl_str="";
                        if($this->_mdl->generator_field_data_type[$i]=="int" || $this->_mdl->generator_field_data_type[$i]=="bigint"  || $this->_mdl->generator_field_data_type[$i]=="tinyint" || $this->_mdl->generator_field_data_type[$i]=="decimal")
                            $hiddenvalue=0;
                        else
                            $hiddenvalue="";
                         if($fieldname=="country_id") {
                                            $lbl_str='<label for="country_name" class="'.$label_layout_classes.'">Country</label>';
                                            $field_str .= '<input type="text" class="form-control" id="country_name" name="country_name" value="" disabled />';
                                         } 
                        if($fieldname=="company_id") {
                            $hiddenvalue=COMPANY_ID;
                        }
                        else if($fieldname=="created_by") {
                            if($transactionmode=="U") {
                                $hiddenvalue=$this->_mdl->$cls_field_name;
                            } else {
                                $hiddenvalue=USER_ID;
                            }
                        } else if($fieldname=="created_date") {
                            if($transactionmode=="U") {
                                $hiddenvalue=$this->_mdl->$cls_field_name;
                            } else {
                                $hiddenvalue=date("Y-m-d H:i:s");
                            }
                        } else if($fieldname=="modified_by") {
                            $hiddenvalue=USER_ID; 
                        } else if($fieldname=="modified_date") {
                            $hiddenvalue=date("Y-m-d H:i:s");
                        } else {
                            if($transactionmode=="U") {
                                $hiddenvalue=$this->_mdl->$cls_field_name;
                            } 
                        }
                        $hidden_str.=addHidden($fieldname,$hiddenvalue);
                    
                        break;
                    case "textarea":
                        $value="";
                        if(isset($this->_mdl)){
                                $value=$this->_mdl->$cls_field_name;
                            }
                        $field_str.=addTextArea($fieldname,$value,$required_str,$disabled_str,$cls,$duplicate_str).$error_container;
                        break;
                    default:
                        break;
                } //switch ends
                 if(empty($this->_mdl->generator_after_detail) || (!empty($this->_mdl->generator_after_detail) && !in_array($fieldname,$this->_mdl->generator_after_detail))) {
                    if($table_layout=="vertical" && $this->_mdl->generator_fields_types[$i]!="hidden") {
                        $output.='<div class="row mb-3 align-items-center">';
                    }
                    $output.=$lbl_str;
                    if($field_str) {
                   $output .= '<div class="'.$field_layout_classes.' '.$add_modal_cls.'">';

                    $output.=$field_str;
                    $output.='</div>';
                    }
                    if($table_layout=="vertical" && $this->_mdl->generator_fields_types[$i]!="hidden") {
                        $output.='</div>';
                    }
                } else {
                    $lbl_array[]=$lbl_str;
                    $field_array[]=$field_str;
                }
            } // foreach ends
            $output.="</div><!-- /.row -->";
            $output.=$hidden_str;
               $output.="</div> <!-- /.box-body -->";
            
        
            if(!empty($field_array)) {
                $output.='<div class="box-body">
                <div class="form-group row gy-2">';
                 for($j=0;$j<count($field_array);$j++) {
                    if($table_layout=="vertical") {
                        $output.='<div class="row mb-3 align-items-center">';
                    }
                    $output.=$lbl_array[$j];
                    if($field_array[$j]) {
                        $output.='<div class="col-8 col-sm-4 col-md-3 col-lg-2>';
                        $output.=$field_array[$j];
                        $output.='</div>';
                    }
                    if($table_layout=="vertical") {
                        $output.='</div>';
                    }
                 } // for loop ends
                 $output.="</div><!-- /.row -->
              </div> <!-- /.box-body -->";
            }
        } // if ends
        return $output;
    } // function getForm ends
    
}

 class dal_citymaster                         
{
    public function dbTransaction($_mdl)                     
    {
        global $_dbh;

        
        try {
            $_dbh->exec("set @p0 = ".$_mdl->_city_id);
            $_pre=$_dbh->prepare("CALL city_master_transaction (@p0,?,?,?,?,?,?,?,?,?) ");
            
                if(is_array($_mdl->generator_fields_names) && !empty($_mdl->generator_fields_names)){
                    foreach($_mdl->generator_fields_names as $i=>$fieldname)
                    {
                        if($i==0)
                            continue;
                        $field=$_mdl->{"_".$fieldname};
                        $_pre->bindValue($i,$field);
                    }
                }
                $_pre->bindValue($i+1,$_mdl->_transactionmode);
                $_pre->execute();
            } catch (PDOException $e) {
                $_SESSION["sess_message"]=$e->getMessage();
                $_SESSION["sess_message_cls"]="alert-danger";
            }
        
    }
    public function fillModel($_mdl)
    {
        global $_dbh;
        $_pre=$_dbh->prepare("CALL city_master_fillmodel (?) ");
        $_pre->bindParam(1,$_REQUEST["city_id"]);
        $_pre->execute();
        $_rs=$_pre->fetchAll(); 
        if(!empty($_rs)) {
            if(is_array($_mdl->generator_fields_names) && !empty($_mdl->generator_fields_names)){
                foreach($_mdl->generator_fields_names as $i=>$fieldname)
                {
                    $_mdl->{"_".$fieldname}=$_rs[0][$fieldname];
                }
                $_mdl->_transactionmode =$_REQUEST["transactionmode"];
            }
        }
    }
}
$_bll=new bll_citymaster();
if(isset($_REQUEST["action"]))
{
    $action=$_REQUEST["action"];
    $_bll->$action();
}
if(isset($_POST["masterHidden"]) && ($_POST["masterHidden"]=="save"))
{
 
    if(is_array($_bll->_mdl->generator_fields_names) && !empty($_bll->_mdl->generator_fields_names)){
        foreach($_bll->_mdl->generator_fields_names as $i=>$fieldname)
        {
            if(isset($_REQUEST[$fieldname]) && !empty($_REQUEST[$fieldname]))
                $field=trim($_REQUEST[$fieldname]);
            else {
                if($_bll->_mdl->generator_field_data_type[$i]=="int" || $_bll->_mdl->generator_field_data_type[$i]=="bigint" || $_bll->_mdl->generator_field_data_type[$i]=="decimal")
                    $field=0;
                else
                    $field=null;
            }
            $_bll->_mdl->{"_".$fieldname}=$field;
        }
    }

 
if(isset($_REQUEST["transactionmode"]))
    $tmode=$_REQUEST["transactionmode"];
else
    $tmode="I";
$_bll->_mdl->_transactionmode =$tmode;
    if (isset($_REQUEST["ajaxAdd"]) && $_REQUEST["ajaxAdd"] == 1) {
        $_bll->_mdl->_ajaxAdd = 1;
    }
$_bll->dbTransaction();
}

if(isset($_REQUEST["transactionmode"]) && $_REQUEST["transactionmode"]=="D")       
{   
     $_bll->fillModel();
     $_bll->dbTransaction();
}

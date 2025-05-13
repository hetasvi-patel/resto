<?php
    include("classes/cls_customer_wise_item_preservation_price_list_master.php");
    include("include/header.php");
    include("include/theme_styles.php");
    include("include/header_close.php");
    $transactionmode="";
    if(isset($_REQUEST["transactionmode"]))       
    {    
        $transactionmode=$_REQUEST["transactionmode"];
    }
    if( $transactionmode=="U")       
    {    
        $_bll->fillModel();
        $label="Update";
    } else {
        $label="Add";
    }?>
<!-- ADD THE CLASS layout-top-nav TO REMOVE THE SIDEBAR. -->
<body class="hold-transition skin-blue layout-top-nav">
<?php
    include("include/body_open.php");
?>
<div class="wrapper">
<?php
    include("include/navigation.php");
?>
  <!-- Full Width Column -->
  <div class="content-wrapper">
    <div class="container-fluid">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <h1>
          <?php echo $label; ?> Data
        </h1>
        <ol class="breadcrumb">
          <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="srh_customer_wise_item_preservation_price_list_master.php"><i class="fa fa-dashboard"></i> Customer Wise Item Preservation Price List Master</a></li>
          <li class="active"><?php echo $label; ?></li>
        </ol>
      </section>

      <!-- Main content -->
      <section class="content">
    <div class="col-md-12" style="padding:0;">
       <div class="box box-info">
            <!-- form start -->
            <form id="masterForm" action="classes/cls_customer_wise_item_preservation_price_list_master.php"  method="post" class="form-horizontal needs-validation" enctype="multipart/form-data" novalidate>
            <div class="box-body">
                <div class="form-group row gy-2">
    <?php
            global $database_name;
            global $_dbh;
            $hidden_str="";
            $table_name="tbl_customer_wise_item_preservation_price_list_master";
            $lbl_array=array();
            $field_array=array();
            $err_array=array();
            $select = $_dbh->prepare("SELECT `generator_options` FROM `tbl_generator_master` WHERE `table_name` = ?");
            $select->bindParam(1, $table_name);
            $select->execute();
            $row = $select->fetch(PDO::FETCH_ASSOC);
             if($row) {
                    $generator_options=json_decode($row["generator_options"]);
                    if($generator_options) {
                        $table_layout=$generator_options->table_layout;
                        $fields_names=$generator_options->field_name;
                        $fields_types=$generator_options->field_type;
                        $field_scale=$generator_options->field_scale;
                        $dropdown_table=$generator_options->dropdown_table;
                        $label_column=$generator_options->label_column;
                        $value_column=$generator_options->value_column;
                        $where_condition=$generator_options->where_condition;
                        $fields_labels=$generator_options->field_label;
                        $field_display=$generator_options->field_display;
                        $field_required=$generator_options->field_required;
                        $allow_zero=$generator_options->allow_zero;
                        $allow_minus=$generator_options->allow_minus;
                        $chk_duplicate=$generator_options->chk_duplicate;
                        $field_data_type=$generator_options->field_data_type;
                        $field_is_disabled=$generator_options->is_disabled;
                        $after_detail=$generator_options->after_detail;

                        if($table_layout=="horizontal") {
                            $label_layout_classes="col-4 col-sm-2 col-md-1 col-lg-1 control-label";
                            $field_layout_classes="col-8 col-sm-4 col-md-3 col-lg-2";
                        } else {
                            $label_layout_classes="col-12 col-sm-3 col-md-2 col-lg-2 col-xl-2 col-xxl-1 col-form-label";
                            $field_layout_classes="col-12 col-sm-6 col-md-4 col-lg-4 col-xl-3 col-xxl-2";
                        }
                        
                        if(is_array($fields_names) && !empty($fields_names)) {
                            for($i=0;$i<count($fields_names);$i++) {
                                $required="";$checked="";$field_str="";$lbl_str="";$required_str="";$min_str="";$step_str="";$error_container="";$duplicate_str="";
                                 $cls_field_name="_".$fields_names[$i];$is_disabled=0;$disabled_str="";
                                 
                                if(!empty($field_required) && in_array($fields_names[$i],$field_required)) {
                                    $required=1;
                                }
                                if(!empty($field_is_disabled) && in_array($fields_names[$i],$field_is_disabled)) {
                                    $is_disabled=1;
                                }
                                if(!empty($chk_duplicate) && in_array($fields_names[$i],$chk_duplicate)) {
                                    $error_container='<div class="invalid-feedback"></div>';
                                    $duplicate_str="duplicate";
                                }
                                if($fields_labels[$i]) {
                                    $lbl_str='<label for="'.$fields_names[$i].'" class="'.$label_layout_classes.'">'.$fields_labels[$i].'';
                                     if($table_layout=="vertical") {
                                        $field_layout_classes="col-12 col-sm-6 col-md-4 col-lg-4 col-xl-3 col-xxl-2";
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
                                switch($fields_types[$i]) {
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
                                         $table=explode("_",$fields_names[$i]);
                                            $field_name=$table[0]."_name";
                                            $fields=$fields_names[$i].", ".$table[0]."_name";
                                            $tablename="tbl_".$table[0]."_master";
                                            $selected_val="";
                                            if(isset($_bll->_mdl->$cls_field_name)) {
                                                $selected_val=$_bll->_mdl->$cls_field_name;
                                            }
                                            if(!empty($where_condition[$i]))
                                                $where_condition_val=$where_condition[$i];
                                            else {
                                                $where_condition_val=null;
                                            }
                                            if($fields_types[$i]=="checkbox" || $fields_types[$i]=="radio") {
                                             $cls.=$required_str;
                                            if(!empty($dropdown_table[$i]) && !empty($label_column[$i]) && !empty($value_column[$i])) {
                                                $flag=1;
                                                $field_str.=getChecboxRadios($dropdown_table[$i],$value_column[$i],$label_column[$i],$where_condition_val,$fields_names[$i],$selected_val, $cls, $required_str, $fields_types[$i]).$error_container;
                                            }
                                            else{
                                                    if($transactionmode=="U" && $_bll->_mdl->$cls_field_name==1) {
                                                        $chk_str="checked='checked'";
                                                    }
                                                    $value="1";
                                                    $field_str.='<input type="hidden" name="'.$fields_names[$i].'" value="0" />';
                                            }
                                        } else {
                                            $cls.="form-control ".$required_str." ".$duplicate_str;
                                            $chk_str="";
                                             if(isset($_bll->_mdl)) {
                                                    $value=$_bll->_mdl->$cls_field_name; 
                                            }
                                        }
                                         if($fields_types[$i]=="number") {
                                            $step="";
                                            if(!empty($field_scale[$i]) && $field_scale[$i]>0) {
                                                for($k=1;$k<$field_scale[$i];$k++) {
                                                    $step.=0;
                                                }
                                                $step="0.".$step."1";
                                            } else {
                                                $step=1;
                                            }
                                            $step_str='step="'.$step.'"';
                                             $min=1; 
                                             if(!empty($allow_zero) && in_array($fields_names[$i],$allow_zero)) 
                                                 $min=0;
                                             if(!empty($allow_minus) && in_array($fields_names[$i],$allow_minus)) 
                                                $min="";

                                             $min_str='min="'.$min.'"';
                                         }
                                         if(!empty($value) && ($fields_types[$i]=="date" || $fields_types[$i]=="datetime-local" || $fields_types[$i]=="datetime" || $fields_types[$i]=="timestamp")) {
                                                $value=date("Y-m-d",strtotime($value));
                                         }
                                         if($fields_types[$i]=="select") {
                                            $cls="form-select ".$required_str." ".$duplicate_str;
                                           
                                            if(!empty($dropdown_table[$i]) && !empty($label_column[$i]) && !empty($value_column[$i]))
                                                $field_str.=getDropdown($dropdown_table[$i],$value_column[$i],$label_column[$i],$where_condition_val,$fields_names[$i],$selected_val, $cls, $required_str).$error_container;
                                        } else {
                                            if($flag==0) {
                                                $field_str.='<input type="'.$fields_types[$i].'" class="'.$cls.'" id="'.$fields_names[$i].'" name="'.$fields_names[$i].'" placeholder="Enter '.ucwords(str_replace("_"," ",$fields_names[$i])).'" value= "'.$value.'"  '.$min_str.' '.$step_str.' '.$chk_str.'  '.$disabled_str.' '.$required_str.' />
                                                '.$error_container;
                                            }
                                        }
                                        break;
                                    case "hidden":
                                        $lbl_str="";
                                        if($field_data_type[$i]=="int" || $field_data_type[$i]=="bigint"  || $field_data_type[$i]=="tinyint" || $field_data_type[$i]=="decimal")
                                            $hiddenvalue=0;
                                        else
                                            $hiddenvalue="";
                                        if($fields_names[$i]!="modified_by" && $fields_names[$i]!="modified_date") {
                                            if($fields_names[$i]=="company_id") {
                                                $hiddenvalue=COMPANY_ID;
                                            }
                                            else if($fields_names[$i]=="created_by") {
                                                if($transactionmode=="U") {
                                                    $hiddenvalue=$_bll->_mdl->$cls_field_name;
                                                } else {
                                                    $hiddenvalue=USER_ID;
                                                }
                                            } else if($fields_names[$i]=="created_date") {
                                                if($transactionmode=="U") {
                                                    $hiddenvalue=$_bll->_mdl->$cls_field_name;
                                                } else {
                                                    $hiddenvalue=date("Y-m-d H:i:s");
                                                }
                                            } else {
                                                if($transactionmode=="U") {
                                                    $hiddenvalue=$_bll->_mdl->$cls_field_name;
                                                } 
                                            }
                                            $hidden_str.='
                                            <input type="'.$fields_types[$i].'" id="'.$fields_names[$i].'" name="'.$fields_names[$i].'" value= "'.$hiddenvalue.'"  />';
                                        }
                                        break;
                                    case "textarea":
                                        $value="";
                                        if(isset($_bll->_mdl)){
                                             $value=$_bll->_mdl->$cls_field_name;
                                            }
                                        $field_str.='<textarea id="'.$fields_names[$i].'" name="'.$fields_names[$i].'" class="'.$cls.'" '.$disabled_str.' placeholder="Enter '.ucwords(str_replace("_"," ",$fields_names[$i])).'"  '.$required_str.' >'.$value.'</textarea>
                                        '.$error_container;
                                        break;
                                    default:
                                        break;
                                } //switch ends
                                 $cls_err="";
                                    $lbl_err="";
                                   
                                if(empty($after_detail) || (!empty($after_detail) && !in_array($fields_names[$i],$after_detail))) {
                                    if($table_layout=="vertical" && $fields_types[$i]!="hidden") {
                                ?>
                                <div class="row mb-3 align-items-center">
                                <?php
                                    } // verticle condition ends
                                    echo $lbl_str;
                                    if($field_str) {
                                    ?>
                                    <div class="<?php echo $field_layout_classes." ".$cls_err; ?>"  >
                                    <?php
                                            echo $field_str;
                                            echo $lbl_err;
                                    ?>
                                    </div>
                                <?php
                                    }
                                if($table_layout=="vertical" && $fields_types[$i]!="hidden") {
                                ?>
                                </div>
                                <?php
                                    } // verticle condition ends
                                } else {
                                    $lbl_array[]=$lbl_str;
                                    $field_array[]=$field_str;
                                    $err_array[]=$lbl_err;
                                    $clserr_array[]=$cls_err;
                                }
                            } //for loop ends
                        } // field_types if ends
                    }
             } 
            
            ?>
                    <div id="gridContainer" class="table-responsive" style="width: 100%; display: block;">
    <table id="dataGrid" class="table table-bordered table-striped text-center align-middle">
        <thead class="thead-dark">
            <tr>
                <th>Packing Unit Name</th>
                <th>Rent/Month/Qty</th>
                <th>Rent/Season/Qty</th>
            </tr>
        </thead>
        <tbody id="gridBody">
            <!-- Dynamic rows will be populated here -->
        </tbody>
    </table>
</div>
                 </div><!-- /.row -->
              </div>
              <!-- /.box-body -->
           
<?php
    if(!empty($field_array)) {
?>
     <!-- remaining main table content-->
    <div class="box-body">
        <div class="form-group row gy-2">
    <?php
        for($j=0;$j<count($field_array);$j++) {
        if($table_layout=="vertical") {
    ?>
    <div class="row mb-3 align-items-center">
    <?php
            } // verticle condition ends
            echo $lbl_array[$j];
            if($field_array[$j]) {
            ?>
            <div class="col-8 col-sm-4 col-md-3 col-lg-2 <?php echo $clserr_array; ?>"  >
            <?php
                    echo $field_array[$j];
                    echo $err_array[$j];
            ?>
            </div>
    <?php
            }
     if($table_layout=="vertical") {
    ?>
    </div>
    <?php
            } // verticle condition ends
        } // after detail for loop ends
    ?>
    </div>
</div>
<?php
    } // empty detail array if ends
?>
<!-- .box-footer -->
              <div class="box-footer">
               <?php echo  $hidden_str; ?>
                <input type="hidden" id="transactionmode" name="transactionmode" value= "<?php if($transactionmode=="U") echo "U"; else echo "I";  ?>">
                <input type="hidden" id="modified_by" name="modified_by" value="<?php echo USER_ID; ?>">
                <input type="hidden" id="modified_date" name="modified_date" value="<?php echo date("Y-m-d H:i:s"); ?>">
                <input type="hidden" id="detail_records" name="detail_records" />
                                        <input type="hidden" id="deleted_records" name="deleted_records" />
                    <input type="hidden" name="masterHidden" id="masterHidden" value="save" />
                <input class="btn btn-success" type="button" id="btn_add" name="btn_add" value= "Save">
                
              </div>
              <!-- /.box-footer -->
        </form>
        <!-- form end -->
          </div>
          </div>
      </section>
      <!-- /.content -->
    </div>
   
    
    <!-- /.container -->
  </div>
  <!-- /.content-wrapper -->
  <?php
    include("include/footer.php");
?>
</div>
<!-- ./wrapper -->

<?php
    include("include/footer_includes.php");
?>
<script>
document.addEventListener("DOMContentLoaded", function () {
    let modifiedRows = {};
    let originalValues = {};
    const duplicateInputs = document.querySelectorAll(".duplicate");
    const masterForm = document.getElementById("masterForm");
    const gridContainer = document.getElementById("gridContainer");
    const gridBody = document.createElement("tbody");

    // Set initial focus
    const firstInput = masterForm.querySelector("input:not([type=hidden]), select, textarea");
    if (firstInput) firstInput.focus();

    // Grid setup
    gridContainer.innerHTML = `
        <table id="dataGrid" class="table table-bordered table-striped text-center align-middle">
            <thead class="thead-dark">
                <tr>
                    <th>Packing Unit Name</th>
                    <th>Rent/Month/Qty</th>
                    <th>Rent/Season/Qty</th>
                </tr>
            </thead>
        </table>
    `;
    const table = gridContainer.querySelector("#dataGrid");
    table.appendChild(gridBody);

    // Duplicate check
    function checkDuplicate(input) {
        let column_value = input.value.trim();
        if (column_value == "") return;

        let id_column = "<?php echo 'customer_wise_item_preservation_price_list_id' ?>";
        let id_value = document.getElementById(id_column).value;

        $.ajax({
            url: "<?php echo 'classes/cls_customer_wise_item_preservation_price_list_master.php'; ?>",
            type: "POST",
            data: {
                column_name: input.name,
                column_value: column_value,
                id_name: id_column,
                id_value: id_value,
                table_name: "<?php echo 'tbl_customer_wise_item_preservation_price_list_master'; ?>",
                type: "ajax"
            },
            success: function (response) {
                if (response == 1) {
                    input.classList.add("is-invalid");
                    input.focus();
                    if (input.nextElementSibling)
                        input.nextElementSibling.textContent = "Duplicate Value";
                } else {
                    input.classList.remove("is-invalid");
                    if (input.nextElementSibling)
                        input.nextElementSibling.textContent = "";
                }
            }
        });
    }

    duplicateInputs.forEach((input) => {
        input.addEventListener("blur", () => checkDuplicate(input));
    });

    function fetchUnits() {
        const itemId = document.getElementById("item_id").value;
        const customerId = document.getElementById("customer_id").value;

        if (itemId && customerId) {
            fetch(`classes/cls_customer_wise_item_preservation_price_list_master.php?action=fetch_units&item_id=${encodeURIComponent(itemId)}&customer_id=${encodeURIComponent(customerId)}`)
                .then(response => response.json())
                .then(data => {
                    if (data.length > 0) {
                        let html = "";
                        data.forEach(unit => {
                            originalValues[unit.packing_unit_id] = {
                                rent_per_qty_month: unit.rent_per_qty_month,
                                rent_per_qty_season: unit.rent_per_qty_season
                            };
                            html += `
                                <tr data-id="${unit.packing_unit_id}">
                                    <td>${unit.packing_unit_name}</td>
                                    <td contenteditable="true" class="editable rent-monthly">${unit.rent_per_qty_month}</td>
                                    <td contenteditable="true" class="editable rent-seasonal">${unit.rent_per_qty_season}</td>
                                </tr>
                            `;
                        });
                        gridBody.innerHTML = html;

                        gridBody.querySelectorAll(".editable").forEach(cell => {
                            cell.addEventListener("input", function () {
                                const row = this.closest("tr");
                                const packingUnitId = row.dataset.id;
                                if (!modifiedRows[packingUnitId]) {
                                    modifiedRows[packingUnitId] = { ...originalValues[packingUnitId] };
                                }
                                if (this.classList.contains("rent-monthly")) {
                                    modifiedRows[packingUnitId].rent_per_qty_month = this.innerText.trim();
                                } else if (this.classList.contains("rent-seasonal")) {
                                    modifiedRows[packingUnitId].rent_per_qty_season = this.innerText.trim();
                                }
                            });
                        });
                    } else {
                        gridBody.innerHTML = "";
                    }
                });
        } else {
            gridBody.innerHTML = "";
        }
    }

    document.getElementById("item_id").addEventListener("change", fetchUnits);
    document.getElementById("customer_id").addEventListener("change", fetchUnits);

    // ✅ Single Save Button Event
    document.getElementById("btn_add").addEventListener("click", async function (e) {
        e.preventDefault();

        // Validate form
        let i = 0;
        let firstInvalid;
        duplicateInputs.forEach((input) => checkDuplicate(input));

        if (!masterForm.checkValidity()) {
            masterForm.querySelectorAll(":invalid").forEach(function (input) {
                if (i === 0) firstInvalid = input;
                input.classList.add("is-invalid");
                input.nextElementSibling.textContent = input.validationMessage;
                i++;
            });
            if (firstInvalid) firstInvalid.focus();
            return false;
        } else {
            masterForm.querySelectorAll(".is-invalid").forEach(function (input) {
                input.classList.remove("is-invalid");
                input.nextElementSibling.textContent = "";
            });
        }

        // Prepare grid updates
        const itemId = document.getElementById("item_id").value;
        const customerId = document.getElementById("customer_id").value;
         const companyId = document.getElementById("company_id").value;
        const updates = [];

        for (const packingUnitId in modifiedRows) {
            updates.push({
                packing_unit_id: packingUnitId,
                item_id: itemId,
                customer_id: customerId,
                rent_per_qty_month: modifiedRows[packingUnitId].rent_per_qty_month || originalValues[packingUnitId].rent_per_qty_month,
                rent_per_qty_season: modifiedRows[packingUnitId].rent_per_qty_season || originalValues[packingUnitId].rent_per_qty_season,
                company_id: companyId  
            });
        }

        try {
            const saveResponse = await fetch("classes/cls_customer_wise_item_preservation_price_list_master.php?action=save_unit", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify(updates)
            });

            const saveResult = await saveResponse.json();

            if (saveResult.success) {
                const formData = new FormData(masterForm);
                const formResponse = await fetch(masterForm.action, {
                    method: "POST",
                    body: formData
                });

                const formResult = await formResponse.text(); // use for debugging if needed
                modifiedRows = {};

                Swal.fire({
                    icon: 'success',
                    title: 'Update Successful!',
                    text: 'Record updated successfully!',
                    confirmButtonText: 'OK'
                });
            } else {
                throw new Error("Grid save failed");
            }
        } catch (err) {
            console.error("Save Error:", err);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to update data.',
                confirmButtonText: 'OK'
            });
        }
    });
});

</script>
<?php
    include("include/footer_close.php");
?>
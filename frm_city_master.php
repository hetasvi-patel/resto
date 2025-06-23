<?php
 include("classes/cls_country_master.php");
 require_once("classes/cls_state_master.php");

    include("classes/cls_city_master.php");
//    require_once("classes/cls_state_master.php");
    include("include/header.php");
    include("include/theme_styles.php");
    include("include/header_close.php");
    $transactionmode="";
    $currentmenu_label=getCurrentMenuLabel();
    if(isset($_REQUEST["transactionmode"]))       
    {    
        $transactionmode=$_REQUEST["transactionmode"];
    }
    if( $transactionmode=="U")       
    {
        if (!$canUpdate) {
            $_SESSION["sess_message"]="You don't have permission to update ".$currentmenu_label.".";
            $_SESSION["sess_message_cls"]="danger";
            $_SESSION["sess_message_title"]="Permission Denied";
            $_SESSION["sess_message_icon"]="exclamation-triangle-fill";
            header("Location: ".BASE_URL."srh_country_master.php");
            exit();
        }
        $_bll->fillModel();
        $label="Update";
    } else {
        if (!$canAdd) {
            $_SESSION["sess_message"]="You don't have permission to add ".$currentmenu_label.".";
            $_SESSION["sess_message_cls"]="danger";
            $_SESSION["sess_message_title"]="Permission Denied";
            $_SESSION["sess_message_icon"]="exclamation-triangle-fill";
            header("Location: ".BASE_URL."srh_city_master.php");
            exit();
        }
        $label="Add";
    }
 $state_bll=new bll_statemaster();
 $country_bll=new bll_countrymaster();
?>
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
      </section>

      <!-- Main content -->
      <section class="content">
    <div class="col-md-12" style="padding:0;">
       <div class="box box-info">
            <!-- form start -->
            <form id="masterForm" action="classes/cls_city_master.php"  method="post" class="form-horizontal needs-validation" enctype="multipart/form-data" novalidate>
                <?php
                    echo $_bll->getForm($transactionmode);
                ?>
            <!-- .box-footer -->
              <div class="box-footer">
                <input type="hidden" id="transactionmode" name="transactionmode" value= "<?php if($transactionmode=="U") echo "U"; else echo "I";  ?>">
                <input type="hidden" name="masterHidden" id="masterHidden" value="save" />
                <input class="btn btn-success" type="button" id="btn_add" name="btn_add" value= "Save">
                <input type="button" class="btn btn-primary" id="btn_search" name="btn_search" value="Search" onclick="window.location='srh_city_master.php'">
                <input class="btn btn-secondary" type="button" id="btn_reset" name="btn_reset" value="Reset" onclick="document.getElementById('masterForm').reset();" >
              </div>
              <!-- /.box-footer -->
        </form>
        <!-- form end -->
          </div>
          </div>
      </section>
      <!-- /.content -->
    </div>
         <!-- Add Country Modal -->
       <div class="modal fade" id="addCountryModal" tabindex="-1" aria-labelledby="addCountryModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="countryForm"  method="post" class="form-horizontal needs-validation" novalidate>
                    <div class="modal-header">
                        <h5 class="modal-title" id="addCountryModalLabel">Add New Country</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <?php echo $country_bll->getForm("I","col-12 col-sm-3 col-md-2 col-lg-2 col-xl-2 col-xxl-3","col-12 col-sm-6 col-md-4 col-lg-4 col-xl-3 col-xxl-9"); ?>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="masterHidden" id="masterHidden" value="save" />
                        <input type="hidden" name="ajaxAdd" id="ajaxAdd" value="1" />
                        <input class="btn btn-success" type="submit" id="countrybtn_add" name="countrybtn_add" value= "Save">
                        <input class="btn btn-dark" type="button" id="countrybtn_cancel" name="countrybtn_cancel" value= "Cancel" data-bs-dismiss="modal">
                    </div>
            </form>
            </div>
        </div>
        </div>
        <!-- Add State Modal -->
        <div class="modal fade" id="addStateModal" tabindex="-1" aria-labelledby="addStateModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="stateForm"  method="post" class="form-horizontal needs-validation" novalidate>
                    <div class="modal-header">
                        <h5 class="modal-title" id="addStateModalLabel">Add New State</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <?php echo $state_bll->getForm("I","col-12 col-sm-3 col-md-2 col-lg-2 col-xl-2 col-xxl-3 col-form-label","col-12 col-sm-6 col-md-4 col-lg-4 col-xl-3 col-xxl-9"); ?>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="masterHidden" id="masterHidden" value="save" />
                        <input type="hidden" name="ajaxAdd" id="ajaxAdd" value="1" />
                        <input class="btn btn-success" type="submit" id="statebtn_add" name="statebtn_add" value= "Save">
                        <input class="btn btn-dark" type="button" id="statebtn_cancel" name="statebtn_cancel" value= "Cancel" data-bs-dismiss="modal">
                    </div>
            </form>
            </div>
        </div>
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
    let jsonData = [];
    let editIndex = -1;
    let deleteData = [];
    let detailIdLabel="";
    const duplicateInputs = document.querySelectorAll(".duplicate");
    const masterForm = document.getElementById("masterForm");
    
    const firstInput = masterForm.querySelector("input:not([type=hidden]), select, textarea");
    if (firstInput) {
        firstInput.focus();
    }
       function fetchCountry(state_id) {
        $.ajax({
            url: "<?php echo "classes/cls_city_master.php"; ?>",
            type: "POST",
            data: { state_id: state_id, action: "fetchCountry" },
            success: function (response) {
                let data = JSON.parse(response);
                $('#country_id').val(data.country_id);
                $('#country_name').val(data.country_name);
            },
            error: function () {
                console.log("Error");
            }
        });
    }
    if(document.querySelector('#state_id')) { 
        document.getElementById("state_id").addEventListener("change", function (event) {
           
            let state_id = this.value;
            if (state_id == "") {
                return;
            }
            fetchCountry(state_id);
        });
    }
    function checkDuplicate(input) {
       let column_value = input.value.trim();
       if (column_value == "") return;
       let id_column="<?php echo "city_id" ?>";
       let id_value=document.getElementById(id_column).value;
       $.ajax({
            url: "<?php echo "classes/cls_city_master.php"; ?>",
            type: "POST",
            data: { column_name: input.name, column_value:column_value, id_name:id_column,id_value:id_value,table_name:"<?php echo "tbl_city_master"; ?>",action:"checkDuplicate"},
            success: function(response) {
                //let input=document.getElementById("party_sequence");
                if (response == 1) {
                    input.classList.add("is-invalid");
                    input.focus();
                    let message="";
                    if(input.validationMessage)
                        message=input.validationMessage;
                    else
                        message="Duplicate Value";
                    if(input.nextElementSibling) 
                      input.nextElementSibling.textContent = message;
                      return false;
                } else {
                   input.classList.remove("is-invalid");
                    if(input.nextElementSibling) 
                        input.nextElementSibling.textContent = "";
                }
            },
            error: function() {
                console.log("Error");
            }
        }); // ajax completed
    }
    /*duplicateInputs.forEach((input) => {
        input.addEventListener("blur", function () {
          checkDuplicate(input);
        });
      });*/
        $("#countryForm" ).on( "submit", function( event ) {
    event.preventDefault();
    if (!this.checkValidity()) {
        event.stopPropagation();
        let i=0;
        let firstelement;
        this.querySelectorAll(":invalid").forEach(function (input) {
            if(i==0) {
            firstelement=input;
            }
            input.classList.add("is-invalid");
            input.nextElementSibling.textContent = input.validationMessage; 
            i++;
        });
        if(firstelement) firstelement.focus(); 
        return false;
    } 
    const formData = $(this).serialize();
    const country_name=$(this).find("#country_name").val();
    $.ajax({
        url: "classes/cls_country_master.php",
        method: "POST",
        data: formData,
        success: function (country_id) {
            console.log(country_id);
            if (country_id > 0 && country_name) {
                 Swal.fire("", "Country saved successfully!", "success");

                $("#stateForm select[name='country_id']").each(function () {
                    const $dropdown = $(this);
                    if (!$dropdown.find('option[value="' + country_id + '"]').length) {
                        $dropdown.append(
                            $("<option>", {
                                value: country_id,
                                text: country_name,
                                selected: true
                            })
                        );
                    } else {
                        $dropdown.val(country_id);
                    }
                    
                });
                $("#addCountryModal").modal("hide");
                document.getElementById("countryForm").reset();
                $("#stateForm").find("#country_id").focus();
                
            } else {
                Swal.fire("", "Unexpected response: " + country_id, "error");
            }
        },
        error: function (xhr, status, error) {
            Swal.fire("", "Error saving city: " + error, "error");
        }
    });
} );
    $("#stateForm" ).on( "submit", function( event ) {
    event.preventDefault();
    if (!this.checkValidity()) {
        event.stopPropagation();
        let i=0;
        let firstelement;
        this.querySelectorAll(":invalid").forEach(function (input) {
            if(i==0) {
            firstelement=input;
            }
            input.classList.add("is-invalid");
            input.nextElementSibling.textContent = input.validationMessage; 
            i++;
        });
        if(firstelement) firstelement.focus(); 
        return false;
    } 
    const formData = $(this).serialize();
    const state_name=$(this).find("#state_name").val();
    $.ajax({
        url: "classes/cls_state_master.php",
        method: "POST",
        data: formData,
        success: function (state_id) {
            console.log(state_id);
            if (state_id > 0 && state_name) {
                 Swal.fire("", "State saved successfully!", "success");

                $("#masterForm select[name='state_id']").each(function () {
                    const $dropdown = $(this);
                    if (!$dropdown.find('option[value="' + state_id + '"]').length) {
                        $dropdown.append(
                            $("<option>", {
                                value: state_id,
                                text: state_name,
                                selected: true
                            })
                        );
                    } else {
                        $dropdown.val(state_id);
                    }
                    fetchCountry(state_id);
                });
                $("#addStateModal").modal("hide");
                document.getElementById("stateForm").reset();
            } else {
                Swal.fire("", "Unexpected response: " + state_id, "error");
            }
        },
        error: function (xhr, status, error) {
            Swal.fire("", "Error saving city: " + error, "error");
        }
    });
} );


 document.getElementById("btn_add").addEventListener("click", function (event) {
    //event.preventDefault();
    const form = document.getElementById("masterForm"); // Store form reference
    let i=0;
    let firstelement;
     duplicateInputs.forEach((input) => {
          checkDuplicate(input);
      });
    if (!form.checkValidity()) {
        //event.stopPropagation();
        form.querySelectorAll(":invalid").forEach(function (input) {
            if(i==0) {
                firstelement=input;
            }
          input.classList.add("is-invalid");
          input.nextElementSibling.textContent = input.validationMessage; 
          i++;
        });
         if(firstelement) firstelement.focus(); 
         return false;
    } else {
        form.querySelectorAll(".is-invalid").forEach(function (input) {
          input.classList.remove("is-invalid");
          input.nextElementSibling.textContent = "";
        });
    }
    setTimeout(function(){
        const invalidInputs = document.querySelectorAll(".is-invalid");
        if(invalidInputs.length>0)
        {} else{
        
            let transactionMode = document.getElementById("transactionmode").value;
            let message = "";
            let title = "";
            let icon = "success";

            if (transactionMode === "U") {
                message = "Record updated successfully!";
                title = "Update Successful!";
            } else {
                message = "Record added successfully!";
                title = "Save Successful!";
            }
            /*const result=await Swal.fire(title, message, icon);
            if (result.isConfirmed) {
              form.removeEventListener("submit", arguments.callee);
              document.getElementById("btn_add").click();
            }*/
             
             //form.removeEventListener("submit", arguments.callee);
             // document.getElementById("btn_add").click();
             (async function() {
              //await customAlert(message);
              result=await Swal.fire(title, message, icon);
                if (result.isConfirmed) {
                $("#masterForm").submit();
                }
                
            })();
        }
    },200);
} );
});
</script>
<?php
    include("include/footer_close.php");
?>
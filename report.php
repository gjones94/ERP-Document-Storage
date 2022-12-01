<?php
    include_once "partial/header.php";
?>


<form method="post" enctype="multipart/form-data" action="report.php">
    <div style="border-color: silver; border-style: solid; border-width: 2px; background-color: #111;" class="row mb-3 py-5">
        <div class="col-3 text-center">
            <button class="btn btn-primary p-4" name="unique" type="submit" value="unique_loan_numbers">
                Unique Loan Number Report
            </button>
        </div>

        <div class="col-3 text-center">
            <button class="btn btn-primary p-4" name="size" type="submit" value="doc_size_averages">
                Total Storage Size Report
            </button>
        </div>

        <div class="col-3 text-center">
            <button class ="btn btn-primary p-4" name="detail" type="submit" value="loan_counts">
                Loan Counts
            </button>
        </div>

        <div class="col-3 text-center">
            <button class="btn btn-primary p-4" name="missing" type="submit" value="loan_details">
                Missing Loans
            </button>
        </div>
    </div>
</form>


<?php
    if(isset($_REQUEST['unique']) && $_REQUEST['report'] = "unique_loan_numbers")
    {
        include "unique_loan_numbers.php";
    }
    else if(isset($_REQUEST['size']) && $_REQUEST['report'] = "doc_size_averages")
    {
        include "document_storage_sizes.php";
    }
    else if(isset($_REQUEST['detail']) && $_REQUEST['report'] = "loan_counts")
    {
        include "loan_document_counts.php";
    }
    else if(isset($_REQUEST['missing']) && $_REQUEST['report'] = "loan_details")
    {
        include "loan_details.php";
    }
?>
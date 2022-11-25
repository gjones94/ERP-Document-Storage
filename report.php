<?php
    include_once "partial/header.php";
?>


<form method="post" enctype="multipart/form-data" action="report.php">
    <div class="row">
        <div class="col-3 text-center">
            <button name="unique" type="submit" value="unique_loan_numbers">
                Unique Loan Numbers   
            </button>
        </div>

        <div class="col-3 text-center">
            <button name="size" type="submit" value="doc_size_averages">
                Document Storage Size
            </button>
        </div>

        <div class="col-3 text-center">
            <button name="detail" type="submit" value="loan_details">
                Loan Details
            </button>
        </div>

        <div class="col-3 text-center">
            <button name="missing" type="submit" value="missing_loan_details">
                Missing Loans
            </button>
        </div>
    </div>
</form>


<?php
    if(isset($_REQUEST['unique']) && $_REQUEST['report'] = "unique_loan_numbers")
    {
        include "UniqueLoanNumbers.php";
    }
    else if(isset($_REQUEST['size']) && $_REQUEST['report'] = "doc_size_averages")
    {
        include "DocumentStorageSizes.php";
    }
    else if(isset($_REQUEST['detail']) && $_REQUEST['report'] = "loan_details")
    {
        echo "Loan details";
    }
    else if(isset($_REQUEST['missing']) && $_REQUEST['report'] = "missing_loan_details")
    {
        echo "Missing loans";
    }
?>
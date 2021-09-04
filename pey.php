
<?php
require_once("business/payment.php")
extract($_REQUEST);

opayment = new Payment($conektaTokenId,$card,$name,$description,$total,$email);

if(opayment->pay()){
	echo "1";

}else
	echo $opayment->error;
	
}

?>
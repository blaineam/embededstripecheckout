<?php

/* This is the backend for the embedable stripe gateway.
	
	It has been developed by BLAINE MILLLER
	It is has no garantees and is provided as is.
	By Using this plugin you accept all legal responsibility and cannot hold BLAINE MILLER liable for any reason
	You are free to use and modify this software as you see fit. 
	If you do make modifications and plan on sharing them be sure to include a reference to the original developer BLAINE MILLER
	
	Enjoy the free software!
	- BLAINE MILLER */
	
	

/*configuration options */
$publickey="pk..."; // stripes public key test or live
$privatekey="sk..."; // stripes private key test or live
$who="Pay Blaine Miller"; // entity to whom the payments are going to for display purposes only
$what="for services rendered or gifts"; // description of what they are paying for for display purposes only
$inputdescription="for services rendered or gifts";// description of what they are paying for for display purposes only
$inputheader="Pay Blaine Miller"; // entity to whom the payments are going to for display purposes only
$inputtextdisplay = true; //show detail text above input amount form
$checkoutdescription="for services rendered or gifts";// description of what they are paying for for display purposes only
$checkoutheader="Pay Blaine Miller"; // entity to whom the payments are going to for display purposes only
$checkouttextdisplay = true; //show detail text above checkout form
$action='pay {{amount}}'; // default action text for stripe checkout. {{amount}} translates into the ammount with proper currency symbol
$stripecheckoutbuttontext="Pay with Card"; // default text shown on the stripe checkout button.
$customamountaction="pay"; //default action text for the custom inputed amount page
$customamountplaceholder="0.00"; // text used as placeholder for inputed custom amounts;
$confirmationMessage='Thank You for your payment of ${{amount}}! It should appear as BLAINE MILLER on your statements '; // success message for a properly processed transaction
$chargeFailedMessage='We are sorry but we were unable to charge your card. Please check your information and try again later.'; // message displayed if card cannot be charged or stripe fails.
$amount=0.00; // default amount used if posting directly to this script setup in the display format for your currency symbol
$currencysymbol="$"; //the symbol used for your currency
$currency="USD"; // stripe currency string. check here: https://support.stripe.com/questions/which-currencies-does-stripe-support
$conversionFromSmallestCurrencyUnitToCurrencySymbol = 100; //100 cents = 1 dollar; 1Yen = 1Yen etc; Check Stripe for more details
$fontfamily="sans-serif"; // font family for all of the custom text and forms  does not include stripe checkout script
$fontweight="100"; // font wieght for message texts. does not include stripe checkout script.
$fontcolor="white"; //font color for message texts. does not include stripe checkout script.
$inputamountborderwidth="1px"; //for custom input amount form: the input box border width.
$inputamountbordercolor="white"; //for custom input amount form: the input box border color.
$inputamountborderradius="7px"; //for custom input amount form: the input box border radius or rounded corners.
$inputamountboxwidth="50px"; //for custom input amount form: the input box width.
$inputamountheight="32px"; //for custom input amount form: the input box and submit button height.
$inputamountpadding="9px"; //for custom input amount form: the input box and submit button padding amount.
$inputamountfontsize="14px"; //for custom input amount form: the input box and submit button font size.
$inputbuttonbackgroundcolor="#28a0e5"; //for custom input amount form: the submit button background.
$inputbuttonfontcolor="white"; //for custom input amount form: the submit button text color.
$imputboxcolor="black"; // color used for imputed amount text color;
$htmlembed=false;





/*DO NOT MODIFY BELOW THIS POINT*/


require_once('stripe/init.php');

$stripe = array(
  "secret_key"      => $privatekey,
  "publishable_key" => $publickey
);

\Stripe\Stripe::setApiKey($stripe['secret_key']);

if($htmlembed == true){
?>
<style type="text/css">
body {background:none transparent;
	text-align: center;
}
</style>

<?php
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET["amount"])) {
try {
	$amount=$_GET["amount"] * (int)$conversionFromSmallestCurrencyUnitToCurrencySymbol;

$token  = $_POST['stripeToken'];

  $customer = \Stripe\Customer::create(array(
      'email' => $_POST['email'],
      'source'  => $token
  ));

  $charge = \Stripe\Charge::create(array(
      'customer' => $customer->id,
      'amount'   => $amount,
      'currency' => $currency
  ));

  ?>
  
  <h1 style="font-family:<?php echo $fontfamily; ?>; font-weight:<?php echo $fontweight; ?>; color: <?php echo $fontcolor; ?>"><?php echo str_replace("{{amount}}", $_GET["amount"], (string)$confirmationMessage); ?></h1>
  
  <?php


    } catch (Exception $e) {
	      ?>
  
  
  <h1 style="font-family:<?php echo $fontfamily; ?>; font-weight:<?php echo $fontweight; ?>; color: <?php echo $fontcolor; ?>"><?php echo str_replace("{{amount}}", $_GET["amount"], (string)$chargeFailedMessage); ?></h1>
  
  <?php
    }

}elseif(isset($_GET["amount"])){
	
	$amount=$_GET["amount"] * (int)$conversionFromSmallestCurrencyUnitToCurrencySymbol;
	
	if($checkouttextdisplay == true){
	
	?>
	
<p style="color: <?php echo $fontcolor; ?>; font-size:<?php echo $headerfontsize; ?>; font-family:<?php echo $fontfamily; ?>;"><?php echo $checkoutheader; ?> </p>
<p style="color: <?php echo $fontcolor; ?>; font-size:<?php echo $descriptionfontsize; ?>; font-family:<?php echo $fontfamily; ?>;"><?php echo $checkoutdescription; ?> </p>
<?php
	
	}
	?>

	<form action="" method="POST">
  <script
    src="https://checkout.stripe.com/checkout.js" class="stripe-button"
    data-key="<?php echo $publickey; ?>"
    data-amount="<?php echo $amount; ?>"
    data-name="<?php echo $who; ?>"
    data-description="<?php echo $what; ?>"
    data-panel-label="<?php echo $action; ?>"
    data-currency="<?php echo $currency; ?>"
    data-label="<?php echo $stripecheckoutbuttontext; ?>"
    data-locale="auto">
  </script>
</form>
	
	
	<?php
	
}else{
	
	if($inputtextdisplay == true){
	?>
	
	<form action="" method="GET">
<p style="color: <?php echo $fontcolor; ?>; font-size:<?php echo $headerfontsize; ?>; font-family:<?php echo $fontfamily; ?>;"><?php echo $inputheader; ?> </p>
<p style="color: <?php echo $fontcolor; ?>; font-size:<?php echo $descriptionfontsize; ?>; font-family:<?php echo $fontfamily; ?>;"><?php echo $inputdescription; ?> </p>
<?php
	}
	?>
	<p style="display:inline-block; color: <?php echo $fontcolor; ?>; font-size:<?php echo $inputamountfontsize; ?>; font-family:<?php echo $fontfamily; ?>;"><?php echo $currencysymbol; ?> </p><input type="text" id="amount" name="amount" placeholder="<?php echo $customamountplaceholder; ?>" style="display:inline-block; border-radius:<?php echo $inputamountborderradius; ?>; height:<?php echo $inputamountheight; ?>; padding: <?php echo $inputamountpadding; ?>; font-size:<?php echo $inputamountfontsize; ?>; width:<?php echo $inputamountboxwidth; ?>; font-family:<?php echo $fontfamily; ?>; border: <?php echo $inputamountborderwidth; ?> solid <?php echo $inputamountbordercolor; ?>; color: <?php echo $imputboxcolor; ?>;" />
	<input type="submit" value="<?php echo $customamountaction; ?>" style="display:inline-block; color: <?php echo $inputbuttonfontcolor; ?>; font-family:<?php echo $fontfamily; ?>; border-radius:<?php echo $inputamountborderradius; ?>; height:<?php echo $inputamountheight; ?>; padding:<?php echo $inputamountpadding; ?>; font-size: <?php echo $inputamountfontsize; ?>; border:0px solid transparent; background: <?php echo $inputbuttonbackgroundcolor; ?>;"/>
	</form>
	
	<?php
	
}



?>
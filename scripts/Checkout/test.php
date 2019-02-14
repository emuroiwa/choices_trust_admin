<form action="paypal_ec_redirect.php" method="POST">
  <input type="hidden" name="PAYMENTREQUEST_0_AMT" value="10.00"></input>
  <input type="hidden" name="currencyCodeType" value="USD"></input>
  <input type="hidden" name="paymentType" value="Sale"></input>
  <!--Pass additional input parameters based on your shopping cart. For complete list of all the parameters click here -->
  <input type="image" src="https://www.paypalobjects.com/webstatic/en_US/i/buttons/checkout-logo-large.png" alt="Check out with PayPal"></input>
</form>
SomethingDigital_CheckoutSuccess
================================

A simple module which allows the success page to be viewed more than once.

This allows much more efficient iterative styling of the page or static blocks on it, as well as analytics tag testing.

Please note that this won't allow viewing other user's orders.


## Usage

1. Enable under System Config -> Advanced -> Developer.
2. Login with a user who has placed an order.
3. Navigate to `checkout/onepage/success`.


## Production use

This is intended for development, testing, and staging.  Please consider carefully before using in a production environment.

However, it is designed to present no security or privacy risks if enabled on production (on purpose or by accident.)  Note that analytics platforms which do not de-duplicate conversions may see skewed data.

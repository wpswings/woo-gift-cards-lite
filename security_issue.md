We have received three separate external reports of security issues in one of your WordPress plugins: Ultimate Gift Cards for WooCommerce, slug woo-gift-cards-lite.

All three are confirmed on 3.2.9, the current release on wordpress.org, and each was reproduced in a running WooCommerce install. They are written up separately below, each with its own proof of concept, its own suggested fix and the name of the researcher who reported it. A coding pattern that runs through the plugin is described at the end, after the three issues, and it matters more than any of the individual functions named below.

1 of 3. Gift card value inflation on discounted purchases (CVE-2026-19436). Affects 3.2.9 and earlier releases.

Reported by Guillermo Álvarez Fernández. Please credit him in your changelog or advisory.

A gift card bought with an ordinary store discount coupon applied is issued at its full pre-discount face value while the buyer is charged only the discounted amount, and the resulting store credit is spendable. No account is needed at any point: WooCommerce guest checkout is enabled by default and we completed the whole flow logged out.

Quantity multiplies the effect. The issuing loop runs once per unit and takes the full Original Price on every pass, while the discount is spread across the line, so quantity 3 at 90% off pays $30 and issues $300 of credit.

To reproduce, on 3.2.9 with WooCommerce and guest checkout left at its default:

1. Logged out, open a gift card product and add it to the cart with a value of $100 
and a quantity of 3. 
2. Add any other ordinary product to the cart as well. 
3. Apply any valid store discount coupon that reduces the cart total, for example 90% off. 
4. Complete checkout as a guest. The amount charged is $30. 
5. Open the resulting order. One gift card coupon is issued per unit, and each carries 
the full value of $100. 
6. From a separate anonymous session, apply the issued codes to a later order. They 
apply at full face value.
In our own run a $100 gift card bought with a 90% off coupon cost $10 and produced a fixed_cart coupon worth $100, which then took a separate $100 order to $0.00.

The countermeasure in your readme FAQ does not hold. The FAQ points at the option that hides the Apply Coupon field on gift card carts. wps_wgm_hidding_coupon_field_on_cart() returns $enabled = true whenever the cart also holds a non gift card item, so adding any second product brings the field back. We re-ran the test with that option switched on: $20 paid, $100 issued. Step 2 above is there for that reason.

Suggested fix. The value of an issued gift card must never exceed the amount actually collected for the line item that issued it, calculated per unit. Today wps_wgm_woocommerce_checkout_create_order_line_item() writes the pre-discount unit price onto the order line as Original Price, and that figure is handed straight to wps_wgm_create_gift_coupon() inside the per-unit issuing loop. $item->get_total() is not consulted anywhere on that path. wps_add_fund_to_existing_coupon() reads $order->get_subtotal(), which is pre-discount in the same way, and needs the same reconciliation. Those two are examples rather than the full list: we tested the checkout issuing path and did not audit every route that creates or tops up a card.

2 of 3. Gift card redemption without an ownership check. Affects 3.2.9.

Reported by Shikhali Jamalzade. Please credit him in your changelog or advisory.

3.2.9 added a recipient check to wps_recharge_wallet_via_giftcard(). We reproduced the reported issue on 3.2.8 and then re-ran the identical request on 3.2.9: it is rejected, the card is left intact, and the rightful recipient still redeems successfully. That half is fixed. For your advisory, on 3.2.8 that handler was also registered on wp_ajax_nopriv, so a logged out visitor could destroy a card as well, and 3.2.9 closes that too.

A second route to the same outcome is still open. wps_wgm_redeem_gift_card_coupon() is unchanged between the released 3.2.8 and 3.2.9. It accepts a coupon code over wp_ajax_redeem_gift_card_coupon, checks only the wps-wgc-verify-nonce-check nonce, and calls set_amount( 0 ) on whatever coupon that code resolves to, with no comparison against the card's recipient and no capability check. The action is registered only when your Points and Rewards for WooCommerce plugin is active and enabled, so the exposure is configuration dependent rather than always present.

To reproduce, on 3.2.9 with Points and Rewards for WooCommerce active and enabled. The attacker is an ordinary Subscriber who is not the recipient of the card:

1. Log in as the attacker and keep the session cookie. 
curl -s -c /tmp/attacker.txt -X POST 'https://example.com/wp-login.php' \ 
 --data-urlencode 'log=attacker_subscriber' \ 
 --data-urlencode 'pwd=attacker_password' \ 
 --data-urlencode 'wp-submit=Log In' -o /dev/null 

 

2. Read the nonce off /my-account/. It is handed to any logged-in visitor on the 

account page and on any page carrying the balance check shortcode. 
NONCE=$(curl -s -b /tmp/attacker.txt 'https://example.com/my-account/' \ 
 

grep -o '"wps_nonce_check":"

Failed to load

*"' 

cut -d'"' -f4) 

 

3. Redeem a gift card belonging to somebody else. 
curl -s -b /tmp/attacker.txt -X POST 'https://example.com/wp-admin/admin-ajax.php' \ 
 --data-urlencode 'action=redeem_gift_card_coupon' \ 
 --data-urlencode "wps_wgm_nonce_check=$NONCE" \ 
 --data-urlencode 'coupon_code=VICTIM_GIFTCARD' 

 

Response: {"result":true,"msg":"Coupon redeem successfully..."} 

The victim's card drops to 0 and its full value is credited to the attacker as 

reward points.
In our own run the attacker account destroyed a $250 card on 3.2.9 and had its full value credited to itself as reward points.

Suggested fix. Every path that redeems, spends down or zeroes a gift card must confirm that the current user is that card's recipient before it changes the balance, and must fail closed when no recipient is recorded. The check added to wps_recharge_wallet_via_giftcard() in 3.2.9 has the right shape and wps_wgm_redeem_gift_card_coupon() needs the same one. Those two are the paths we tested and not the whole set. A nonce is not authorisation, so any handler that resolves a card from a submitted code needs the ownership comparison as well.

3 of 3. Unauthenticated disclosure of gift card codes and customer data (CVE-2026-19439). Affects 3.0.3 to 3.2.9.

Reported by Usama Arshad. Please credit him in your changelog or advisory.

define_admin_hooks() is called with no is_admin() gate, so wps_wgm_preview_report_details(), registered on init, runs on every front-end request despite living in the admin class. Its only guard calls wp_create_nonce( 'wps-gc-report-nonce' ) and immediately passes that same freshly created value to wp_verify_nonce(), so it never inspects anything the client sent and can never fail. There is no capability check and no ownership check anywhere on the path.

One GET request with no cookies returns HTTP 200 carrying the redemption code and its status, the original amount, the amount used, the remaining balance, the purchase date, the order ID, the product name and link, the recipient email address, the sender name, the scheduled delivery date, the personal message, the expiry date and the full redemption history. On a group gift the contributors' billing addresses and amounts are included as well. The disclosure still happens with your own gift cards enable setting switched off.

The disclosed codes are spendable. As an anonymous guest with a fresh cookie jar, a disclosed code took a $400 cart down to $150. The codes carry no email restriction and no usage limit.

order_id and coupon_id are not validated against each other, so any order on the store pairs with any coupon. An invalid pair returns HTTP 500 while a valid one returns HTTP 200, which tells an attacker which sequential post IDs are real gift cards and turns this into a bulk read of the store's outstanding cards.

To reproduce, on 3.2.9 with WooCommerce active:

1. As an administrator, create a gift card product and place an order for it so that a 
gift card coupon is generated. Note the order ID and the gift card coupon's post ID. 
Both are ordinary sequential post IDs. 

2. Log out completely, or use a browser profile with no cookies at all, and request: 

https://example.com/?wps_uwgc_report_details=wps_uwgc_report_details&order_id=123&coupon_id=456 

The page returns HTTP 200 and renders the full gift card report to the anonymous 
visitor. 

3. Still logged out and with the same empty cookie jar, add any product to the cart and 
apply the disclosed redemption code at checkout. The gift card balance is applied to 
the order.
The redemption code half of this is specific to 3.2.9, where WC_Coupon::get_code() arrives with the report modal rewrite. 3.0.3 through 3.2.8 return the same report without the code, so those releases disclose the customer data, the balances and the dates but not the code itself. 3.0.2 and earlier do not carry the handler.

Suggested fix. Nothing that renders gift card report data may run on an unauthenticated request, and every path that returns it must verify a nonce carried in the request and then confirm that the current user is entitled to see that specific card. Moving the registration behind is_admin() is not enough on its own, since admin-ajax.php and other admin side requests still reach it. We fired every other nopriv AJAX endpoint in the plugin and all of them returned 403, -1, 400 or 0, so this init handler is the only unauthenticated disclosure path we found. That is worth knowing because a fix confined to the AJAX layer would miss it completely.

The pattern behind issue 3, which is not confined to issue 3.

The guard described above, wp_create_nonce() on an action fed straight into wp_verify_nonce() on that same value, appears 14 times in 3.2.9. In every one of them the check is present, reads as a real nonce check on review, and can never fail, because the value being verified was created two lines earlier by the server rather than supplied by the client. Fixing only wps_wgm_preview_report_details() leaves the other thirteen in place.

The sites in 3.2.9:

woocommerce_gift_cards_lite.php lines 616 and 665
includes/class-woocommerce-gift-cards-common-function.php line 616
includes/class-makewebbetter-onboarding-helper.php line 887
admin/class-woocommerce-gift-cards-lite-admin.php lines 165, 341, 823, 1536 and 2171
admin/partials/class-wps-wgm-giftcard-report-list.php lines 308, 381 and 979
admin/partials/woocommerce-gift-cards-lite-admin-display.php line 222
public/class-woocommerce-gift-cards-lite-public.php line 2061
The property to hold everywhere: a nonce check is only a check when the value being verified comes from the request, so wp_verify_nonce() and check_admin_referer() must read the value out of $_REQUEST. A nonce check is also never a substitute for current_user_can() or for an ownership comparison, and several of the sites above have no other guard behind them.

One of those sites deserves a look on its own. wps_wgm_preview_email_on_single_page(), at public/class-woocommerce-gift-cards-lite-public.php line 2061, is also hooked on init and reachable by an unauthenticated visitor with the same construct. It renders a placeholder code rather than a live one, so nothing is disclosed there today, but its access control is the same non check and only the template content is keeping it harmless.

Could you confirm receipt, and let us know the version that will carry the fixes and roughly when you expect to publish it? Our process is described at wpscan.com/vulnerability-disclosure-policy. Details stay private until a fixed version is publicly available, and we are happy to verify a build before you release it.
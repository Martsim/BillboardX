<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>Example payment usage - Ipizza Testpank - Pangalink-net</title>
    </head>
    <body>
<?php

// THIS IS AUTO GENERATED SCRIPT
// (c) 2011-2016 Kreata OÜ www.pangalink.net

// File encoding: UTF-8
// Check that your editor is set to use UTF-8 before using any non-ascii characters

// STEP 1. Setup private key
// =========================

$private_key = openssl_pkey_get_private(
"-----BEGIN RSA PRIVATE KEY-----
MIIEogIBAAKCAQEAxxPZ0b1R15HfUEXyuVS1EHl71YAbmMTITG/kmdnixJ+FLCfa
GOdLLCDrXjhcm620lGNHLXUQIQozZCTKRUgPh2PfAHti+BcTeS+6gf/lCMfGBZUE
nweryPDO+lDQo/7pG4gmNWOsbFRAIJw+pSqlS9UrKP5G//WVT0wAnF40ZWFASrsQ
i3XEPBemKWwgwzIiXJGqLBLXbS48dD41fE36S3sc4Du8fA/NFd7ZwYuGBayDpX+L
PtfyRYysyYAsse3EVEQx32SlCuW8iHFwlvkKdT8v71USri9HUgiVoSP0X1DwuAN0
3XipbHSkv2zq/B8k64s9DIqaM3+8EL0MdlbJ8wIDAQABAoIBABDOpzwi7K7zfNQN
I8Hr2eGLMB5FU48hRfvWEom+sHuDMD4bGzmxBVKzcMuinIb0MmO4wgCfen4fm1kg
FgelXtJCZ0hmImPpptY7ZlalYRPNsfU0sJAwmJs9YWwWuJav5cRSSU+Gm78Fmehm
6I8PO6dcVul+FkZz2Gg6dW6+MVRBrGOG7dcu7Vd2Y6/H83aU6dc3cHo+L/cpT0zA
VdaHFYv3O9PX4Ime/HAeK243e5BOjBTgCGKrRBzhCvbXWF2HMYW1xVYodpIpgK0P
UxL709Clk9uazpyj5yhpF4iz2ojIAjmb+vTKdyjzWAum9CI8PuBZnUTcnoy7XNKf
5MvLXKkCgYEA9tNArpwhN8Fo1+0rqw7wOwx5eLy9CDMgYEPDVJ/bem714DBFl2N3
7OZcMgsQGHkdTTqAPDWAvaA4JBusJu7rEC66PMTWQ6O8kcYxiEXYYmrVQE3K28PH
BRbQDwwf6oKMw2/AvR+D5lwUnygntuIYxTuOyXVvvBBq3exJG1+Azb0CgYEAzno9
Sr/exdVVgHODKq3mvIJUE+5DbnbuDYIRLOKycCyeyVaKF4Q7/Cw+VqI4iOcxaOBr
eI1zk/NsUqS+0oC6qY5GltwC5TzGq5a2KXKhSlOLjKfZC37nOh61UAq0B8wVs1aA
abLg6lN8heROa7T1V5Ahk3JfmkrIi+D2EuEQuW8CgYB0poByRU2ZqSmgqvExZdz5
OzsJacG451jMxhnBm89BfMFtU8L/+j2KU5CNAhd4SX0kq5pBWlPeyqLdxcpFmDK1
si/IWoqc6vxKtK1iJwhN8wmX22wdEtizOgXYprVKwqo+D0m61/MhYrFIStUCJLbq
N+ySn5LQAb5P5cTjT+5yzQKBgEUffy1rFnU8eidKhHrhRQGOz/7sIP72KbOz+3P/
YKVVCsN0iT5eMoa1eRkfrbWHUG8/0jrFgA/jyjvQk2F9XwAsdU1D0mRT+F6xUcKG
caCkX29zMaI7lcVLSn/FVYfWtt0W9F5uWAWadXGNgQNlzUzHH7Zw77iDmKfbZAXl
+udZAoGALDrvMur5O/WvUKrjPku8OzOUg7f+uy02LctKQdbi0Kb74o9NefPBLYG9
CX9gxoqvlo3XhBPL6BxrkATLYcSwbuBmkNcdkPl/s5Im4wV8cnboHTAIDVxoOjbS
la45mq7d52bIZMvqceK+I9L9H0FpaICHAbhowiCXQ9Ukya4naQs=
-----END RSA PRIVATE KEY-----");

// STEP 2. Define payment information
// ==================================

$fields = array(
        "VK_SERVICE"     => "1011",
        "VK_VERSION"     => "008",
        "VK_SND_ID"      => "uid100010",
        "VK_STAMP"       => "12345",
        "VK_AMOUNT"      => "150",
        "VK_CURR"        => "EUR",
        "VK_ACC"         => "EE871600161234567892",
        "VK_NAME"        => "BXCode",
        "VK_REF"         => "1234561",
        "VK_LANG"        => "EST",
        "VK_MSG"         => "Torso Tiger",
        "VK_RETURN"      => 'http' . '://' . $_SERVER['HTTP_HOST'] .
                                             dirname ($_SERVER['PHP_SELF']) . '/recieve.php',
        "VK_CANCEL"      => 'http' . '://' . $_SERVER['HTTP_HOST'] .
                                             dirname ($_SERVER['PHP_SELF']) . '/reject_makse.php',
        "VK_DATETIME"    => "2016-04-08T20:16:14+0300",
        "VK_ENCODING"    => "utf-8",
        "VK_MAC"    	 => base64_encode($signature),
);

// STEP 3. Generate data to be signed
// ==================================

// Data to be signed is in the form of XXXYYYYY where XXX is 3 char
// zero padded length of the value and YYY the value itself
// NB! Ipizza Testpank expects symbol count, not byte count with UTF-8,
// so use `mb_strlen` instead of `strlen` to detect the length of a string

$data = str_pad (mb_strlen($fields["VK_SERVICE"], "UTF-8"), 3, "0", STR_PAD_LEFT) . $fields["VK_SERVICE"] .    /* 1011 */
        str_pad (mb_strlen($fields["VK_VERSION"], "UTF-8"), 3, "0", STR_PAD_LEFT) . $fields["VK_VERSION"] .    /* 008 */
        str_pad (mb_strlen($fields["VK_SND_ID"], "UTF-8"),  3, "0", STR_PAD_LEFT) . $fields["VK_SND_ID"] .     /* uid100010 */
        str_pad (mb_strlen($fields["VK_STAMP"], "UTF-8"),   3, "0", STR_PAD_LEFT) . $fields["VK_STAMP"] .      /* 12345 */
        str_pad (mb_strlen($fields["VK_AMOUNT"], "UTF-8"),  3, "0", STR_PAD_LEFT) . $fields["VK_AMOUNT"] .     /* 150 */
        str_pad (mb_strlen($fields["VK_CURR"], "UTF-8"),    3, "0", STR_PAD_LEFT) . $fields["VK_CURR"] .       /* EUR */
        str_pad (mb_strlen($fields["VK_ACC"], "UTF-8"),     3, "0", STR_PAD_LEFT) . $fields["VK_ACC"] .        /* EE871600161234567892 */
        str_pad (mb_strlen($fields["VK_NAME"], "UTF-8"),    3, "0", STR_PAD_LEFT) . $fields["VK_NAME"] .       /* BXCode */
        str_pad (mb_strlen($fields["VK_REF"], "UTF-8"),     3, "0", STR_PAD_LEFT) . $fields["VK_REF"] .        /* 1234561 */
        str_pad (mb_strlen($fields["VK_MSG"], "UTF-8"),     3, "0", STR_PAD_LEFT) . $fields["VK_MSG"] .        /* Torso Tiger */
        str_pad (mb_strlen($fields["VK_RETURN"], "UTF-8"),  3, "0", STR_PAD_LEFT) . $fields["VK_RETURN"] .     /* http://localhost:8080/project/0E22YvozIosyviwf?payment_action=success */
        str_pad (mb_strlen($fields["VK_CANCEL"], "UTF-8"),  3, "0", STR_PAD_LEFT) . $fields["VK_CANCEL"] .     /* http://localhost:8080/project/0E22YvozIosyviwf?payment_action=cancel */
        str_pad (mb_strlen($fields["VK_DATETIME"], "UTF-8"), 3, "0", STR_PAD_LEFT) . $fields["VK_DATETIME"];    /* 2016-04-08T20:16:14+0300 */

/* $data = "0041011003008009uid10001000512345003150003EUR020EE871600161234567892006BXCode0071234561011Torso Tiger069http://localhost:8080/project/0E22YvozIosyviwf?payment_action=success068http://localhost:8080/project/0E22YvozIosyviwf?payment_action=cancel0242016-04-08T20:16:14+0300"; */

// STEP 4. Sign the data with RSA-SHA1 to generate MAC code
// ========================================================

openssl_sign ($data, $signature, $private_key, OPENSSL_ALGO_SHA1);

/* EjWtBkZu23ufbJB9EC+cK5ALEeP/B0gaBA9GopgQzZp+eOFJiZiu5knhksEv24KnDIs4xrGbC7IogfmeSKMKxg1k/6o/4iXUbnT0Ap1lDtt6Vulv235d3cUJLR1kCK5nwSfUHontT2ErzFnGroern2e/fxFplxdVQJi9+JiKDFyVSfdwXh4zJRNrWTNoElVwYryJRgCRY2190FZsHMX4VzJh5el25cXdZ9jku7E9lzMwyVRz5UlqPMZAtvYNuQedFd2XjEwPcrY6nIHUBnMm3rO7F9Gh4E/liTfpC5q8f8HKGNMUbij4ejc8TvZJ6+dbOe/eNL0u9pJ1KCKJsOMBiA== */
$fields["VK_MAC"] = base64_encode($signature);

// STEP 5. Generate POST form with payment data that will be sent to the bank
// ==========================================================================
?>

        <h1><a href="http://localhost:8080/">Pangalink-net</a></h1>
        <p>Makse teostamise näidisrakendus <strong>"Annetus"</strong></p>

        <form method="post" action="http://localhost:8080/banklink/ipizza">
            <!-- include all values as hidden form fields -->
<?php foreach($fields as $key => $val):?>
            <input type="hidden" name="<?php echo $key; ?>" value="<?php echo htmlspecialchars($val); ?>" />
<?php endforeach; ?>

            <!-- draw table output for demo -->
            <table>
<?php foreach($fields as $key => $val):?>
                <tr>
                    <td><strong><code><?php echo $key; ?></code></strong></td>
                    <td><code><?php echo htmlspecialchars($val); ?></code></td>
                </tr>
<?php endforeach; ?>

                <!-- when the user clicks "Edasi panga lehele" form data is sent to the bank -->
                <tr><td colspan="2"><input type="submit" value="Edasi panga lehele" /></td></tr>
            </table>
        </form>

    </body>
</html>
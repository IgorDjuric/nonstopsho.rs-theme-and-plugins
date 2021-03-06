<?php
/**
 * @var WP_User $supplier
 */

$text =
    <<<HEREDOC
<!DOCTYPE html>
<html lang="sr-RS">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Nonstopshop.rs</title>
</head>
<body leftmargin="0" marginwidth="0" topmargin="0" marginheight="=0" offset="0">
<div id="wrapper" dir="ltr" style="background-color: #f7f7f7; margin: 0; padding: 70px 0 70px 0; -webkit-text-size-adjust: none !important; width: 100%;">
    <table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">
        <tr>
            <td align="center" valign="top">
                <div id="template_header_image">
                </div>
                <table border="0" cellpadding="0" cellspacing="0" width="600" id="template_container" style="box-shadow: 0 1px 4px rgba(0,0,0,0.1) !important; background-color: #ffffff; border: 1px solid #dedede; border-radius: 3px !important;">
                    <tr>
                        <td align="center" valign="top">
                            <!-- Header -->
                            <table border="0" cellpadding="0" cellspacing="0" width="600" id="template_header"
                                   style='background-color: #f45411; border-radius: 3px 3px 0 0 !important; color: #ffffff; border-bottom: 0; font-weight: bold; line-height: 100%; vertical-align: middle; font-family: "Helve=tica Neue", Helvetica, Roboto, Arial, sans-serif;'>
                                <tr>
                                    <td id="header_wrapper" style="padding: 36px 48px; display: block;">
                                        <h1 style='color: #ffffff; font-family: "Helvetica Neue", Helvetica, Roboto, Arial, sans-serif; font-size: 30px; font-weight: 300; line-height: 150%; margin: 0; text-align: left; text-shadow: 0 1px 0 #f67641;'>
                                            NonStopShop.rs - Narudžbenica</h1>
                                    </td>
                                </tr>
                            </table>
                            <!-- End Header -->
                        </td>
                    </tr>
                    <tr>
                        <td align="center" valign="top">
                            <!-- Body -->
                            <table border="0" cellpadding="0" cellspacing="0" width="600" id="template_body">
                                <tr>
                                    <td valign="top" id="body_content" style="background-color: #ffffff;">
                                        <!-- Content -->
                                        <table border="0" cellpadding="20" cellspacing="0" width="100%">
                                            <tr>
                                                <td valign="top" style="padding: 48px 48px 0;">
                                                    <div id="body_content_inner" style='color: #636363; font-family: "Helvetica Neue", Helvetica, Roboto, Arial, sans-serif; font-size: 14px; line-height: 150%; text-align: left;'>
                                                        <p style="margin: 0 0 16px;">Poštovanje, <br />
                                                            Naručujemo proizvode iz sledeće tabele. Molimo vas da pripremite proizvode i da nam ih dostavite ili <br />
                                                            da ih pripremite za preuzimanje (shodno dogovoru). <br />
                                                            <br />
                                                            Ukoliko bilo koji od proizvoda nemate na stanju molimo vas da nas o tome NAJHITNIJE obavestite na email prodaja@nonstopshop.rs  <br />
                                                            ili na tel. 011/33-34-722. <br />
                                                            Za sve dodatne informacije i pitanja molimo vas da nam se obratite na navedene kontakte. <br />
                                                            <br /></p>


                                                        <div style="margin-bottom: 40px;">
                                                            {{details}}
                                                        </div>

                                                        <div style="margin-bottom: 40px;">
                                                            Srdačan pozdrav, <br />
                                                            Služba nabavke <br />
                                                            www.NonStopShop.rs <br /><br />
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>
                                        <!-- End Content -->
                                    </td>
                                </tr>
                            </table>
                            <!-- End Body -->
                        </td>
                    </tr>
                    <tr>
                        <td align="center" valign="top">
                            <!-- Footer -->
                            <table border="0" cellpadding="10" cellspacing="0" width="600" id="template_footer">
                                <tr>
                                    <td valign="top" style="padding: 0; -webkit-border-radius: 6px;">
                                        <table border="0" cellpadding="10" cellspacing="0" width="100%">
                                            <tr>
                                                <td colspan="2" valign="middle" id="credit"
                                                    style="padding: 0 48px 48px 48px; -webkit-border-radius: 6px; border: 0; color: #f89870; font-family: Arial; font-size: 12px; line-height: 125%; text-align: center;">
                                                    <p>Nonstopshop.rs</p>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                            <!-- End Footer -->
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</div>
</body>
</html>    
HEREDOC;

include 'backOrderDetailsEmail.php';

$text = str_replace('{{details}}', $details, $text);

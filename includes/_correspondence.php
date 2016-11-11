<?php
/**
 * File name: functions.php
 *
 * This file is part of PROJECKLIST
 *
 * @author Daniel Racine <mailto.danielracine@gmail.com>
 * @link --
 * @package PROJECKLIST
 * @version 1
 *
 * Copyright (c) 2016 Daniel Racine
 * You should have received a copy of the MIT License
 * along with PROJECKLIST. If not, see <https://en.wikipedia.org/wiki/MIT_License>.
 */


    require_once("../vendor/PHPMailer/PHPMailerAutoload.php"); 


    function notificationMail($info) {

        //format each email
        $_body = formatNotificationEmail($info,'html');
        $_body_plain_txt = formatNotificationEmail($info,'txt');

        $mail = new PHPMailer();
        $mail->CharSet = 'UTF-8';
        // $mail->CharSet = 'ISO-8859-15';
        
                              
        //Set PHPMailer to use SMTP.
        $mail->isSMTP();            
        //Set SMTP host name                          
        $mail->Host = "smtp.gmail.com";
        //Set this to true if SMTP host requires authentication to send email
        $mail->SMTPAuth = true;                          
        //Provide username and password     
        $mail->Username = "noreply.tetsingstuff@gmail.com";                 
        $mail->Password = "4gFg49CbAuowAC9msKNTyU82CFtdqEP2hcZsEMZU+7Wi8bsC3g";                           
        //If SMTP requires TLS encryption then set it
        $mail->SMTPSecure = "tls";                           
        //Set TCP port to connect to 
        $mail->Port = 587;      

        $mail->setFrom('noreply.tetsingstuff@gmail.com', 'Projecklist');


        if (isset($info['email']) && isset($info['username']))
        {
            $mail->addAddress($info['email'], $info['username']);
        }
        else
        {
            userErrorHandler(0, '_correspondence.php', 'no email and username specified');
            exit;
        }

        // If this is a "contact us" correspondence, change the reply email
        if (isset($info['bcc_projecklist']) && $info['bcc_projecklist'])
        {
            $eml = 'projecklist@gmail.com';
            $mail->addReplyTo($eml, 'Projecklist');
            $mail->addBCC($eml);
        }


        $mail->isHTML(true);

        if (isset($info['subject']))
        {
            $mail->Subject = $info['subject'];
        }
        else
        {
            $mail->Subject = "Notification";
        }


        $mail->Body    = $_body;
        $mail->AltBody = $_body_plain_txt;


        if(!$mail->send()) {
            // DEBUG
            // echo 'Message could not be sent.';
            // echo 'Mailer Error: ' . $mail->ErrorInfo;
            // exit;
            return false;
        } else {
            return true;
        }
    }

    function formatNotificationEmail($info, $format) {
     
        //set the root
        // Store DEV server root
        // $server_root = 'http://dracine.local/~dracine/xdev/projecklist/mail';
        if (isset($info['locale']))
        {
            $server_root = dirname(__FILE__) . '/correspondence/locales/' . $info['locale'];
        }
        else
        {
            $server_root = dirname(__FILE__) . '/correspondence/locales/en_CA';
        }
     
        //grab the template content
        if (isset($info['template']))
        {
            $template = file_get_contents($server_root.'/' . $info['template'] . '.' . $format);
        }
        else
        {
            userErrorHandler(0, '_correspondence.php', 'no email template specified');
            exit;
        }
                 
        //replace all the tags
        if (isset($info['username']))
        {
            $template = preg_replace('/{USERNAME}/', $info['username'], $template);
        }

        if (isset($info['email']))
        {
            $template = preg_replace('/{EMAIL}/', $info['email'], $template);
        }

        if (isset($info['key']))
        {
            $template = preg_replace('/{KEY}/', $info['key'], $template);
        }

        if (isset($info['psw']))
        {
            $template = preg_replace('/{PSW}/', $info['psw'], $template);
        }

        if (isset($info['altemail']))
        {
            $template = preg_replace('/{ALTEMAIL}/', $info['altemail'], $template);
        }

        if (isset($info['locale']))
        {
            $template = preg_replace('/{LOCALE}/', $info['locale'], $template);
        }

        if (isset($info['reason']))
        {
            $template = preg_replace('/{REASON}/', $info['reason'], $template);
        }

        if (isset($info['correspondence']))
        {
            $template = preg_replace('/{CORR}/', $info['correspondence'], $template);
        }

        $template = preg_replace('/{SITEPATH}/','http://dracine.local/~dracine/xdev/projecklist/public', $template);
        $template = preg_replace('/{SITEPATH_ROOT}/','http://dracine.local/~dracine/xdev/projecklist', $template);
        $template = preg_replace('/{YEAR}/', date('Y'), $template);
        $template = preg_replace('/{ADMIN_EMAIL}/', 'projecklist@gmail.com', $template);
             
        //return the html of the template
        return $template;
     
    }



    
    // function submitEmail($_postArray) {

    //     $_post = labelvalueSplit($_postArray);

    //     // Get the email template and store it in a variable
    //     ob_start();
    //     require(__DIR__ . "/../mail/email_html.php");
    //     $email_html = ob_get_clean();

    //     // Get the plain email template and store it in a variable
    //     ob_start();
    //     require(__DIR__ . "/../mail/email_plain.php");
    //     $email_plain = ob_get_clean();

    //     // Get the plain email template and store it in a variable
    //     ob_start();
    //     echo debug_SubmitTable($_post);
    //     $email_htmltable = ob_get_clean();

    //     $attach_dir = __DIR__."/../mail/attach";
    //     $attach_name = _( 'process_email_attach_name' );
    //     $file_plain = $attach_dir . "/" . $attach_name . ".txt";
    //     $file_htmltable = $attach_dir . "/" . $attach_name . ".html";

    //     if( chmod($attach_dir, 0755) )
    //     {
    //         chmod($attach_dir, 0777);

    //         // Write the contents back to the file
    //         file_put_contents($file_plain, $email_plain, LOCK_EX);
    //         file_put_contents($file_htmltable, $email_htmltable, LOCK_EX);

    //         if( chmod($attach_dir, 0777) ) {
    //             chmod($attach_dir, 0755);
    //         }
    //     }

    //     $plain_attachment = chunk_split(base64_encode(file_get_contents($file_plain)));
    //     $htmltable_attachment = chunk_split(base64_encode(file_get_contents($file_htmltable)));
        
    //     $email_subject = "New Form Submission from " . $_post['value']['fld_contact_primary_fn'] . " " . $_post['value']['fld_contact_primary_ln'] . " | " . $_post['value']['fld_project_name'];
        
    //     // boundarie
    //     $semi_rand = md5(time()); 
    //     $mime_boundary = "BOUNDARY_mixed_{$semi_rand}"; 
    //     $alt_mime_boundary = "BOUNDARY_alt_{$semi_rand}"; 

    //     $email_headers  = "From: " . "do_not_reply@danwebco.ca" . "\r\n";
    //     $email_headers .= "Reply-To: " . $_post['value']['eml_contact_primary_email'] . "\r\n";
    //     // $email_headers .= "Cc: " . $_post['value']['eml_contact_primary_email'] . "\r\n";
    //     $email_headers .= "Content-Type: multipart/mixed; boundary=\"{$mime_boundary}\"\r\n";
    //     // $email_headers .= "MIME-Version: 1.0\r\n"; // if I add this header, gmail tag it as spam... no clue how to fix this

    //     $email_message = "\r\n--{$mime_boundary}\r\n";
    //     $email_message .= "Content-Type: multipart/alternative; boundary=\"{$alt_mime_boundary}\"\r\n";
        
    //     $email_message .= "\r\n--{$alt_mime_boundary}\r\n";
    //     $email_message .= "Content-Type: text/plain; charset=UTF-8; format=\"fixed\"\r\n".
    //                       // "Content-Transfer-Encoding: 7bit\r\n".
    //                       "Content-Transfer-Encoding: quoted-printable\r\n".
    //                       "Content-Disposition: inline\r\n".
    //                       $email_plain;

    //     $email_message .= "\r\n--{$alt_mime_boundary}\r\n";
    //     $email_message .= "Content-Type: text/html; charset=UTF-8\r\n".
    //                       // "Content-Transfer-Encoding: 7bit\r\n".
    //                       "Content-Transfer-Encoding: quoted-printable\r\n".
    //                       "Content-Disposition: inline\r\n".
    //                       $email_html;
    //     $email_message .= "\r\n--{$alt_mime_boundary}--\r\n";
        
    //     $email_message .= "\r\n--{$mime_boundary}\r\n";
    //     $email_message .= "Content-Type: text/plain; charset=UTF-8; name=\"" . $attach_name . ".txt\"\r\n".
    //                       "Content-Disposition: attachment; filename=\"" . $attach_name . ".txt\"\r\n".
    //                       "Content-Transfer-Encoding: base64\r\n".
    //                       "\r\n".
    //                       $plain_attachment;

    //     $email_message .= "\r\n--{$mime_boundary}\r\n";
    //     $email_message .= "Content-Type: text/html; charset=UTF-8; name=\"" . $attach_name . ".html\"\r\n".
    //                       "Content-Disposition: attachment; filename=\"" . $attach_name . ".html\"\r\n".
    //                       "Content-Transfer-Encoding: base64\r\n".
    //                       "\r\n".
    //                       $htmltable_attachment;
        
    //     $email_message .= "\r\n--{$mime_boundary}--\r\n";

    //     //send the email
    //     $mail = mail( $_post['value']['eml_contact_primary_email'], $email_subject , $email_message, $email_headers );


    //     if( chmod($attach_dir, 0755) )
    //     {
    //         chmod($attach_dir, 0777);
    //         unlink($file_plain);
    //         unlink($file_htmltable);

    //         if( chmod($attach_dir, 0777) )
    //         {
    //             chmod($attach_dir, 0755);
    //         }
    //     }

    //     // DEBUG Ouput the result of sending the email in the cron notification email
    //     echo $mail ? "\n\nMail sent\n\n" : "\n\nMail failed\n\n";

    // }


    function formatAttachment($arr, $extension) {

        // // DEBUG
        // $output = ['data' => "DEBUG within\n\n" . $txtOut,'modal' => true]; echo(json_encode($output)); exit;

        if ($extension !== "txt" && $extension !== "md")
        {
            return false;
        }

        $spx1 = " ";
        $spx2 = "  ";
        $spx3 = "   ";
        $spx4 = "    ";
        $nlx1 = "  \r\n";
        $nlx2 = $nlx1.$nlx1;
        $nlx3 = $nlx1.$nlx1.$nlx1;
        $nlx4 = $nlx1.$nlx1.$nlx1.$nlx1;
        $ttl = "# ";
        $h1 = "## ";
        $h2 = "### ";
        $h3 = "#### ";
        $bullet = "- ";
        $pad = "    ";
        $spliter = "";

        // Beging of content line
        $cnt = "";

        // // DEBUG
        // $output = ['data' => "DEBUG within\n\n" . $txtOut,'modal' => true]; echo(json_encode($output)); exit;
        
        $txtOut = "";
        if ($extension === 'txt')
        {
            $pad = "    ";
            $spliter = "";
            $mdbr = $nlx1;
            $mdpad = "    ";
        }
        else if ($extension === 'md')
        {
            $pad = "";
            $spliter = "***";
            $mdbr = "<br>" . $nlx1;
            $mdpad = "    ";
        }

        /**********************/

        $txtOut .= $ttl . $arr['fld_project_name'] . $nlx1;
        if (isset($arr['projeckt_ref'])) { $txtOut .= $arr['projeckt_ref'] . $nlx1; }
        $txtOut .= $nlx2;

        


        if ($extension === 'md')
        {
            $txtOut .= $nlx2;
            $txtOut .= $h1 . _( 'Table of Contents' ) . $nlx1;
            $txtOut .= "1. [" . _( 'form-planning-ttl' ) . "](#" . _( 'form-planning-ttl' ) . ")" . $nlx1;
            $txtOut .= "2. [" . _( 'form-design-ttl' ) . "](#" . _( 'form-design-ttl' ) . ")" . $nlx1;
            $txtOut .= "3. [" . _( 'form-technology-ttl' ) . "](#" . _( 'form-technology-ttl' ) . ")" . $nlx1;
            $txtOut .= $nlx2;
        }




        /**********************/

        $txtOut .= $nlx2;
        $txtOut .= $h1 . _( 'form-planning-ttl' ) . $nlx1;
        $txtOut .= $spliter . $nlx1;
        $txtOut .= $nlx1;

        /**********************/

        $txtOut .= $h2 . _('form-planning-contact-ttl') . $nlx1;
        $txtOut .= $nlx1;
        $txtOut .= $h3 . _('form-planning-contact-primary-ttl') . $nlx1;
        $txtOut .= $cnt; if ($arr['fld_contact_primary_fn'] !== "not specified") { $txtOut .= $arr['fld_contact_primary_fn']; } $txtOut .= $spx1; if ($arr['fld_contact_primary_ln'] !== "not specified") { $txtOut .= $arr['fld_contact_primary_ln']; } $txtOut .= $nlx1;
        $txtOut .= $cnt . _("Phone: "); if ($arr['tel_contact_primary_tel'] !== "not specified") {      $txtOut .= $arr['tel_contact_primary_tel']; }   $txtOut .= $nlx1;
        $txtOut .= $cnt . _("Email: "); if ($arr['eml_contact_primary_email'] !== "not specified") {    $txtOut .= $arr['eml_contact_primary_email']; } $txtOut .= $nlx1;
        $txtOut .= $nlx1;

        /**********************/

        $txtOut .= $h3 . _('form-planning-contact-alternate-ttl') . $nlx1;
        $txtOut .= $cnt; if ($arr['fld_contact_alt_fn'] !== "not specified") { $txtOut .= $arr['fld_contact_alt_fn']; } $txtOut .= $spx1; if ($arr['fld_contact_alt_ln'] !== "not specified") { $txtOut .= $arr['fld_contact_alt_ln']; } $txtOut .= $nlx1;
        $txtOut .= $cnt . _("Phone: "); if ($arr['tel_contact_alt_contact_tel'] !== "not specified") {  $txtOut .= $arr['tel_contact_alt_contact_tel']; }   $txtOut .= $nlx1;
        $txtOut .= $cnt . _("Email: "); if ($arr['eml_contact_alt_email'] !== "not specified") {        $txtOut .= $arr['eml_contact_alt_email']; }         $txtOut .= $nlx1;
        $txtOut .= $nlx2;

        /**********************/

        $txtOut .= $h2 . _('form-planning-familiarity-ttl') . $nlx1;
        $txtOut .= $nlx1;
        $txtOut .= $cnt . $arr['opt_familiarity'] . $nlx1;
        $txtOut .= $nlx2;

        /**********************/

        $txtOut .= $h2 . _('form-planning-timeline-ttl') . $nlx1;
        $txtOut .= $nlx1;
        $txtOut .= $cnt . $arr['opt_timeline'] . $nlx1;
        $txtOut .= $nlx2;

        /**********************/

        $txtOut .= $h2 . _('form-planning-budget-ttl') . $nlx1;
        $txtOut .= $nlx1;
        $txtOut .= $cnt . $arr['opt_budget'] . $nlx1;
        $txtOut .= $nlx2;

        /**********************/

        $txtOut .= $h2 . _('form-planning-billing-ttl') . $nlx1;
        $txtOut .= $nlx1;
        $txtOut .= $h3 . _('form-planning-billing-stl-attn') . $nlx1;
        $txtOut .= $cnt; if ($arr['fld_billing_fn'] !== "not specified") {      $txtOut .= $arr['fld_billing_fn']; }     $txtOut .= $spx1; if ($arr['fld_billing_ln'] !== "not specified") { $txtOut .= $arr['fld_billing_ln']; } $txtOut .= $nlx1;
        $txtOut .= $cnt; if ($arr['fld_billing_coname'] !== "not specified") {  $txtOut .= $arr['fld_billing_coname']; } $txtOut .= $nlx1;
        $txtOut .= $cnt; if ($arr['fld_billing_area'] !== "not specified") {    $txtOut .= $arr['fld_billing_area']; }   $txtOut .= $nlx1;
        $txtOut .= $cnt . _("Phone: "); if ($arr['tel_billing_tel'] !== "not specified") {      $txtOut .= $arr['tel_billing_tel']; }   $txtOut .= $nlx1;
        $txtOut .= $cnt . _("Fax: ");   if ($arr['tel_billing_fax'] !== "not specified") {      $txtOut .= $arr['tel_billing_fax']; }   $txtOut .= $nlx1;
        $txtOut .= $cnt . _("Email: "); if ($arr['eml_billing_email'] !== "not specified") {    $txtOut .= $arr['eml_billing_email']; } $txtOut .= $nlx1;
        $txtOut .= $nlx1;

        /**********************/

        $txtOut .= $h3 . _('form-planning-billing-stl-address') . $nlx1;
        $txtOut .= $cnt; if ($arr['fld_billing_address'] !== "not specified") { $txtOut .= $arr['fld_billing_address']; } $txtOut .=  $nlx1;
        if ($arr['fld_billing_address_2'] !== "not specified") { $txtOut .= $cnt . $arr['fld_billing_address_2'] . $nlx1; }
        $txtOut .= $cnt;    if ($arr['fld_billing_city'] !== "not specified") { $txtOut .= $arr['fld_billing_city'] . "," . $spx1; }
                            if ($arr['fld_billing_province'] !== "not specified") { $txtOut .= $arr['fld_billing_province'] . $spx2; }
                            if ($arr['fld_billing_postalcode'] !== "not specified") { $txtOut .= $arr['fld_billing_postalcode']; }
                            $txtOut .= $nlx1;
        $txtOut .= $cnt;    if ($arr['fld_billing_country'] !== "not specified") { $txtOut .= $arr['fld_billing_country']; }
        $txtOut .= $nlx3;

        /**********************/

        $i = 0;
        $txtOut .= $h2 . _('form-content-info-ttl') . $nlx1;
        $txtOut .= $nlx1;
        $txtOut .= $cnt . _('fld_info_legal');      if ($arr['fld_info_legal'] !== "not specified") {   $i++; $txtOut .= $spx1 . $arr['fld_info_legal'] . $nlx1; } else { $txtOut .= $nlx1; }
        $txtOut .= $cnt . _('fld_info_brand');      if ($arr['fld_info_brand'] !== "not specified") {   $i++; $txtOut .= $spx1 . $arr['fld_info_brand'] . $nlx1; } else { $txtOut .= $nlx1; }
        $txtOut .= $cnt . _('fld_info_tagline');    if ($arr['fld_info_tagline'] !== "not specified") { $i++; $txtOut .= $spx1 . $arr['fld_info_tagline'] . $nlx1; } else { $txtOut .= $nlx1; }
        $txtOut .= $cnt . _("Phone:");              if ($arr['tel_info_tel'] !== "not specified") {     $i++; $txtOut .= $spx1 . $arr['tel_info_tel'] . $nlx1; } else { $txtOut .= $nlx1; }
        $txtOut .= $cnt . _("Fax:");                if ($arr['tel_info_fax'] !== "not specified") {     $i++; $txtOut .= $spx1 . $arr['tel_info_fax'] . $nlx1; } else { $txtOut .= $nlx1; }
        $txtOut .= $cnt . _("Email:");              if ($arr['eml_info_email'] !== "not specified") {   $i++; $txtOut .= $spx1 . $arr['eml_info_email'] . $nlx1; } else { $txtOut .= $nlx1; }
        $txtOut .= $nlx1;

        $txtOut .= $h3 . _('form-content-info-adress-stl') . $nlx1;
        if ($arr['fld_info_address'] !== "not specified") {     $i++; $txtOut .= $cnt . $arr['fld_info_address'] . $nlx1; }
        if ($arr['fld_info_address_2'] !== "not specified") {   $i++; $txtOut .= $cnt . $arr['fld_info_address_2'] . $nlx1; }
        
        $j = 0;
        if ($arr['fld_info_city'] !== "not specified") {        $i++; $j++; $txtOut .= $cnt . $arr['fld_info_city'] . "," . $spx1; }
        if ($arr['fld_info_province'] !== "not specified") {    $i++; $j++; $txtOut .= $cnt . $arr['fld_info_province'] . $spx2; }
        if ($arr['fld_info_postalcode'] !== "not specified") {  $i++; $j++; $txtOut .= $cnt . $arr['fld_info_postalcode']; }
        if ($j != 0) { $txtOut .= $nlx1; }
        
        if ($arr['fld_info_postalcode'] !== "not specified") {  $i++; $txtOut .= $cnt . $arr['fld_info_country'] . $nlx1; }
        if ($i == 0) { $txtOut .= $cnt . "not specified" . $nlx1; }
        $txtOut .= $nlx1;

        $txtOut .= $h3 . _('txt_info_description') . $nlx1;
        $txtOut .= $cnt . $arr['txt_info_description'] . $nlx1;
        $txtOut .= $nlx2;

        /**********************/

        $i = 0;
        $txtOut .= $h2 . _( 'form-content-hours-ttl' ) . $nlx1;
        $txtOut .= $nlx1;
        if ($arr['hra_hours_1'] !== "not specified")
        {
            $i++;
            $txtOut .= $cnt . $arr['hra_hours_1'] . $nlx1;
            $txtOut .= $cnt . $arr['hra_hours_1_hours'] . $nlx1;
            $txtOut .= $nlx1;
        }
        if ($arr['hra_hours_2'] !== "not specified")
        {
            $i++;
            $txtOut .= $cnt . $arr['hra_hours_2'] . $nlx1;
            $txtOut .= $cnt . $arr['hra_hours_2_hours'] . $nlx1;
            $txtOut .= $nlx1;
        }
        if ($arr['hra_hours_3'] !== "not specified")
        {
            $i++;
            $txtOut .= $cnt . $arr['hra_hours_3'] . $nlx1;
            $txtOut .= $cnt . $arr['hra_hours_3_hours'] . $nlx1;
            $txtOut .= $nlx1;
        }
        if ($arr['hra_hours_4'] !== "not specified")
        {
            $i++;
            $txtOut .= $cnt . $arr['hra_hours_4'] . $nlx1;
            $txtOut .= $cnt . $arr['hra_hours_4_hours'] . $nlx1;
            $txtOut .= $nlx1;
        }
        if ($arr['hra_hours_5'] !== "not specified")
        {
            $i++;
            $txtOut .= $cnt . $arr['hra_hours_5'] . $nlx1;
            $txtOut .= $cnt . $arr['hra_hours_5_hours'] . $nlx1;
            $txtOut .= $nlx1;
        }
        if ($arr['hra_hours_6'] !== "not specified")
        {
            $i++;
            $txtOut .= $cnt . $arr['hra_hours_6'] . $nlx1;
            $txtOut .= $cnt . $arr['hra_hours_6_hours'] . $nlx1;
            $txtOut .= $nlx1;
        }
        if ($arr['hra_hours_7'] !== "not specified")
        {
            $i++;
            $txtOut .= $cnt . $arr['hra_hours_7'] . $nlx1;
            $txtOut .= $cnt . $arr['hra_hours_7_hours'] . $nlx1;
            $txtOut .= $nlx1;
        }
        if ($i == 0) { $txtOut .= $cnt . "not specified" . $nlx2; }
        $txtOut .= $nlx1;

        /**********************/

        $txtOut .= $h2 . _('form-content-holiday-ttl') . $nlx1;
        $txtOut .= $nlx1;
        $txtOut .= $cnt . $arr['txt_hours_holidays'] . $nlx1;
        $txtOut .= $nlx2;

        /**********************/

        $i = 0;
        $txtOut .= $h2 . _('form-content-product-ttl') . $nlx1;
        $txtOut .= $nlx1;
        if ($arr['fld_product_1'] !== "not specified") { $i++; $txtOut .= $cnt . $bullet . $arr['fld_product_1'] . $nlx1; }
        if ($arr['fld_product_2'] !== "not specified") { $i++; $txtOut .= $cnt . $bullet . $arr['fld_product_2'] . $nlx1; }
        if ($arr['fld_product_3'] !== "not specified") { $i++; $txtOut .= $cnt . $bullet . $arr['fld_product_3'] . $nlx1; }
        if ($i == 0) { $txtOut .= $cnt . $bullet . "not specified" . $nlx1; }
        $txtOut .= $nlx2;

        /**********************/

        $i = 0;
        $txtOut .= $h2 . _('form-content-asset-ttl') . $nlx1;
        $txtOut .= $nlx1;
        if (isset($arr['rdo_existing_asset'])) {    $i++; $txtOut .= $cnt . $arr['rdo_existing_asset'] . $nlx2; } else { $txtOut .= $cnt . "not specified" . $nlx1; }
        if (isset($arr['cbx_asset_logo'])) {        $i++; $txtOut .= $cnt . $bullet . $arr['cbx_asset_logo'] . $nlx1; }
        if (isset($arr['cbx_asset_img'])) {         $i++; $txtOut .= $cnt . $bullet . $arr['cbx_asset_img'] . $nlx1; }
        if (isset($arr['cbx_asset_audio'])) {       $i++; $txtOut .= $cnt . $bullet . $arr['cbx_asset_audio'] . $nlx1; }
        if (isset($arr['cbx_asset_video'])) {       $i++; $txtOut .= $cnt . $bullet . $arr['cbx_asset_video'] . $nlx1; }
        if (isset($arr['cbx_asset_docs'])) {        $i++; $txtOut .= $cnt . $bullet . $arr['cbx_asset_docs'] . $nlx1; }
        if ($arr['txt_asset_othercomments'] !== "not specified")
        {
            $i++;
            $txtOut .= $cnt . $bullet . _( 'txt_asset_othercomments' ) . $nlx1;
            $txtOut .= $cnt . $pad . $arr['txt_asset_othercomments'] . $nlx1;
        }
        if ($i == 0) { $txtOut .= $cnt . "not specified" . $nlx1; }
        $txtOut .= $nlx2;

        /**********************/

        $i = 0;
        $txtOut .= $h2 . _('form-content-content-ttl') . $nlx1;
        $txtOut .= $nlx1;
        if (isset($arr['cbx_content_copywriting'])) {   $i++; $txtOut .= $cnt . $bullet . $arr['cbx_content_copywriting'] . $nlx1; }
        if (isset($arr['cbx_content_graphicdesign'])) { $i++; $txtOut .= $cnt . $bullet . $arr['cbx_content_graphicdesign'] . $nlx1; }
        if (isset($arr['cbx_content_photography'])) {   $i++; $txtOut .= $cnt . $bullet . $arr['cbx_content_photography'] . $nlx1; }
        if (isset($arr['cbx_content_none'])) {          $i++; $txtOut .= $cnt . $bullet . $arr['cbx_content_none'] . $nlx1; }
        if ($arr['txt_content_othercomments'] !== "not specified")
        {
            $i++;
            $txtOut .= $cnt . $bullet . _( 'xxx_content_otherdetails' ) . $nlx1;
            $txtOut .= $cnt . $pad . $arr['txt_content_othercomments'] . $nlx1;
        }
        if ($i == 0) { $txtOut .= $cnt . $bullet . "not specified" . $nlx1; }
        $txtOut .= $nlx2;

        /**********************/

        $i = 0;
        $txtOut .= $h2 . _('form-content-feature-ttl') . $nlx1;
        $txtOut .= $nlx1;
        if (isset($arr['cbx_feature_forum'])) { $i++; $txtOut .= $cnt . $bullet . $arr['cbx_feature_forum'] . ":" . $nlx1; }
        if (isset($arr['cbx_feature_forum']))
        {
            if (isset($arr['txt_feature_forum'])) { $txtOut .= $cnt . $pad . $arr['txt_feature_forum'] . $nlx1; } //else { $txtOut .= $cnt . $pad . "no comment specified" . $nlx1; }  
            $txtOut .= $mdbr;
        }
        if (isset($arr['cbx_feature_login'])) { $i++; $txtOut .= $cnt . $bullet . $arr['cbx_feature_login'] . ":" . $nlx1; }
        if (isset($arr['cbx_feature_login']))
        {
            if (isset($arr['txt_feature_login'])) { $txtOut .= $cnt . $pad . $arr['txt_feature_login'] . $nlx1; } //else { $txtOut .= $cnt . $pad . "no comment specified" . $nlx1; }
            $txtOut .= $mdbr;
        }
        if (isset($arr['cbx_feature_chart'])) { $i++; $txtOut .= $cnt . $bullet . $arr['cbx_feature_chart'] . ":" . $nlx1; }
        if (isset($arr['cbx_feature_chart']))
        {
            if (isset($arr['txt_feature_chart'])) { $txtOut .= $cnt . $pad . $arr['txt_feature_chart'] . $nlx1; } //else { $txtOut .= $cnt . $pad . "no comment specified" . $nlx1; }
            $txtOut .= $mdbr;
        }
        if (isset($arr['cbx_feature_catalog'])) { $i++; $txtOut .= $cnt . $bullet . $arr['cbx_feature_catalog'] . ":" . $nlx1; }
        if (isset($arr['cbx_feature_catalog']))
        {
            if (isset($arr['txt_feature_catalog'])) { $txtOut .= $cnt . $pad . $arr['txt_feature_catalog'] . $nlx1; } //else { $txtOut .= $cnt . $pad . "no comment specified" . $nlx1; }
            $txtOut .= $mdbr;
        }
        if (isset($arr['cbx_feature_comparechart'])) { $i++; $txtOut .= $cnt . $bullet . $arr['cbx_feature_comparechart'] . ":" . $nlx1; }
        if (isset($arr['cbx_feature_comparechart']))
        {                                                                                                               
            if (isset($arr['txt_feature_comparechart'])) { $txtOut .= $cnt . $pad . $arr['txt_feature_comparechart'] . $nlx1; } //else { $txtOut .= $cnt . $pad . "no comment specified" . $nlx1; } 
            $txtOut .= $mdbr;                                                                               
        }                                                                                           
        if (isset($arr['cbx_feature_form'])) { $i++; $txtOut .= $cnt . $bullet . $arr['cbx_feature_form'] . ":" . $nlx1; }
        if (isset($arr['cbx_feature_form']))
        {
            if (isset($arr['txt_feature_form'])) { $txtOut .= $cnt . $pad . $arr['txt_feature_form'] . $nlx1; } //else { $txtOut .= $cnt . $pad . "no comment specified" . $nlx1; }
            $txtOut .= $mdbr;
        }
        if (isset($arr['cbx_feature_advancedform'])) { $i++; $txtOut .= $cnt . $bullet . $arr['cbx_feature_advancedform'] . ":" . $nlx1; }
        if (isset($arr['cbx_feature_advancedform']))
        {
            if (isset($arr['txt_feature_advancedform'])) { $txtOut .= $cnt . $pad . $arr['txt_feature_advancedform'] . $nlx1; } //else { $txtOut .= $cnt . $pad . "no comment specified" . $nlx1; }
            $txtOut .= $mdbr;
        }
        if (isset($arr['cbx_feature_animation'])) { $i++; $txtOut .= $cnt . $bullet . $arr['cbx_feature_animation'] . ":" . $nlx1; }
        if (isset($arr['cbx_feature_animation']))
        {
            if (isset($arr['txt_feature_animation'])) { $txtOut .= $cnt . $pad . $arr['txt_feature_animation'] . $nlx1; } //else { $txtOut .= $cnt . $pad . "no comment specified" . $nlx1; }
            $txtOut .= $mdbr;
        }
        if (isset($arr['cbx_feature_search'])) { $i++; $txtOut .= $cnt . $bullet . $arr['cbx_feature_search'] . ":" . $nlx1; }
        if (isset($arr['cbx_feature_search']))
        {
            if (isset($arr['txt_feature_search'])) { $txtOut .= $cnt . $pad . $arr['txt_feature_search'] . $nlx1; } //else { $txtOut .= $cnt . $pad . "no comment specified" . $nlx1; }
            $txtOut .= $mdbr;
        }
        if (isset($arr['cbx_feature_advancedsearch'])) { $i++; $txtOut .= $cnt . $bullet . $arr['cbx_feature_advancedsearch'] . ":" . $nlx1; }
        if (isset($arr['cbx_feature_advancedsearch']))
        {
            if (isset($arr['txt_feature_advancedsearch'])) { $txtOut .= $cnt . $pad . $arr['txt_feature_advancedsearch'] . $nlx1; } //else { $txtOut .= $cnt . $pad . "no comment specified" . $nlx1; }
            $txtOut .= $mdbr;
        }
        if (isset($arr['cbx_feature_social'])) { $i++; $txtOut .= $cnt . $bullet . $arr['cbx_feature_social'] . ":" . $nlx1; }
        if (isset($arr['cbx_feature_social']))
        {
            if (isset($arr['txt_feature_social'])) { $txtOut .= $cnt . $pad . $arr['txt_feature_social'] . $nlx1; } //else { $txtOut .= $cnt . $pad . "no comment specified" . $nlx1; }
            $txtOut .= $mdbr;
        }
        if (isset($arr['cbx_feature_blog'])) { $i++; $txtOut .= $cnt . $bullet . $arr['cbx_feature_blog'] . ":" . $nlx1; }
        if (isset($arr['cbx_feature_blog']))
        {
            if (isset($arr['txt_feature_blog'])) { $txtOut .= $cnt . $pad . $arr['txt_feature_blog'] . $nlx1; } //else { $txtOut .= $cnt . $pad . "no comment specified" . $nlx1; }
            $txtOut .= $mdbr;
        }
        if (isset($arr['cbx_feature_timeline'])) { $i++; $txtOut .= $cnt . $bullet . $arr['cbx_feature_timeline'] . ":" . $nlx1; }
        if (isset($arr['cbx_feature_timeline']))
        {
            if (isset($arr['txt_feature_timeline'])) { $txtOut .= $cnt . $pad . $arr['txt_feature_timeline'] . $nlx1; } //else { $txtOut .= $cnt . $pad . "no comment specified" . $nlx1; }
            $txtOut .= $mdbr;
        }
        if (isset($arr['cbx_feature_newsletter'])) { $i++; $txtOut .= $cnt . $bullet . $arr['cbx_feature_newsletter'] . ":" . $nlx1; }
        if (isset($arr['cbx_feature_newsletter']))
        {
            if (isset($arr['txt_feature_newsletter'])) { $txtOut .= $cnt . $pad . $arr['txt_feature_newsletter'] . $nlx1; } //else { $txtOut .= $cnt . $pad . "no comment specified" . $nlx1; }
            $txtOut .= $mdbr;
        }
        if (isset($arr['cbx_feature_calculator'])) { $i++; $txtOut .= $cnt . $bullet . $arr['cbx_feature_calculator'] . ":" . $nlx1; }
        if (isset($arr['cbx_feature_calculator']))
        {
            if (isset($arr['txt_feature_calculator'])) { $txtOut .= $cnt . $pad . $arr['txt_feature_calculator'] . $nlx1; } //else { $txtOut .= $cnt . $pad . "no comment specified" . $nlx1; }
            $txtOut .= $mdbr;
        }
        if (isset($arr['cbx_feature_otherdetails'])) { $i++; $txtOut .= $cnt . $bullet . $arr['cbx_feature_otherdetails'] . $nlx1; }
        if (isset($arr['cbx_feature_otherdetails']))
        {
            if (isset($arr['txt_feature_otherdetails'])) { $txtOut .= $cnt . $pad . $arr['txt_feature_otherdetails'] . $nlx1; } //else { $txtOut .= $cnt . $pad . "no comment specified" . $nlx1; }
            $txtOut .= $nlx1;
        }
        if ($i == 0) { $txtOut .= $cnt . $bullet . "not specified" . $nlx1; }
        $txtOut .= $nlx2;




        





        /**********************/

        $txtOut .= $nlx2;
        $txtOut .= $h1 . _( 'form-design-ttl' ) . $nlx1;
        $txtOut .= $spliter . $nlx1;
        $txtOut .= $nlx1;

        /**********************/

        $i = 0;
        $txtOut .= $h2 . _('form-design-siteGoal-ttl') . $nlx1;
        $txtOut .= $nlx1;
        if (isset($arr['txt_site_goal_1'])) { $i++; $txtOut .= $cnt . _( 'txt_site_goal_1' ) . $nlx1; }
        if (isset($arr['txt_site_goal_1']))
        {
            $txtOut .= $cnt . $pad . $arr['txt_site_goal_1'] . $nlx1;
            $txtOut .= $nlx1;
        }
        if (isset($arr['txt_site_goal_2'])) { $i++; $txtOut .= $cnt . _( 'txt_site_goal_2' ) . $nlx1; }
        if (isset($arr['txt_site_goal_2']))
        {
            $txtOut .= $cnt . $pad . $arr['txt_site_goal_2'] . $nlx1;
            $txtOut .= $nlx1;
        }
        if (isset($arr['txt_site_goal_3'])) { $i++; $txtOut .= $cnt . _( 'txt_site_goal_3' ) . $nlx1; }
        if (isset($arr['txt_site_goal_3']))
        {
            $txtOut .= $cnt . $pad . $arr['txt_site_goal_3'] . $nlx1;
            $txtOut .= $nlx1;
        }
        if (isset($arr['txt_site_goal_4'])) { $i++; $txtOut .= $cnt . _( 'txt_site_goal_4' ) . $nlx1; }
        if (isset($arr['txt_site_goal_4']))
        {
            $txtOut .= $cnt . $pad . $arr['txt_site_goal_4'] . $nlx1;
            $txtOut .= $nlx1;
        }
        if ($i == 0) { $txtOut .= $cnt . $bullet . "not specified" . $nlx1; }
        $txtOut .= $nlx2;

        /**********************/

        $i = 0;
        $txtOut .= $h2 . _('form-design-action-ttl') . $nlx1;
        $txtOut .= $nlx1;
        if (isset($arr['cbx_action_call'])) {           $i++; $txtOut .= $cnt . $bullet . $arr['cbx_action_call'] . $nlx1; }
        if (isset($arr['cbx_action_mail'])) {           $i++; $txtOut .= $cnt . $bullet . $arr['cbx_action_mail'] . $nlx1; }
        if (isset($arr['cbx_action_fillform'])) {       $i++; $txtOut .= $cnt . $bullet . $arr['cbx_action_fillform'] . $nlx1; }
        if (isset($arr['cbx_action_socialshare'])) {    $i++; $txtOut .= $cnt . $bullet . $arr['cbx_action_socialshare'] . $nlx1; }
        if (isset($arr['cbx_action_subscribeemail'])) { $i++; $txtOut .= $cnt . $bullet . $arr['cbx_action_subscribeemail'] . $nlx1; }
        if (isset($arr['cbx_action_article'])) {        $i++; $txtOut .= $cnt . $bullet . $arr['cbx_action_article'] . $nlx1; }
        if (isset($arr['cbx_action_searchinfo'])) {     $i++; $txtOut .= $cnt . $bullet . $arr['cbx_action_searchinfo'] . $nlx1; }
        if (isset($arr['cbx_action_purchase'])) {       $i++; $txtOut .= $cnt . $bullet . $arr['cbx_action_purchase'] . $nlx1; }
        if ($arr['txt_action_othercomments'] !== "not specified")
        {
            $i++;
            $txtOut .= $cnt . $bullet . _( 'txt_action_othercomments' ) . $nlx1;
            $txtOut .= $cnt . $pad . $arr['txt_action_othercomments'] . $nlx1;
        }
        if ($i == 0) {    $txtOut .= $cnt . $bullet . "not specified" . $nlx1; }
        $txtOut .= $nlx2;

        /**********************/

        $i = 0;
        $txtOut .= $h2 . _('form-design-adjective-ttl') . $nlx1;
        $txtOut .= $nlx1;
        if ($arr['fld_adjective_1'] !== "not specified") { $i++; $txtOut .= $cnt . $bullet . $arr['fld_adjective_1'] . $nlx1; }
        if ($arr['fld_adjective_2'] !== "not specified") { $i++; $txtOut .= $cnt . $bullet . $arr['fld_adjective_2'] . $nlx1; }
        if ($arr['fld_adjective_3'] !== "not specified") { $i++; $txtOut .= $cnt . $bullet . $arr['fld_adjective_3'] . $nlx1; }
        if ($i == 0) { $txtOut .= $cnt . $bullet . "not specified" . $nlx1; }
        $txtOut .= $nlx2;

        /**********************/

        $txtOut .= $h2 . _('form-design-target-ttl') . $nlx1;
        $txtOut .= $nlx1;
        
        $i = 0;
        $txtOut .= $h3 . _('form-design-target-stl-age') . $nlx1;
        if (isset($arr['cbx_audience_age_kids'])) { $i++; $txtOut .= $cnt . $bullet . $arr['cbx_audience_age_kids'] . $nlx1; }
        if (isset($arr['cbx_audience_age_teen'])) { $i++; $txtOut .= $cnt . $bullet . $arr['cbx_audience_age_teen'] . $nlx1; }
        if (isset($arr['cbx_audience_age_young'])) { $i++; $txtOut .= $cnt . $bullet . $arr['cbx_audience_age_young'] . $nlx1; }
        if (isset($arr['cbx_audience_age_adult'])) { $i++; $txtOut .= $cnt . $bullet . $arr['cbx_audience_age_adult'] . $nlx1; }
        if (isset($arr['cbx_audience_age_senior'])) { $i++; $txtOut .= $cnt . $bullet . $arr['cbx_audience_age_senior'] . $nlx1; }
        if ($i == 0) { $txtOut .= $cnt . $bullet . "not specified" . $nlx1; } else { $txtOut .= $nlx1; }
        
        $i = 0;
        $txtOut .= $h3 . _('form-design-target-stl-geographic') . $nlx1;
        if (isset($arr['cbx_audience_geo_local'])) { $i++; $txtOut .= $cnt . $bullet . $arr['cbx_audience_geo_local'] . $nlx1; }
        if (isset($arr['cbx_audience_geo_city'])) { $i++; $txtOut .= $cnt . $bullet . $arr['cbx_audience_geo_city'] . $nlx1; }
        if (isset($arr['cbx_audience_geo_province'])) { $i++; $txtOut .= $cnt . $bullet . $arr['cbx_audience_geo_province'] . $nlx1; }
        if (isset($arr['cbx_audience_geo_country'])) { $i++; $txtOut .= $cnt . $bullet . $arr['cbx_audience_geo_country'] . $nlx1; }
        if (isset($arr['cbx_audience_geo_world'])) { $i++; $txtOut .= $cnt . $bullet . $arr['cbx_audience_geo_world'] . $nlx1; }
        if ($i == 0) { $txtOut .= $cnt . $bullet . "not specified" . $nlx1; } else { $txtOut .= $nlx1; }
        
        $i = 0;
        $txtOut .= $h3 . _('form-design-target-stl-education') . $nlx1;
        if (isset($arr['cbx_audience_education_hschool'])) { $i++; $txtOut .= $cnt . $bullet . $arr['cbx_audience_education_hschool'] . $nlx1; }
        if (isset($arr['cbx_audience_education_college'])) { $i++; $txtOut .= $cnt . $bullet . $arr['cbx_audience_education_college'] . $nlx1; }
        if (isset($arr['cbx_audience_education_undergrad'])) { $i++; $txtOut .= $cnt . $bullet . $arr['cbx_audience_education_undergrad'] . $nlx1; }
        if (isset($arr['cbx_audience_education_grad'])) { $i++; $txtOut .= $cnt . $bullet . $arr['cbx_audience_education_grad'] . $nlx1; }
        if (isset($arr['cbx_audience_education_none'])) { $i++; $txtOut .= $cnt . $bullet . $arr['cbx_audience_education_none'] . $nlx1; }
        if ($i == 0) { $txtOut .= $cnt . $bullet . "not specified" . $nlx1; } else { $txtOut .= $nlx1; }
        
        $i = 0;
        $txtOut .= $h3 . _('form-design-target-stl-job') . $nlx1;
        if (isset($arr['cbx_audience_job_salaried'])) { $i++; $txtOut .= $cnt . $bullet . $arr['cbx_audience_job_salaried'] . $nlx1; }
        if (isset($arr['cbx_audience_job_self'])) { $i++; $txtOut .= $cnt . $bullet . $arr['cbx_audience_job_self'] . $nlx1; }
        if (isset($arr['cbx_audience_job_professional'])) { $i++; $txtOut .= $cnt . $bullet . $arr['cbx_audience_job_professional'] . $nlx1; }
        if (isset($arr['cbx_audience_job_entrepreneur'])) { $i++; $txtOut .= $cnt . $bullet . $arr['cbx_audience_job_entrepreneur'] . $nlx1; }
        if (isset($arr['cbx_audience_job_unemployed'])) { $i++; $txtOut .= $cnt . $bullet . $arr['cbx_audience_job_unemployed'] . $nlx1; }
        if ($i == 0) { $txtOut .= $cnt . $bullet . "not specified" . $nlx1; } else { $txtOut .= $nlx1; }
        
        $i = 0;
        $txtOut .= $h3 . _('form-design-target-stl-wealth') . $nlx1;
        if (isset($arr['cbx_audience_wealth_below'])) { $i++; $txtOut .= $cnt . $bullet . $arr['cbx_audience_wealth_below'] . $nlx1; }
        if (isset($arr['cbx_audience_wealth_average'])) { $i++; $txtOut .= $cnt . $bullet . $arr['cbx_audience_wealth_average'] . $nlx1; }
        if (isset($arr['cbx_audience_wealth_above'])) { $i++; $txtOut .= $cnt . $bullet . $arr['cbx_audience_wealth_above'] . $nlx1; }
        if ($i == 0) { $txtOut .= $cnt . $bullet . "not specified" . $nlx1; } else { $txtOut .= $nlx1; }
        
        $i = 0;
        $txtOut .= $h3 . _('form-design-target-stl-gender') . $nlx1;
        if (isset($arr['cbx_audience_gender_man'])) { $i++; $txtOut .= $cnt . $bullet . $arr['cbx_audience_gender_man'] . $nlx1; }
        if (isset($arr['cbx_audience_gender_woman'])) { $i++; $txtOut .= $cnt . $bullet . $arr['cbx_audience_gender_woman'] . $nlx1; }
        if ($i == 0) { $txtOut .= $cnt . $bullet . "not specified" . $nlx1; } else { $txtOut .= $nlx1; }

        $txtOut .= $h3 . _('txt_audience_description') . $nlx1;
        $txtOut .= $cnt . $arr['txt_audience_description'] . $nlx1;
        $txtOut .= $nlx2;

        /**********************/

        $i = 0;
        $txtOut .= $h2 . _('form-design-design-ttl') . $nlx1;
        $txtOut .= $nlx1;
        if ($arr['txt_design_colour'] != "not specified") { $i++; $txtOut .= $cnt . _( 'txt_design_colour' ) . $nlx1; }
        if ($arr['txt_design_colour'] != "not specified") { $txtOut .= $cnt . $pad . $arr['txt_design_colour'] . $nlx1 . $mdbr; }

        if ($arr['txt_design_theme'] != "not specified") { $i++; $txtOut .= $cnt . _( 'txt_design_theme' ) . $nlx1; }
        if ($arr['txt_design_theme'] != "not specified") { $txtOut .= $cnt . $pad . $arr['txt_design_theme'] . $nlx1 . $mdbr; }

        if ($arr['txt_design_style'] != "not specified") { $i++; $txtOut .= $cnt . _( 'txt_design_style' ) . $nlx1; }
        if ($arr['txt_design_style'] != "not specified") { $txtOut .= $cnt . $pad . $arr['txt_design_style'] . $nlx1 . $mdbr; }

        if ($arr['txt_design_brand'] != "not specified") { $i++; $txtOut .= $cnt . _( 'txt_design_brand' ) . $nlx1; }
        if ($arr['txt_design_brand'] != "not specified") { $txtOut .= $cnt . $pad . $arr['txt_design_brand'] . $nlx1 . $mdbr; }

        if ($arr['txt_design_othercomments'] != "not specified") { $i++; $txtOut .= $cnt . _( 'txt_design_othercomments' ) . $nlx1; }
        if ($arr['txt_design_othercomments'] != "not specified") { $txtOut .= $cnt . $pad . $arr['txt_design_othercomments'] . $nlx1; }
        if ($i == 0) { $txtOut .= $cnt . $bullet . "not specified" . $nlx1; }
        $txtOut .= $nlx2;


        /**********************/

        $i = 0;
        $txtOut .= $h2 . _('form-design-competitor-ttl') . $nlx1;
        $txtOut .= $nlx1;
        if ($arr['fld_competitor_1'] !== "not specified") { $i++; $txtOut .= $cnt . $bullet . $arr['fld_competitor_1'] . $nlx1; }
        if ($arr['fld_competitor_2'] !== "not specified") { $i++; $txtOut .= $cnt . $bullet . $arr['fld_competitor_2'] . $nlx1; }
        if ($arr['fld_competitor_3'] !== "not specified") { $i++; $txtOut .= $cnt . $bullet . $arr['fld_competitor_3'] . $nlx1; }
        if ($arr['fld_competitor_4'] !== "not specified") { $i++; $txtOut .= $cnt . $bullet . $arr['fld_competitor_4'] . $nlx1; }
        if ($arr['fld_competitor_5'] !== "not specified") { $i++; $txtOut .= $cnt . $bullet . $arr['fld_competitor_5'] . $nlx1; }
        if ($arr['fld_competitor_6'] !== "not specified") { $i++; $txtOut .= $cnt . $bullet . $arr['fld_competitor_6'] . $nlx1; }
        if ($i == 0) { $txtOut .= $cnt . $bullet . "not specified" . $nlx1; }
        $txtOut .= $nlx2;

        /**********************/

        $i = 0;
        $txtOut .= $h2 . _('form-design-competitor-ttl') . $nlx1;
        $txtOut .= $nlx1;

        if ($arr['fld_like_1_url'] !== "not specified")
        {
            $txtOut .= $h3 . _( 'form-design-like-stl-site-1' ) . $nlx1;
            $txtOut .= $cnt . _( 'fld_like_1_url' ) . " ";      if ($arr['fld_like_1_url'] !== "not specified")     { $i++; $txtOut .= $arr['fld_like_1_url']; }      $txtOut .= $nlx1;
            $txtOut .= $cnt . _( 'fld_like_1_like' ) . " ";     if ($arr['fld_like_1_like'] !== "not specified")    { $i++; $txtOut .= $arr['fld_like_1_like']; }     $txtOut .= $nlx1;
            $txtOut .= $cnt . _( 'fld_like_1_improve' ) . " ";  if ($arr['fld_like_1_improve'] !== "not specified") { $i++; $txtOut .= $arr['fld_like_1_improve']; }  $txtOut .= $nlx1;   
            $txtOut .= $nlx1;
        }

        if ($arr['fld_like_2_url'] !== "not specified")
        {
            $txtOut .= $h3 . _( 'form-design-like-stl-site-2' ) . $nlx1;
            $txtOut .= $cnt . _( 'fld_like_2_url' ) . " ";      if ($arr['fld_like_2_url'] !== "not specified")     { $i++; $txtOut .= $arr['fld_like_2_url']; }      $txtOut .= $nlx1;
            $txtOut .= $cnt . _( 'fld_like_2_like' ) . " ";     if ($arr['fld_like_2_like'] !== "not specified")    { $i++; $txtOut .= $arr['fld_like_2_like']; }     $txtOut .= $nlx1;
            $txtOut .= $cnt . _( 'fld_like_2_improve' ) . " ";  if ($arr['fld_like_2_improve'] !== "not specified") { $i++; $txtOut .= $arr['fld_like_2_improve']; }  $txtOut .= $nlx1;   
            $txtOut .= $nlx1;
        }

        if ($arr['fld_like_3_url'] !== "not specified")
        {
            $txtOut .= $h3 . _( 'form-design-like-stl-site-3' ) . $nlx1;
            $txtOut .= $cnt . _( 'fld_like_3_url' ) . " ";      if ($arr['fld_like_3_url'] !== "not specified")     { $i++; $txtOut .= $arr['fld_like_3_url']; }      $txtOut .= $nlx1;
            $txtOut .= $cnt . _( 'fld_like_3_like' ) . " ";     if ($arr['fld_like_3_like'] !== "not specified")    { $i++; $txtOut .= $arr['fld_like_3_like']; }     $txtOut .= $nlx1;
            $txtOut .= $cnt . _( 'fld_like_3_improve' ) . " ";  if ($arr['fld_like_3_improve'] !== "not specified") { $i++; $txtOut .= $arr['fld_like_3_improve']; }  $txtOut .= $nlx1;   
            $txtOut .= $nlx1;
        }

        if ($arr['fld_like_4_url'] !== "not specified")
        {
            $txtOut .= $h3 . _( 'form-design-like-stl-site-4' ) . $nlx1;
            $txtOut .= $cnt . _( 'fld_like_4_url' ) . " ";      if ($arr['fld_like_4_url'] !== "not specified")     { $i++; $txtOut .= $arr['fld_like_4_url']; }      $txtOut .= $nlx1;
            $txtOut .= $cnt . _( 'fld_like_4_like' ) . " ";     if ($arr['fld_like_4_like'] !== "not specified")    { $i++; $txtOut .= $arr['fld_like_4_like']; }     $txtOut .= $nlx1;
            $txtOut .= $cnt . _( 'fld_like_4_improve' ) . " ";  if ($arr['fld_like_4_improve'] !== "not specified") { $i++; $txtOut .= $arr['fld_like_4_improve']; }  $txtOut .= $nlx1;   
            $txtOut .= $nlx1;
        }

        if ($i == 0) { $txtOut .= $cnt . $bullet . "not specified" . $nlx2; }
        $txtOut .= $nlx1;

        /**********************/

        $i = 0;
        $txtOut .= $h2 . _('form-design-dislike-ttl') . $nlx1;
        $txtOut .= $nlx1;

        if ($arr['fld_dislike_1_url'] !== "not specified")
        {
            $txtOut .= $h3 . _( 'form-design-dislike-stl-site-1' ) . $nlx1;
            $txtOut .= $cnt . _( 'fld_dislike_1_url' ) . " ";      if ($arr['fld_dislike_1_url'] !== "not specified")       { $i++; $txtOut .= $arr['fld_dislike_1_url']; }     $txtOut .= $nlx1;
            $txtOut .= $cnt . _( 'fld_dislike_1_dislike' ) . " ";  if ($arr['fld_dislike_1_dislike'] !== "not specified")   { $i++; $txtOut .= $arr['fld_dislike_1_dislike']; } $txtOut .= $nlx1;
            $txtOut .= $nlx1;
        }

        if ($arr['fld_dislike_2_url'] !== "not specified")
        {
            $txtOut .= $h3 . _( 'form-design-dislike-stl-site-2' ) . $nlx1;
            $txtOut .= $cnt . _( 'fld_dislike_2_url' ) . " ";      if ($arr['fld_dislike_2_url'] !== "not specified")       { $i++; $txtOut .= $arr['fld_dislike_2_url']; }     $txtOut .= $nlx1;
            $txtOut .= $cnt . _( 'fld_dislike_2_dislike' ) . " ";  if ($arr['fld_dislike_2_dislike'] !== "not specified")   { $i++; $txtOut .= $arr['fld_dislike_2_dislike']; } $txtOut .= $nlx1;
            $txtOut .= $nlx1;
        }

        if ($arr['fld_dislike_3_url'] !== "not specified")
        {
            $txtOut .= $h3 . _( 'form-design-dislike-stl-site-3' ) . $nlx1;
            $txtOut .= $cnt . _( 'fld_dislike_3_url' ) . " ";      if ($arr['fld_dislike_3_url'] !== "not specified")       { $i++; $txtOut .= $arr['fld_dislike_3_url']; }     $txtOut .= $nlx1;
            $txtOut .= $cnt . _( 'fld_dislike_3_dislike' ) . " ";  if ($arr['fld_dislike_3_dislike'] !== "not specified")   { $i++; $txtOut .= $arr['fld_dislike_3_dislike']; } $txtOut .= $nlx1;
            $txtOut .= $nlx1;
        }

        if ($arr['fld_dislike_4_url'] !== "not specified")
        {
            $txtOut .= $h3 . _( 'form-design-dislike-stl-site-4' ) . $nlx1;
            $txtOut .= $cnt . _( 'fld_dislike_4_url' ) . " ";      if ($arr['fld_dislike_4_url'] !== "not specified")       { $i++; $txtOut .= $arr['fld_dislike_4_url']; }     $txtOut .= $nlx1;
            $txtOut .= $cnt . _( 'fld_dislike_4_dislike' ) . " ";  if ($arr['fld_dislike_4_dislike'] !== "not specified")   { $i++; $txtOut .= $arr['fld_dislike_4_dislike']; } $txtOut .= $nlx1;
            $txtOut .= $nlx1;
        }

        if ($i == 0) { $txtOut .= $cnt . $bullet . "not specified" . $nlx2; }
        $txtOut .= $nlx1;

        /**********************/

        $i = 0;
        $txtOut .= $h2 . _('form-design-remark-ttl') . $nlx1;
        $txtOut .= $nlx1;
        if (isset($arr['rdo_remark'])) { $i++; $txtOut .= $cnt . $arr['rdo_remark'] . $nlx2; } else { $txtOut .= $cnt . "not specified" . $nlx1; }
        if ($arr['txt_definite_no'] !== "not specified")
        {
            $i++;
            $txtOut .= $cnt . $pad . $arr['txt_definite_no'] . $nlx1;
        }
        if ($i == 0) { $txtOut .= $cnt . "not specified" . $nlx1; }
        $txtOut .= $nlx2;




        





        /**********************/

        $txtOut .= $nlx2;
        $txtOut .= $h1 . _( 'form-technology-ttl' ) . $nlx1;
        $txtOut .= $spliter . $nlx1;
        $txtOut .= $nlx1;

        /**********************/

        $i = 0;
        $txtOut .= $h2 . _('form-technology-architecture-ttl') . $nlx1;
        $txtOut .= $nlx1;
        if (isset($arr['rdo_architecture_layout']))         { $i++; $txtOut .= $cnt . $arr['rdo_architecture_layout'] . $nlx2; }
        if (isset($arr['cbx_architecture_hd']))             { $i++; $txtOut .= $cnt . $bullet . $arr['cbx_architecture_hd'] . $nlx1; }
        if (isset($arr['cbx_architecture_legacysupport']))  { $i++; $txtOut .= $cnt . $bullet . $arr['cbx_architecture_legacysupport'] . $nlx1; }
        if ($arr['txt_architecture_othercomments'] !== "not specified")
        {
            $i++;
            $txtOut .= $cnt . $bullet . _( 'txt_architecture_othercomments' ) . $nlx1;
            $txtOut .= $cnt . $pad . $arr['txt_architecture_othercomments'] . $nlx1;
        }
        if ($i == 0) { $txtOut .= $cnt . "not specified" . $nlx1; }
        $txtOut .= $nlx2;

        /**********************/

        $i = 0;
        $txtOut .= $h2 . _('form-technology-accessibility-ttl') . $nlx1;
        $txtOut .= $nlx1;
        if (isset($arr['cbx_accessibility_eyesight']))      { $i++; $txtOut .= $cnt . $bullet . $arr['cbx_accessibility_eyesight'] . $nlx1; }
        if (isset($arr['cbx_accessibility_mobility']))      { $i++; $txtOut .= $cnt . $bullet . $arr['cbx_accessibility_mobility'] . $nlx1; }
        if (isset($arr['cbx_accessibility_readinglevel']))  { $i++; $txtOut .= $cnt . $bullet . $arr['cbx_accessibility_readinglevel'] . $nlx1; }
        if ($arr['txt_accessibility_othercomments'] !== "not specified")
        {
            $i++;
            $txtOut .= $cnt . $bullet . _( 'txt_accessibility_othercomments' ) . $nlx1;
            $txtOut .= $cnt . $pad . $arr['txt_accessibility_othercomments'] . $nlx1;
        }
        if ($i == 0) { $txtOut .= $cnt . "not specified" . $nlx1; }
        $txtOut .= $nlx2;

        /**********************/

        $txtOut .= $h2 . _('form-technology-seo-instruction') . $nlx1;
        $txtOut .= $nlx1;

        $i = 0;
        $txtOut .= $cnt . $bullet . _( 'Webmaster Tool(s):' ) . $nlx1;
            if (isset($arr['cbx_seo_tool_google'])) { $i++; $txtOut .= $cnt . $mdpad . $bullet . $arr['cbx_seo_tool_google'] . $nlx1; }
            if (isset($arr['cbx_seo_tool_bing'])) { $i++; $txtOut .= $cnt . $mdpad . $bullet . $arr['cbx_seo_tool_bing'] . $nlx1; }
            if (isset($arr['cbx_seo_tool_yandex'])) { $i++; $txtOut .= $cnt . $mdpad . $bullet . $arr['cbx_seo_tool_yandex'] . $nlx1; }
            if ($i == 0) { $txtOut .= $cnt . $mdpad . $bullet . "not specified" . $nlx1; }
            $txtOut .= $mdbr;
        $i = 0;
        $txtOut .= $cnt . $bullet . _( 'Open Graph(s):' ) . $nlx1;
            if (isset($arr['cbx_seo_opengraph_fb'])) { $i++; $txtOut .= $cnt . $mdpad . $bullet . $arr['cbx_seo_opengraph_fb'] . $nlx1; }
            if (isset($arr['cbx_seo_opengraph_tw'])) { $i++; $txtOut .= $cnt . $mdpad . $bullet . $arr['cbx_seo_opengraph_tw'] . $nlx1; }
            if (isset($arr['cbx_seo_opengraph_gplus'])) { $i++; $txtOut .= $cnt . $mdpad . $bullet . $arr['cbx_seo_opengraph_gplus'] . $nlx1; }
            if (isset($arr['cbx_seo_opengraph_linkedin'])) { $i++; $txtOut .= $cnt . $mdpad . $bullet . $arr['cbx_seo_opengraph_linkedin'] . $nlx1; }
            if (isset($arr['cbx_seo_opengraph_pinterest'])) { $i++; $txtOut .= $cnt . $mdpad . $bullet . $arr['cbx_seo_opengraph_pinterest'] . $nlx1; }
            if ($i == 0) { $txtOut .= $cnt . $mdpad . $bullet . "not specified" . $nlx1; }
            $txtOut .= $mdbr;
        if (isset($arr['cbx_seo_url_optimization']))      { $i++; $txtOut .= $cnt . $bullet . $arr['cbx_seo_url_optimization'] . $nlx1; }
        if (isset($arr['cbx_seo_structured_data']))      { $i++; $txtOut .= $cnt . $bullet . $arr['cbx_seo_structured_data'] . $nlx1; }
        if (isset($arr['cbx_seo_localization']))  { $i++; $txtOut .= $cnt . $bullet . $arr['cbx_seo_localization'] . $nlx1; }
        if (isset($arr['cbx_seo_mobile_meta']))  { $i++; $txtOut .= $cnt . $bullet . $arr['cbx_seo_mobile_meta'] . $nlx1; }
        if (isset($arr['cbx_seo_analytic']))  { $i++; $txtOut .= $cnt . $bullet . $arr['cbx_seo_analytic'] . $nlx1; }
        $txtOut .= $nlx2;

        /**********************/

        $i = 0;
        $txtOut .= $h2 . _('form-technology-domain-ttl') . $nlx1;
        $txtOut .= $nlx1;
        if ($arr['fld_domain_1'] !== "not specified") { $i++; $txtOut .= $cnt . $bullet . $arr['fld_domain_1'] . $nlx1; }
        if ($arr['fld_domain_2'] !== "not specified") { $i++; $txtOut .= $cnt . $bullet . $arr['fld_domain_2'] . $nlx1; }
        if ($arr['fld_domain_3'] !== "not specified") { $i++; $txtOut .= $cnt . $bullet . $arr['fld_domain_3'] . $nlx1; }
        if ($arr['fld_domain_4'] !== "not specified") { $i++; $txtOut .= $cnt . $bullet . $arr['fld_domain_4'] . $nlx1; }
        if ($arr['fld_domain_5'] !== "not specified") { $i++; $txtOut .= $cnt . $bullet . $arr['fld_domain_5'] . $nlx1; }
        if ($arr['fld_domain_6'] !== "not specified") { $i++; $txtOut .= $cnt . $bullet . $arr['fld_domain_6'] . $nlx1; }
        if ($i == 0) { $txtOut .= $cnt . $bullet . "not specified" . $nlx1; }
        $txtOut .= $nlx2;

        /**********************/

        $i = 0;
        $txtOut .= $h2 . _('form-technology-hosting-ttl') . $nlx1;
        $txtOut .= $nlx1;
        if (isset($arr['rdo_requirehosting'])) { $i++; $txtOut .= $cnt . $arr['rdo_requirehosting'] . $nlx2; }
        if ($i == 0) { $txtOut .= $cnt . "not specified" . $nlx1; }
        $txtOut .= $nlx2;

        /**********************/

        $i = 0;
        $txtOut .= $h2 . _('form-technology-email-ttl') . $nlx1;
        $txtOut .= $nlx1;
        if (isset($arr['rdo_domain_mailmatch'])) { $i++; $txtOut .= $cnt . $arr['rdo_domain_mailmatch'] . $nlx2; }
        if ($i == 0) { $txtOut .= $cnt . "not specified" . $nlx1; }
        $txtOut .= $nlx2;

        /**********************/

        $txtOut .= $h2 . _('form-technology-maintenance-ttl') . $nlx1;
        $txtOut .= $nlx1;
        $txtOut .= $cnt . $arr['txt_maintenance_details'] . $nlx1;
        $txtOut .= $nlx2;










        /**********************/

        $txtOut .= $nlx2;
        $txtOut .= $h1 . _( 'form-other-ttl' ) . $nlx1;
        $txtOut .= $spliter . $nlx1;
        $txtOut .= $nlx1;

        /**********************/

        $txtOut .= $h2 . _('form-other-future') . $nlx1;
        $txtOut .= $nlx1;
        $txtOut .= $cnt . $arr['txt_future_comments'] . $nlx1;
        $txtOut .= $nlx2;

        /**********************/

        $txtOut .= $h2 . _('form-other-comment') . $nlx1;
        $txtOut .= $nlx1;
        $txtOut .= $cnt . $arr['txt_additional_comments'] . $nlx1;
        $txtOut .= $nlx2;

        /**********************/

        $txtOut .= $nlx2;

        if ($extension === 'txt')
        {
                $txtOut .= "Form generated by Projecklist. Copyright " . date('Y') . ", All rights reserved." . $nlx1;
                $txtOut .= $_SESSION["server_root"];
        }
        else if ($extension === 'md')
        { 
            $txtOut .= $mdbr . $mdbr . $mdbr . $mdbr;
            $txtOut .= "Form generated by [Projecklist](" . $_SESSION["server_root"] . "), Copyright " . date('Y') . ", All rights reserved.";
        }


        // // DEBUG
        // $output = ['data' => "DEBUG within\n\n" . $txtOut,'modal' => true]; echo(json_encode($output)); exit;

        return $txtOut;
    }
<?php 


include 'functions.php';
if(SendEmailFile("emuroiwa@gmail.com","dsfsdfdfsf")){
echo "sdfds";}else{
	echo "adfd";
	}
function mail_file( $to, $subject, $messagehtml, $from, $fileatt, $replyto="" ) {
        // handles mime type for better receiving
        $ext = strrchr( $fileatt , '.');
        $ftype = "";
        if ($ext == ".doc") $ftype = "application/msword";
        if ($ext == ".jpg") $ftype = "image/jpeg";
        if ($ext == ".gif") $ftype = "image/gif";
        if ($ext == ".zip") $ftype = "application/zip";
        if ($ext == ".pdf") $ftype = "application/pdf";
        if ($ftype=="") $ftype = "application/octet-stream";
         
        // read file into $data var
        $file = fopen($fileatt, "rb");
        $data = fread($file,  filesize( $fileatt ) );
        fclose($file);
 
        // split the file into chunks for attaching
        $content = chunk_split(base64_encode($data));
        $uid = md5(uniqid(time()));
 
        // build the headers for attachment and html
        $h = "From: $from";
        if ($replyto) $h .= "Reply-To: ".$replyto;
        $h .= "MIME-Version: 1.0";
        $h .= "Content-Type: multipart/mixed; boundary=\"".$uid;
        $h .= "This is a multi-part message in MIME format";
        $h .= "--".$uid;
        $h .= "Content-type:text/html; charset=iso-8859-1";
        $h .= "Content-Transfer-Encoding: 7bit";
        $h .= $messagehtml;
        $h .= "--".$uid;
        $h .= "Content-Type: ".$ftype."; name=\"".basename($fileatt);
        $h .= "Content-Transfer-Encoding: base64";
        $h .= "Content-Disposition: attachment; filename=\"".basename($fileatt);
        $h .= $content;
        $h .= "--".$uid."--";
 
        // send mail
       

        if(mail( $to, $subject, strip_tags($messagehtml),$h)){
         return  "succes";	
        }else
        {
                 return  "fail";	
	
        }
 
 
    }
//echo mail_file("emuroiwa@gmail.com","dsffd","sadfa","ernestmuroiwa@gmail.com","book.pdf");
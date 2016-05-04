<?php
$message = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">  
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Email Verification </title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link href="https://fonts.googleapis.com/css?family=Josefin+Sans:400,600,300italic,700,700italic" rel="stylesheet" type="text/css">
  </head>
  <body style="margin: 0; padding: 0; font-family: sans-serif;">
   <table align="center"  cellpadding="0" border="0" cellspacing="0" width="600" style="border-collapse: collapse; display: block; border:0; background:#fff; ">
    <tbody>
    <tr style="display: block; ">
      <td style="padding:0px; width: 150px; background-color: #444;" >
        <a href="javascript:void(0);" style="display:block; height:63px; "><img src="'.$base_path.'emailimage/logo.png" alt="" style="    width: 50px;
    margin: 8px 20px;"></a>
      </td>
      <td style="padding: 5px 10px; width:450px; background-color:#444;color: #fff;font-size: 24px; text-transform: uppercase; text-align:right;">
        <span style="float:right;">+91 99999 99999</span>
        <img src="'.$base_path.'emailimage/callIco-head.png" alt="call" width="25" style="float:right; margin:0 10px;"> 
      </td>
    </tr>
    <tr style=" width: 600px; display: block;">
     <td style="display: block; padding: 10px;">
      <p style="font-size:13px;">
            <strong>Hi '.$name.',</strong> <br> <br> 
     Thank you for your order! . Your order ID is '.$order_number.'.
    <br> <br> 
    We will send you another email once the items in your order have been shipped.
      </p>
     </td>          
   </tr>
      ' . $product . '
     <tr style=" width: 600px; display: block;  margin: 20px 0px 0px;  background: #F3F3F3; padding: 10px 0;">
    <td style="  width: 300px; " >
      '.$address.'
     </td>  
                
   </tr>
    <tr style="display: block; margin-top:0px;background: #444; padding: 15px 0;">
      <td colspan="2" style="width: 600px;">
        <ul style="display:block; width:100%; list-style-type:none; overflow: hidden;margin: 0;padding: 10px 0;">
          <li style="display:block; width:200px; float:left; text-align:center;">
            <a href="#!" style="display:block;color:#a9a9a9; text-transform:uppercase;text-decoration:none; font-size:14px; border-right:1px solid #676767;">Visit Website</a>
          </li>
          <li style="display:block; width:200px; float:left; text-align:center;">
            <a href="#!" style="display:block;color:#a9a9a9; text-transform:uppercase;text-decoration:none; font-size:14px;">Terms &amp; Conditions</a>
          </li>
          <li style="display:block; width:200px; float:left; text-align:center;">
            <a href="#!" style="display:block;color:#a9a9a9; text-transform:uppercase;text-decoration:none; font-size:14px; border-left:1px solid #676767;">Privacy Policy</a>
          </li>
        </ul>
      </td> 
    </tr>
  </tbody></table>
  </body>
</html>';
echo $message ;
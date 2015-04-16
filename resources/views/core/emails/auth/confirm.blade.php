<!DOCTYPE html>
<html lang="en">
	<head>
		<style>
			@import url(http://fonts.googleapis.com/css?family=Source+Sans+Pro:200,300,400,600,700|PT+Sans:400,700|Roboto:400,100,300,500,700);
		</style>
	</head>
	
	<body style="font-family: 'Roboto'; font-weight: 400; background-color: #EEE;">
<div marginwidth="0" marginheight="0" bgcolor="#f5f5f5">

<center>
    
    <table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" bgcolor="#f5f5f5" style="font-family:'Roboto','PT Sans','Source Sans Pro',Helvetica,Arial,'Lucida Grande',sans-serif;font-weight:300;font-size:13px">
        <tbody><tr>
            <td align="center" valign="top">


                
                <table border="0" cellpadding="0" cellspacing="0" width="600">
                    <tbody><tr height="115" style="border: 0.75px solid #27ae60; border-top-right-radius: 0.35em; border-top-left-radius: 0.35em;">

                        
                        <td valign="middle">
                            <div>
                                <a href="http://minerzone.net" style="color:#90b42b;text-decoration:none !important;" target="_blank">
                                    <h1 style="font-size: 39px;text-decoration:none !important; font-family: 'PT Sans' !important; font-weight: 700 !important;">minerzone</h1>
                                </a>
                            </div>
                        </td>
                        
                        <td valign="middle" align="right" width="285" style="font-size:16px;color:#b5b5b5">
                            Welcome to MinerZone!
                        </td>

                    </tr>
                </tbody></table>
                

                
                <table border="0" cellpadding="0" cellspacing="0" width="600" bgcolor="#ffffff">
                    <tbody><tr>
                        <td style="height:5px;background-color:#90b42b"></td>
                    </tr>
                    <tr>
                        <td align="center" valign="top" style="border-left:1px solid #e1e1e1;border-right:1px solid #e1e1e1;border-bottom:1px solid #e1e1e1">

   
       <table border="0" cellpadding="40" cellspacing="0" width="100%">
           <tbody><tr align="center">
               <td valign="top" align="center">

                   <h2 style="font-size:28px;letter-spacing:-0.03em;font-weight:300;color:#333333;margin-top:0px;margin-bottom:15px">Dear {{{ $user->name }}},</h2>

                   <div style="color:#999999;font-size:13px;line-height:25px">
                     Welcome to MinerZone!<br>
                     Someone registered the username <strong>{{{ $user->name }}}</strong> at <a href="{{{ config('app.url') }}}" target="_blank" style="text-decoration: none !important;">minerzone.net</a> using this email <strong><a href="mailto:{{{ $user->email }}}" target="_blank">{{{ $user->email }}}</a></strong>.<br>
                     If this was you, please finish the registration process by confirming your email bellow.
                   </div>
                   <br> <br>
                   <div>
                       <a href="{{{ URL::to('/account/confirm/' . $confirmation->code) }}}" style="color:white; padding: 18px 30px; font-size: 19px; line-height: 1.3333333; background-color: #2ecc71; border-radius: 4.5px; border-color: #2ecc71; text-decoration: none !important;" target="_blank">Confirm account</a>
                   </div>
                   <br> <br>

                   <div style="color:#999999;font-size:13px;line-height:25px">
                       You can also copy and paste this link into your browser:
                       <a href="{{{ URL::to('/account/confirm/' . $confirmation->code) }}}" target="_blank" style="text-decoration: none !important;">{{{ URL::to('/account/confirm/' . $confirmation->code) }}}</a><br>
                       If you did not register for MinerZone, please delete this email.
                   </div>
               </td>
           </tr>
       </tbody></table>
	</body>
</html>
<!DOCTYPE html>
<html lang="en">
    <head></head>
    <body>
        Hey, {{{ $user->name }}}!
        <br />
        Someone (we hope it's you!) has signed up to the forum '{{{ $siteName }}}' with this email address.
        <br />
        If this was you, simply visit the following link and your account will be activated:
        <br />
        <a href="{{{ URL::to('/account/confirm/' . $confirmation->code) }}}">{{{ URL::to('/account/confirm/' . $confirmation->code) }}}</a>
        <br />
        Otherwise, you can ignore this email.
    </body>
</html>
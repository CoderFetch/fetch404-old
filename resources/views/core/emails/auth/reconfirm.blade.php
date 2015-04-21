<!DOCTYPE html>
<html lang="en">
    <head></head>
    <body>
        Hey, {{{ $user->name }}}!
        <br />
        Please confirm this email address for your account.
        <br />
        Until you re-confirm your account, you will be unable to post on our forums.
        <br />
        Please click the following link:
        <br />
        <a href="{{{ URL::to('/account/confirm/' . $confirmation->code) }}}">{{{ URL::to('/account/confirm/' . $confirmation->code) }}}</a>
        <br />

        If you did not request this email change, you can ignore this email.
    </body>
</html>
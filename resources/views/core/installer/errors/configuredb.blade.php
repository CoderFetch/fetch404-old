<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Fetch404 Installer | Error</title>
        <meta charset="UTF-8" />

        {!! HTML::style('assets/css/cosmo.css') !!}
        {!! HTML::script('assets/js/jquery-1.11.2.min.js') !!}
        {!! HTML::script('assets/js/bootstrap.min.js') !!}

        <style>
            .alert {
                border-radius: 4px !important;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="well">
                <h2>Error</h2>
                <p>To install Fetch404, you must configure your database.</p>
                <div class="alert alert-danger">
                    <strong>
                        Please ensure that your database credentials are correct!
                    </strong>
                    <br>
                    <span class="help-block" style="color: lightgray;">
                        * To configure your database, follow these steps:
                        <ul>
                            <li>
                                1. Find the .env file (if there is none, create .env then copy the contents of .env.example)
                            </li>
                            <li>
                                2. Make sure that you have created your database and granted permissions to the user you plan on using.
                            </li>
                            <li>
                                3. Open your .env file. You should see 4 keys: <strong>DB_HOST, DB_DATABASE, DB_USERNAME, DB_PASSWORD</strong>.
                                Follow these steps:
                                <ul>
                                    <li>
                                        3a. Change DB_HOST to your database host
                                    </li>
                                    <li>
                                        3b. Change DB_DATABASE to the name of your database
                                    </li>
                                    <li>
                                        3c. Change DB_USERNAME to your database username
                                    </li>
                                    <li>
                                        3d. Change DB_PASSWORD to your database's password.
                                    </li>
                                </ul>
                            </li>
                            <li>
                                4. Once you have configured your .env file, click the "Retry" button and start the installation process.
                                <br>
                                <small>
                                    If it still doesn't work, make sure you followed ALL of the steps.
                                </small>
                            </li>
                        </ul>
                    </span>
                </div>
                <center>
                    <button class="btn btn-primary" onclick="location.reload()">Retry &raquo;</button>
                </center>
            </div>
        </div>
    </body>
</html>
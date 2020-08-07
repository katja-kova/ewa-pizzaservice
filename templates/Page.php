<?php	// UTF-8 marker äöüÄÖÜß€

abstract class Page
{

    protected $_database = null;
    

    protected function __construct() 
    {
        error_reporting (E_ALL);

        $username = "root";
        $password = ""; // ROOT PASSWORD
        $this->_database = new MySQLi("mariadb", $username, $password, "pizzaservice_2020");
        
        if (mysqli_connect_errno())
            throw new Exception("Connect failed: " . mysqli_connect_error());
        
        // set charset to UTF8!!
        if (!$this->_database->set_charset("utf8"))
          throw new Exception($this->_database->error);

    }
    

    public function __destruct()    
    {
        // to do: close database
    }

    protected function generatePageHeader($headline = "", $refresh=true) 
    {
        $headline = htmlspecialchars($headline);
        header("Content-type: text/html; charset=UTF-8");
        header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
        header("Expires: Sat, 01 Jul 2000 06:00:00 GMT"); // vergangenes Datum
        header("Cache-Control: post-check=0, pre-check=0", false); // fuer IE
        header("Pragma: no-cache");
                
        echo <<<EOF
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <title>{$headline}</title>
            <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
            <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
            <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
            <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
            <script src='./scripts/app.js'></script>
            <link rel="stylesheet" href="http://localhost/Praktikum/Prak5/styles.css">
EOF;
        if ($refresh) 
            echo '<meta http-equiv="refresh" content="5">';

        echo <<< EOF
        </head>
EOF;
    }


    protected function generatePageFooter() 
    {
        
        echo <<<EOF
        <footer></footer>
        </body>
        </html>
EOF;
    }

    protected function processReceivedData() 
    {

    }

}
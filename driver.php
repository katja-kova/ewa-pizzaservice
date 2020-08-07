<?php	// UTF-8 marker äöüÄÖÜß€

require_once './templates/Page.php';
require_once './blocks/menu.php';
require_once './blocks/cart.php';

class Driver extends Page
{

    protected $orders = null;

    protected function __construct()
    {
        parent::__construct();
        $this->orders = [];
    }


    public function __destruct()
    {
        parent::__destruct();
    }

    protected function getViewData()
    {
        $i=0;
        if ($this->_database->connect_errno) {
            throw new Exception("MySQL ErrorCode: " . $this->_database->connect_errno);
            return false;
        }
        try {
            $sql = "SELECT GROUP_CONCAT(ordered_articles.status SEPARATOR ', '), ordered_articles.status, ordering.id, ordering.address, GROUP_CONCAT(article.name SEPARATOR ', '), Round(Sum(article.price),2)
                    FROM ordering
                    INNER JOIN ordered_articles ON ordered_articles.f_order_id = ordering.id
                    INNER JOIN article ON article.id = ordered_articles.f_article_id
                  /* WHERE ordered_articles.status >= 2 AND ordered_articles.status < 4 */
                    GROUP BY ordering.id";

            $Recordset = $this->_database->query($sql);

            $Recordset = $this->_database->query($sql) or die($this->_database->error);
            
            while ($record = $Recordset->fetch_assoc()){

                $stati = $record["GROUP_CONCAT(ordered_articles.status SEPARATOR ', ')"];
                $order_ready = strpos($stati, '1');
                $order_delivered = strpos($stati, '4');

                if($order_ready===false && $order_delivered===false){
                    $orders[] = $record;
                    $i+=1;
                }
            }
            $Recordset->free();
        } catch (Exception $e) {
            echo $e->getMessage();
        }


        if($i==0){
            return 0;
        }else{
            return $orders;
        }
    }


    protected function generateView()
    {
        $orders = $this->getViewData();

        $this->generatePageHeader('Driver', true);

        if($orders == 0){
            echo <<<EOF
            <body>
                <header class="py-1 flex align-items-center text-right" id="p-header">
                    <a class="col-auto" href="index.php">
                        <img src="./assets/logo.PNG" class="img-responsive" id="p-logo">
                    </a>
                </header>
                <div id="p-driver" class="jumbotron">
                    <div class="container">
                        <div class="row">
                            <div class="col-sm-9">
                                <h1 class="display-5">No orders yet</h1>
                            </div>
                        </div>
                    </div>
                </div>
            </body>
EOF;
        } else{

            echo <<<EOF
            <body>
                <header class="py-1 flex align-items-center text-right" id="p-header">
                    <a class="col-auto" href="index.php">
                        <img src="./assets/logo.PNG" class="img-responsive" id="p-logo">
                    </a>
                </header>

            <div id='p-driver' class="jumbotron">
                <div class="container">
                    <h1 class="display-5"> Driver </h1>
                        </br>
                        <form action="driver.php" method="POST">
                            <div class="col-sm-11">
EOF;
            foreach($orders as $order){
                $address = htmlspecialchars($order['address']);
                $id = htmlspecialchars($order['id']);
                $status =htmlspecialchars($order['status']);
                $pizzas = htmlspecialchars($order["GROUP_CONCAT(article.name SEPARATOR ', ')"]);
                $price = htmlspecialchars($order['Round(Sum(article.price),2)']);

            echo <<<EOF
                <h2 id='order-title' class='display-6'>Order #$id </h2>
                <span id='addr-title'>$address , $price €</span>

                <ul class="list-group">
                    <li class="list-group-item">
                        <p id="ordered-pizzas">$pizzas</p>
                        <div class="form" id="status-form">
                            <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
EOF;
                    if($status == 2){
                        echo <<<EOF
                        <span class='span'>
                            <span class="radio-button">
                                <input type='radio' name='pizza[$id]' value='2' id='ordered' onchange="this.form.submit()" checked>
                            </span>
                            <span class="radio-text"> ready </span>
                        </span>
                        <span class='span'>
                            <span class="radio-button">
                                <input type='radio' name='pizza[$id]' value='3' id='preparing' onchange="this.form.submit()">
                            </span>     
                            <span class="radio-text"> on the way </span>
                        </span>
                        <span class='span'>
                            <span class="radio-button">
                                <input type='radio' name='pizza[$id]' value='4' id='ready' onchange="this.form.submit()">
                            </span> 
                            <span class="radio-text"> delivered </span>
                        </span>
EOF;                    
                    } else if($status == 3){
                        echo <<<EOF
                        <span class='span'>
                            <span class="radio-button">
                                <input type='radio' name='pizza[$id]' value='2' id='ordered' onchange="this.form.submit()"> 
                            </span>    
                            <span class="radio-text"> ready </span>
                        </span>
                        <span class='span'>
                            <span class="radio-button">
                                <input type='radio' name='pizza[$id]' value='3' id='preparing' onchange="this.form.submit()" checked> 
                            </span>    
                            <span class="radio-text"> on the way </span>
                        </span>
                        <span class='span'>
                            <span class="radio-button">
                                <input type='radio' name='pizza[$id]' value='4' id='ready' onchange="this.form.submit()">
                            </span> 
                            <span class="radio-text"> delivered </span>
                        </span>
EOF;                    
                    } else {
                        echo <<<EOF
                        <span class='span'>
                            <span class="radio-button">
                                <input type='radio' name='pizza[$id]' value='2' id='ordered' onchange="this.form.submit()">
                            </span> 
                            <span class="radio-text"> ready </span>
                        </span>
                        <span class='span'>
                            <span class="radio-button">
                                <input type='radio' name='pizza[$id]' value='3' id='preparing' onchange="this.form.submit()"> 
                            </span>    
                            <span class="radio-text"> on the way </span>
                        </span>
                        <span class='span'>
                            <span class="radio-button">
                                <input type='radio' name='pizza[$id]' value='4' id='ready' onchange="this.form.submit()" checked> 
                            </span>
                            <span class="radio-text"> delivered </span>
                        </span>
EOF;                    
                    } 
                    echo <<<EOF
                    </div> 
                    </div>  
                    </div>
                    </div>                
                    </li>
                    </ul>
EOF;
            }
            echo <<<EOF
            </div>
            </form>
            </div>
            </br>
            </br>
            </div>
            </div>
            </body>
EOF;

            $this->generatePageFooter();
        }
    }

    protected function processReceivedData()
    {
        parent::processReceivedData();
        
        if(isset($_POST['pizza'])){

            $orders = $_POST['pizza'];            

            foreach ($orders as $id => $status) {       

                $status = $this->_database->real_escape_string($status);
                $id = $this->_database->real_escape_string($id);

                try {
                    $sql = "UPDATE ordered_articles SET ordered_articles.status=$status WHERE ordered_articles.f_order_id=$id";
                    $Recordset = $this->_database->query($sql);
                    
                } catch (Exception $e) {
                    echo $e->getMessage();
                }
            } 
        }
    }

    public static function main()
    {
        try {
            $page = new Driver();
            $page->processReceivedData();
            $page->generateView();
        }
        catch (Exception $e) {
            header("Content-type: text/plain; charset=UTF-8");
            echo $e->getMessage();
        }
    }
}

Driver::main();

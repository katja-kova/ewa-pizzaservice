<?php	// UTF-8 marker äöüÄÖÜß€

require_once './templates/Page.php';

class Baker extends Page
{

    protected $pizzas = null;

    protected function __construct()
    {
        parent::__construct();
        $this->pizzas = [];
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
            $sql = "SELECT ordered_articles.id, ordered_articles.f_order_id, ordered_articles.status, article.name
                    FROM ordered_articles, article
                    WHERE ordered_articles.f_article_id = article.id AND ordered_articles.status BETWEEN 0 AND 2";

            $Recordset = $this->_database->query($sql);

            while ($record = $Recordset->fetch_assoc()){
                $pizzas[] = $record;
                $i+=1;
            }

            $Recordset->free();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
        if($i==0){
            return 0;
        } else{
            return $pizzas;
        }
    }

    protected function generateView()
    {
        $pizzas = $this->getViewData();
        $this->generatePageHeader('Baker', true);
        if($pizzas == 0){
            echo <<<EOF
            <body>
                <header class="py-1 flex align-items-center text-right" id="p-header">
                    <a class="col-auto" href="index.php">
                        <img src="./assets/logo.PNG" class="img-responsive" id="p-logo">
                    </a>
                </header>
                <div id="p-baker" class="jumbotron">
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
        } else {

            $i = 1;

            echo <<<EOF
            <body>
                <header class="py-1 flex align-items-center text-right" id="p-header">
                    <a class="col-auto" href="index.php">
                        <img src="./assets/logo.PNG" class="img-responsive" id="p-logo">
                    </a>
                </header>

                <div id="p-baker" class="jumbotron">
                    <div class="container">
                        <h1 class="display-5"> Baker </h1>
                        </br>

                        <form action="baker.php" method="POST">
                            <div class="col-sm-11">
EOF;

            $tmp = 0;
            foreach($pizzas as $pizza){

                $name = htmlspecialchars($pizza['name']);
                $id = htmlspecialchars($pizza['id']);
                $status = htmlspecialchars($pizza['status']);
                $order_id = htmlspecialchars($pizza['f_order_id']);
                $status = number_format($status);
                
                if($id!=$tmp){
                    echo "<h2 id='order-title' class='display-6'>Order #$order_id</h2>";
                    $tmp = $order_id;
                }
                
                echo <<<EOF
                <ul class="list-group">
                    <li class="list-group-item">
                        <h5>$name</h5>
                        <div class="form" id="status-form">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
EOF;
                if($status == 0){
                    echo <<<EOF
                    <span class='span'><input type='radio' name='pizza[$id]' value='0' id='ordered' checked onchange="this.form.submit()"> ordered </span>
                    <span class='span'><input type='radio' name='pizza[$id]' value='1' id='preparing' onchange="this.form.submit()"> preparing </span>
                    <span class='span'><input type='radio' name='pizza[$id]' value='2' id='ready' onchange="this.form.submit()"> ready </span>
EOF;                
                } else if($status == 1){
                    echo <<<EOF
                    <span class='span'><input type='radio' name='pizza[$id]' value='0' id='ordered' onchange="this.form.submit()"> ordered </span>
                    <span class='span'><input type='radio' name='pizza[$id]' value='1' id='preparing' checked onchange="this.form.submit()"> preparing </span>
                    <span class='span'><input type='radio' name='pizza[$id]' value='2' id='ready' onchange="this.form.submit()"> ready </span>
EOF;                
                } else {
                    echo <<<EOF
                    <span class='span'><input type='radio' name='pizza[$id]' value='0' id='ordered' onchange="this.form.submit()"> ordered </span>
                    <span class='span'><input type='radio' name='pizza[$id]' value='1' id='preparing' onchange="this.form.submit()"> preparing </span>
                    <span class='span'><input type='radio' name='pizza[$id]' value='2' id='ready' checked onchange="this.form.submit()"> ready </span>
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
                $i+=1;
            
        }

            echo <<<EOF
            </div>
            </form>
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
                    $sql = "UPDATE ordered_articles SET ordered_articles.status=$status WHERE ordered_articles.id=$id";
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
            $page = new Baker();
            $page->generateView();
            $page->processReceivedData();
        }
        catch (Exception $e) {
            header("Content-type: text/plain; charset=UTF-8");
            echo $e->getMessage();
        }
    }
}

Baker::main();

<?php	// UTF-8 marker äöüÄÖÜß€

require_once './templates/Page.php';
require_once './blocks/menu.php';
require_once './blocks/cart.php';

class Order extends Page
{
    private $_menu;
    private $_cart;
    protected $_database = null;

    protected function __construct()
    {
        parent::__construct();


        // Initialize members
        $this->_menu = new Menu($this->_database);
        $this->_cart = new Cart($this->_database);
        session_cache_limiter('nocache'); // VOR session_start()!
        session_cache_expire(0);
        session_start();

    }

    public function __destruct()
    {
        parent::__destruct();
    }

    protected function getViewData()
    {
        if ($this->_database->connect_errno) {
            throw new Exception("MySQL ErrorCode: " . $this->_database->connect_errno);
            return false;
        }
        try {
            $sql = "SELECT * FROM article ORDER BY price";
            $Recordset = $this->_database->query($sql);

            while ($record = $Recordset->fetch_assoc()){
                $pizzas[] = $record;
            }

            $Recordset->free();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
        return $pizzas;
    }

    protected function generateView()
    {

        $pizzas = $this->getViewData();
        $this->generatePageHeader('Order', false);

        $this->_menu->generateView('menu', $pizzas);
        $this->_cart->generateView('cart');

        $this->generatePageFooter();
    }

    protected function processReceivedData()
    {
        parent::processReceivedData();
        if(isset($_POST["orders"]) && isset($_POST["address"])){
            $address = $_POST["address"];

            $address = $this->_database->real_escape_string($address);

            try {
                $sql = "INSERT INTO ordering(address, timestamp) VALUES ('$address', now())";
                $Recordset = $this->_database->query($sql);
                $order_id = $this->_database->insert_id;
                $_SESSION['order_id'] = $order_id ;
            } catch (Exception $e) {
                echo $e->getMessage();
            } 

            $ordered_pizzas = $_POST["orders"];
            foreach($ordered_pizzas as $tmp){
                $data = json_decode($tmp, true);
                $id = $data['id'];
                $count = $data['count'];
                $f_order_id = $_SESSION['order_id'];

                for ($x = 1; $x <= $count; $x++) {
                    try {
                        $sql = "INSERT INTO ordered_articles(f_article_id, f_order_id, status)
                            VALUES ((SELECT article.id FROM article WHERE article.id='$id'), '$f_order_id', '0')";
                        $this->_database->query($sql);
                        
                    } catch (Exception $e) {
                        echo $e->getMessage();
                    }
                }
            } 

           header('Location: ./customer.php');

        }
        
    }

    public static function main()
    {
        try {
            $page = new Order();
            $page->processReceivedData();
            $page->generateView();

        }
        catch (Exception $e) {
            header("Content-type: text/plain; charset=UTF-8");
            echo $e->getMessage();
        }
    }
}

Order::main();

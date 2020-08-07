<?php	// UTF-8 marker äöüÄÖÜß€

require_once '../templates/Page.php';

class Status extends Page
{

    protected $pizzas = null;
    protected $_database = null;

    public function __construct() 
    {
        parent::__construct();

        $this->pizzas = [];

        session_start();
    }

    public function __destruct() 
    {
        parent::__destruct();
    }
    
    public function getViewData()
    {
        if ($this->_database->connect_errno) {
            throw new Exception("MySQL ErrorCode: " . $this->_database->connect_errno);
            return false;
        }
        try {
            if(isset($_SESSION['order_id'])){

                $order_id = $_SESSION['order_id'];
                $sql = "SELECT ordered_articles.id, ordered_articles.f_order_id, ordered_articles.status, article.name
                        FROM ordered_articles, article
                        WHERE ordered_articles.f_article_id = article.id AND ordered_articles.f_order_id = $order_id
                        ORDER BY ordered_articles.f_order_id";

                $Recordset = $this->_database->query($sql);

                if(!$Recordset){
                    throw new Exception ("error");
                } else {

                    while ($record = $Recordset->fetch_assoc()){
                        $this->pizzas[] = $record;
                    }

                    $serializedData = json_encode($this->pizzas);
                    
                    $Recordset->free();
                }
            }
            else {
                $serializedData = "";
            }

        } catch (Exception $e) {
            echo $e->getMessage();
        }    
        return $serializedData;
    }
    

    protected function generateView() 
    {
        header("Content-Type: application/json; charset=UTF-8");

        echo $this->getViewData();
        
    }

    protected function processReceivedData() 
    {
        parent::processReceivedData();
        // to do: call processReceivedData() for all members
        
    }
  
    public static function main() 
    {
        try {
            $page = new Status();
            $page->processReceivedData();
            $page->generateView();
        }
        catch (Exception $e) {
            header("Content-type: text/plain; charset=UTF-8");
            echo $e->getMessage();
        }
    }
}
Status::main();

<?php	

class Menu
{

    protected $pizzas = null;
    protected $_database = null;

    public function __construct($database)
    {
        $this->_database = $database;
        $this->pizzas = [];

    }

    protected function getViewData()
    {

    }
                  
    private function generateRow($pizza)
    {

        $id = htmlspecialchars($pizza['id']); 
        $name = htmlspecialchars($pizza['name']);
        $picture = htmlspecialchars($pizza['picture']);
        $price = htmlspecialchars($pizza['price']);

        echo <<<EOF
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body">
                            <p class="card-title">$name</p>

                             <a id="{$id}" title="{$name}" data-price="{$price}" onclick="cart.addPizza('{$id}')"><img id="p-img" class="img-responsive" src="./assets/{$picture}"></a>
                                                    
                            <div id="p-price" class="row">
                                <p id="price-title">Price:</p>
                                <p >$price €</p>
                            </div>
                            <div class="row">
                                <button type="button" class="btn" id="{$id}" onclick="cart.addPizza('{$id}')">Add to cart</button>
                            </div>
                            
                        </div>
                    </div>
                </div>
                    
EOF;
    }


    public function generateView($id = "", array $pizzas)
    {
        
        if ($id) {
            $id = "id=\"$id\"";
        }
        echo <<<EOF
        <body>

            <header class="py-1 flex align-items-center text-right" id="p-header">
                <a class="col-auto" href="index.php">
                    <img src="./assets/logo.PNG" class="img-responsive" id="p-logo">
                </a>
            </header>

            <div id="p-order" class="jumbotron">
                <section class="menu">
                    <h1 class="display-5"> Menu </h1>
                        <div class="row">
EOF;
        foreach($pizzas as $pizza){
            $this->generateRow($pizza);
        }                  

        echo "</div>";
        echo "</section>";
    }


    public function processReceivedData()
    {
        // to do: call processData() for all members
    }
 
}

<?php	// UTF-8 marker äöüÄÖÜß€

class Cart
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

    public function generateView($id = "")
    {
        if ($id) {
            $id = "id=\"$id\"";
        }

        echo <<<EOF

            <section class="cart">
                <form action="order.php" id="order_form" method="POST">

                    <h1 class="display-5"> Cart </h1>

                    <div class="row">
                        <div class="col-md-9">
                            <select name="orders[]" id="orders" multiple size="8"></select>
                            
                        </div>
                        <div class="col-md-9">
                            <p id="total">Sub total: <span id="sum">0,00</span> <span id="euro"> €</span></p>
                        </div>   
                    </div>

                    <div class="row" id="submit-field-row">
                        <div id="cart-row" class="col-md-9">
                            <div class="row" id="submit-field-row-down">
                                <p class="col-8 col-sm-7"> Address: 
                                    <input id="address" type="text" name="address" placeholder="Please enter your address here" onchange="cart.validate()">
                                </p>

                                <article class="col-4 col-sm-5">
                                    <button class="btn"  id="delete-all"type="reset" onclick="cart.resetPizzas()">Delete All</button>
                                    <button class="btn" id="delete-selected"type="reset" onclick="cart.removePizza()">Delete selected</button>
                                    <button class="btn" id="submit-order" type="submit" onclick="cart.submit()">Order</button>
                                </article>  
                            </div>
                        </div>
                    </div>
                    
                </form>
            
            </section>
        </div>
EOF;
    }

    public function processReceivedData()
    {
       
    }
}

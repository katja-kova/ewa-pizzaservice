<?php	// UTF-8 marker äöüÄÖÜß€

require_once './templates/Page.php';

class Customer extends Page
{

    protected $order = null;
    protected $_database = null;

    protected function __construct()
    {
        parent::__construct();
        $this->order = [];        

    }

    public function __destruct()
    {
        parent::__destruct();
    }

    protected function getViewData()
    {

    }

    protected function generateView()
    {
        $stati = array("ordered", "preparing", "ready", "on the way", "delivered");
        $this->generatePageHeader('Customer', false);

        echo <<<EOF

        <body>

            <header class="py-1 flex align-items-center text-right" id="p-header">
                <a class="col-auto" href="index.php">
                    <img src="./assets/logo.PNG" class="img-responsive" id="p-logo">
                </a>
            </header>

            <div id="p-customer" class="jumbotron">
                <script src="./scripts/status-update.js"></script>
                <div class="container">
                    <div class="row">
                        <div class="col-sm-9">
                            <h1 id="title"></h1>
                        </div>
                        <div class="col-sm-11">
                            <ul class="list-group" id="status-list">

                            </ul>
                        </div>
                        <div class="col-sm-9">
                            <button class="btn" onclick="window.location.href='order.php'">New Order</button>
                        </div>
                    </div>
                </div>
            </div>
EOF;


        $this->generatePageFooter();
    }

    protected function processReceivedData()
    {
       // parent::processReceivedData();
        // to do: call processReceivedData() for all members
    }

    public static function main()
    {
        try {
            $page = new Customer();
            $page->processReceivedData();
            $page->generateView();
        }
        catch (Exception $e) {
            header("Content-type: text/plain; charset=UTF-8");
            echo $e->getMessage();
        }
    }
}

Customer::main();

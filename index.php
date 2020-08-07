<?php	// UTF-8 marker äöüÄÖÜß€

require_once './templates/Page.php';


class Index extends Page
{
    // to do: declare reference variables for members
    // representing substructures/blocks


    protected function __construct()
    {
        parent::__construct();
        // to do: instantiate members representing substructures/blocks
    }


    public function __destruct()
    {
        parent::__destruct();
    }

    protected function getViewData()
    {
        // to do: fetch data for this view from the database
    }

    protected function generateView()
    {
        $this->getViewData();
        $this->generatePageHeader('Pizzaservice', false);
        echo <<<EOF
        <body>
        
        <header class="py-1 flex align-items-center text-right" id="p-header">
            <a class="col-auto">
                <img src="./assets/logo.PNG" class="img-responsive" id="p-logo">
            </a>
        </header>

        <div id="p-index" class="jumbotron">
            <ul id="p-nav">
                <li><a class="button" href="order.php">Order</a></li>
                <li><a class="button" href="customer.php">Customer</a></li>
                <li><a class="button" href="baker.php">Baker</a></li>
                <li><a class="button" href="driver.php">Driver</a></li>
            </ul>
        </div>
EOF;
        $this->generatePageFooter();
    }

    protected function processReceivedData()
    {
        parent::processReceivedData();
        // to do: call processReceivedData() for all members
    }

    public static function main()
    {
        try {
            $page = new Index();
            $page->processReceivedData();
            $page->generateView();
        }
        catch (Exception $e) {
            header("Content-type: text/plain; charset=UTF-8");
            echo $e->getMessage();
        }
    }
}


Index::main();


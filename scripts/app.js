window.onload = function () {
    window.cart = new Cart();
};

var address_invalid = "Invalid address";
var pizza_missing = "Please choose something";
var pizza_amount = "Too many pizzas";

function Cart() {
    "use strict";
    
    var pizzas = [];
    
    var ordersElement = document.getElementById("orders");
    var form = document.getElementById("order_form");
    var sumElement = document.getElementById("sum");
    var addressElement = document.getElementById("address");
    var submitButton = document.getElementById("submit-order");
    if(submitButton!=null){submitButton.disabled = true;}    

    this.addPizza = function (id) {
        // check if pizza already exists
        var pizza_tag = document.getElementById(id); 
        var id_pizza = parseInt(pizza_tag.id);
        var pizza_object = findPizzaObjectByName(id_pizza);       
        var price = parseFloat(pizza_tag.getAttribute("data-price"));
        price.toFixed(2);

        if (pizza_object === null) {
            pizzas.push({id: id_pizza, price: price, count: 1}); // add new pizza
        } else {
            if(pizza_object.count < 20)
                pizza_object.count += 1; // increase number of pizza
            else   // more than 20 pizzas in shopping cart
                console.log(pizza_amount);
        }
        updateDOM();
    };

    /* search pizza object in array */
    function findPizzaObjectByName(id) {
        "use strict";

        for (var i = 0; i < pizzas.length; i++) {
            if (pizzas[i].id == id) {
                return pizzas[i];
            }
        }
        return null;
    }

    /* find selected pizza*/ 
    function findSelectedPizza() {
        "use strict";

        var used_indizes = [];
        for (var position = 0; position < ordersElement.options.length; position++) {
            var option = ordersElement.options[position];
            if (option.selected)
                used_indizes.push(position);
        }
        return used_indizes;
    }

    /* delete marked pizza */
    this.removePizza = function () {
        "use strict";
        var used_indizes = findSelectedPizza();
        var position = 0;
        while (position < used_indizes.length){
            var index = used_indizes[position];
            var option = ordersElement.options[index];
            var obj = JSON.parse(option.value);
            var pizza = findPizzaObjectByName(obj.id);
            if (pizza.count > 1) {
                pizza.count -= 1;
                option.selected = true;
                position++;
            } else {
                var elemIndex = pizzas.indexOf(pizza);
                pizzas.splice(elemIndex, 1);   // delete selected pizza from pizzas[]
                used_indizes.splice(position, 1);
            }
        }
        updateDOM();
    };

    /* delete whole cart */
    this.resetPizzas = function () {
        "use strict";
        pizzas = [];
        updateDOM();
    };

    /* send cart to the server, if all inputs correct */
    this.submit = function () {
        "use strict";

            updateDOM(); // render again
            selectEachOptionInDom();
            
            form.submit();
            return true;
    };

    this.validate = function (){
        validateOrder();
    }

    function selectedPizza(){
        "use strict";

        return pizzas.length > 0;
    };

    function validateOrder(){
        if(validateAddress() && selectedPizza()){
            submitButton.disabled = false;
            return true;            
        } else {
            submitButton.disabled = true;
            return false;
        }
    }

    function validateAddress(){
        "use strict";
        if(String(addressElement.value)=="undefined"||String(addressElement.value).length==0){
            return false;
        }
        return true;
    }

    /* set each element to marked */
    function selectEachOptionInDom() {
        "use strict";

        var i;
        var option;
        for (i = 0; i < ordersElement.options.length; i+=1) {
            option = ordersElement.options[i];
            option.selected = true;
        } 
    }

    /* clone json object */
    function cloneObject(obj) {
        "use strict";

        return JSON.parse(objToString(obj));
    }

    /* convert json in string */
    function objToString(obj) {
        "use strict";

        return JSON.stringify(obj);
    }

    /* update DOM with informations of the cart */
    function updateDOM() {
        "use strict";

        resetOrderListInDOM();
        var option;
        for (var position = 0; position < pizzas.length; position+=1) {
            option = createPizzaOptionElement(pizzas[position]);
            ordersElement.add(option);
        }
        var price = calculateTotalPrice();
        updatePriceInDOM(price);

        validateOrder();
    }

    /* number and name of pizza */
    function createPizzaOptionElement(pizza){
        "use strict";

        // create option element
        var option = document.createElement("option");
        option.style.margin='30px 0 30px 0';

        var pizza_tag = document.getElementById(pizza.id); 
        var name_pizza = String(pizza_tag.title);
        
        option.text = pizza.count + "x " + name_pizza;
        var obj = cloneObject(pizza);  
        delete obj.price;    
        option.value = objToString(obj);
        return option;
    }

    /* set price in DOM */
    function updatePriceInDOM(totalPrice){
        "use strict";

        sumElement.removeChild(sumElement.firstChild);
        var new_price = document.createTextNode(totalPrice);
        sumElement.appendChild(new_price);
    }

    /* delete all elements */
    function resetOrderListInDOM() {
        "use strict";

        // if a valid child element exists 
        while (ordersElement.firstChild) {
            // remove this element
            ordersElement.removeChild(ordersElement.firstChild);
        }
    }

    /* calculate the price of the cart */
    function calculateTotalPrice() {
        "use strict";

        var totalPrice = 0.0;
        for (var position = 0; position < pizzas.length; position+=1) {
            var pizza = pizzas[position];
            // number of selected pizza * price, e.g. 3x Margherita
            totalPrice += pizza.count * pizza.price;
        }
        totalPrice = totalPrice.toLocaleString();
        return totalPrice;
    }
}
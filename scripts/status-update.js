window.onload = function () {
  polling();
};

var request= new XMLHttpRequest(); 

function process(jsonOrder) {
  "use strict"
  
  var title = document.getElementById("title");

  if(jsonOrder==''){
    if(!title.hasAttribute("class")){
      title.setAttribute("class", "display-5");
      title.classList.add("text-center");
      title.style.marginBottom='5%';
      title.style.backgroundColor='white';

      var num = document.createTextNode('No orders yet');
      title.appendChild(num);
    }
  } else {
    var input = JSON.parse(jsonOrder);
    var statusArray = {0:"ordered", 1:"preparing", 2:"ready",3:"on the way",4:"delivered"};

    
    for(var i=0; i<input.length; ++i){
      var pizzaID = input[i]['id'];
            var pizzaOID = input[i]['f_order_id'];
            var pizzaStatus=statusArray[input[i]['status']];
            var pizzaName = input[i]['name'];
            
            insertPizza(pizzaID, pizzaOID, pizzaStatus, pizzaName);
    }
    
  }
}

function insertPizza(pizzaID, pizzaOID, pizzaStatus, pizzaName) {

  var statusList = document.getElementById("status-list");

  if(document.getElementById(pizzaOID)==null){
 
    var title = document.getElementById("title");
    title.classList.add("display-5");
    title.classList.add("text-center");
    title.setAttribute("id", pizzaOID);
    title.style.marginBottom='5%';
    title.style.backgroundColor='white';

    var num = document.createTextNode('Order #'+pizzaOID);
    title.appendChild(num);

   } else {

    if(document.getElementById(pizzaID)==null){
      var entryPizza = document.createElement('li');
      entryPizza.classList.add("list-group-item");

      /*--------------------CSS--------------------------*/
      entryPizza.style.marginBottom='3%';
      entryPizza.style.backgroundColor='#F7F7F7';
      entryPizza.style.borderStyle='none';
      entryPizza.style.height='60px';
      entryPizza.style.display='flex';
      entryPizza.style.alignItems='center';
      entryPizza.style.paddingLeft='5vw';
      /*-------------------------------------------------*/

            entryPizza.setAttribute("id", pizzaID);
            var entryPizzaName = document.createTextNode(pizzaName);
            entryPizza.appendChild(entryPizzaName);

            var mainStatusSpan = document.createElement('span');
            mainStatusSpan.style.right='0';
            mainStatusSpan.style.position='absolute';
            mainStatusSpan.style.marginRight='5%';
            mainStatusSpan.style.display='flex';
            mainStatusSpan.style.alignItems='center';
            mainStatusSpan.style.width='15%';

            var bulletSpan = document.createElement('span');
            bulletSpan.style.fontSize='4vw';

            if(pizzaStatus=='ordered'){
              bulletSpan.style.color='#247BA0';
            } else if(pizzaStatus=='preparing') {
              bulletSpan.style.color='#70C1B3';
            } else if(pizzaStatus=='ready') {
              bulletSpan.style.color='#B2DBBF';
            } else if(pizzaStatus=='on the way') {
              bulletSpan.style.color='#F3FFBD';
            } else {
              bulletSpan.style.color='#FF1654';
            }

            var bullet = document.createTextNode('•');
            bulletSpan.appendChild(bullet);

            var statusSpan = document.createElement('span');
            statusSpan.style.marginLeft='1vw';
            var statusSpanText = document.createTextNode(pizzaStatus);
            statusSpan.appendChild(statusSpanText);

            mainStatusSpan.appendChild(bulletSpan);
            mainStatusSpan.appendChild(statusSpan);

            entryPizza.appendChild(mainStatusSpan);
            entryPizza.data = pizzaStatus;
            statusList.appendChild(entryPizza);
    } else {
      var originalPizza = document.getElementById(pizzaID);
            if (originalPizza.data != pizzaStatus) {
                var entryPizza = document.createElement('li');
                entryPizza.classList.add("list-group-item");
                entryPizza.setAttribute("id", pizzaID);


                var entryPizzaName = document.createTextNode(pizzaName);
            entryPizza.appendChild(entryPizzaName);

            var mainStatusSpan = document.createElement('span');
            mainStatusSpan.style.right='0';
            mainStatusSpan.style.position='absolute';
            mainStatusSpan.style.marginRight='5%';
            mainStatusSpan.style.display='flex';
            mainStatusSpan.style.alignItems='center';
            mainStatusSpan.style.width='15%';

            var bulletSpan = document.createElement('span');
            bulletSpan.style.fontSize='4vw';

            if(pizzaStatus=='ordered'){
              bulletSpan.style.color='#F3FFBD';
            } else if(pizzaStatus=='preparing') {
              bulletSpan.style.color='#70C1B3';
            } else if(pizzaStatus=='ready') {
              bulletSpan.style.color='#B2DBBF';
            } else if(pizzaStatus=='on the way') {
              bulletSpan.style.color='#247BA0';
            } else if(pizzaStatus=='delivered') {
              bulletSpan.style.color='#FF1654';
            }

            var bullet = document.createTextNode('•');
            bulletSpan.appendChild(bullet);

            var statusSpan = document.createElement('span');
            statusSpan.style.marginLeft='1vw';
            var statusSpanText = document.createTextNode(pizzaStatus);
            statusSpan.appendChild(statusSpanText);

            mainStatusSpan.appendChild(bulletSpan);
            mainStatusSpan.appendChild(statusSpan);

            entryPizza.appendChild(mainStatusSpan);
            entryPizza.data = pizzaStatus;
                originalPizza.replaceWith(entryPizza);
            }
    }
  }
}


function polling() {
  "use strict"

  window.setInterval(requestData, 2000);
}

function requestData() { // Daten asynchron anfordern
  request.open("GET", "http://localhost/Praktikum/Prak5/blocks/status.php"); // URL für HTTP-GET
  request.onreadystatechange = processData; //Callback-Handler zuordnen
  request.send(null); // Request abschicken
  
}

function processData() {
  if(request.readyState == 4) { // Uebertragung = DONE
     if (request.status == 200) {   // HTTP-Status = OK
       if(request.responseText != null) 
         process(request.responseText);// Daten verarbeiten
       else console.error ("Dokument ist leer");        
     } 
     else console.error ("Uebertragung fehlgeschlagen");
  } else ;          // Uebertragung laeuft noch
 }
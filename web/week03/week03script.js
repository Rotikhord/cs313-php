var count = {"a" : 0, "e" : 0, "i" : 0, "o" : 0, "u" : 0, "y" : 0};
var total = {"a" : 0, "e" : 0, "i" : 0, "o" : 0, "u" : 0, "y" : 0};


//Validate Quantities
function validateQty(element){
  if(isNaN(element.value)){
    element.value = "";
    return;
  }
  var qty = parseInt(element.value);

  if(qty < 0){
    element.value = "0";
    qty = 0;
  } else if (qty > 99){
    element.value = "99";
    qty = 99;
  }
  var letter = element.id[6];

  var price = document.getElementById('price_' + letter).innerHTML;
  document.getElementById('total_' + letter).innerHTML = (price * qty).toFixed(2);
  if (document.getElementById('total_' + letter).innerHTML == "NaN"){
    document.getElementById('total_' + letter).innerHTML = "-";
  }
}

//Update Quantity
function updateQty(element){
  var letter = element.id[6];
  var quantity = document.getElementById('input_' + letter);
  var price = document.getElementById('price_' + letter).innerHTML;
  validateQty(quantity);
  if (quantity.value != ""){
    count[letter] = quantity.value;
    total[letter] = (quantity.value * price).toFixed(2);
  } else {
    count[letter] = 0;
    total[letter] = 0;
  }
  displayTotals();
  updateSession(letter);
}

function displayTotals(){
  var totalCount= 0 ;
  var totalAmount= 0 ;

  for(var key in count)
  {
    totalCount += parseFloat(count[key]);
    totalAmount += parseFloat(total[key]);
  }
  document.getElementById('cartItems').innerHTML = "Items: " + totalCount;
  document.getElementById('cartTotal').innerHTML = "Total: $" + totalAmount.toFixed(2);

}

function updateSession(letter){
//  console.log(letter);
  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function(){
    if (this.readyState == 4 && this.status == 200) {
      console.log(xhttp.responseText);
    }
  }
  //console.log("Sending Request");
  xhttp.open("GET", "updateQty.php?vowel="+letter+"&count="+count[letter], true);
  xhttp.send();
}

function onLoad(){
  for(var key in count)
  {
    var element = document.getElementById('input_' + key);
    if (element != undefined){
      validateQty(element);
    }
    element = document.getElementById('button' + key);
    if (element != undefined){
      updateQty(element);
    }
  }

}

function goToCart(){
  alert(test);
}

//Validate

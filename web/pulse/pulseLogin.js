
function validateLogin(){
  var password = document.getElementById('password');
  var email = document.getElementById('email');
  var postString = "&email=" + email.value + "&password=" + password.value;

  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function(){
    if (this.readyState == 4 && this.status == 200) {
      console.log(xhttp.responseText);
      if (xhttp.responseText == -1){
        document.getElementById('testMessage').innerHTML = "Invalid email or password.";
      } else if (xhttp.responseText == 1) {
        window.location.replace('workbench.php');
      } else {
        document.getElementById('testMessage').innerHTML = "The server is currently unavailable.";

      }
    }
  }
  xhttp.open("POST", "verifyLogin.php", true);
  xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhttp.send(postString);
}

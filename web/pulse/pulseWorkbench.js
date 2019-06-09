//Global variables just to keep track of current state of the list being displayed.
var sortAscending = "true";
var selectedColumn = 1;
var rowSelection = "";
var displayEmpty = false;

//This function handles when user selects a menu option
function selectList(selection){
    //gets the selction
    var content = selection.id.substring(4,15);
    sortAscending = "true";
    selectedColumn = 0;
    rowSelection = "";
    loadList(content, 0)
}

//This function handles when user clicks a column header
function getSort(column){
  if (column == selectedColumn){
    sortAscending = !sortAscending;
  }
  selectedColumn = column;
  loadList("same", column)
}

//This function refreshes the displayed list
function loadList(list, index){
  var request = new XMLHttpRequest();
  request.onreadystatechange = function(){
    if (this.readyState == 4 && this.status == 200){
      if(list != "same"){
        document.getElementById('contentDetails').innerHTML = "";
      }
      document.getElementById('contentList').innerHTML = request.responseText;
      var rows = document.getElementsByClassName('listRecord');
        for (var i = 0; i < rows.length; i++){
          unhighlightRow(rows[i]);
      }
    }
  }
request.open("GET", "getList.php?list=" + list + "&sortColumn=" + index + "&sortAscending=" + sortAscending, true);
request.send();
}

//This function searches the current list for matches.
function searchList(searchInput){
  var regex = new RegExp(searchInput.value, "i");
  var rows = document.getElementsByClassName('listRecord');
  for (var i = 0; i < rows.length; i++){
    var displayRow = false;
    for (var j =  0; j < rows[i].childNodes.length; j++){
      if(rows[i].childNodes[j].innerHTML != undefined){
        if (regex.test(rows[i].childNodes[j].innerHTML)){
          displayRow = true;
          //a match was found
          break;
        }
      }
    }
    if (displayRow){
      rows[i].style.display= "table-row";
    } else {
      rows[i].style.display= "none";
    }
  }
}

function getDetails(element){
  //Check to see what type of element is calling
  if (element.nodeName == "TR"){ //If it is a tablerow
    rowSelection = element.id;
    var rows = document.getElementsByClassName('listRecord');
      for (var i = 0; i < rows.length; i++){
        unhighlightRow(rows[i]);
    }
    element.style.backgroundColor = '#359C88';
  } else if (element.nodeName == "INPUT") { //Else if it is an input item
    displayEmpty = element.checked;
  }

  var id = rowSelection.substring(6,16);
  var request = new XMLHttpRequest();
  request.onreadystatechange = function(){
    if (this.readyState == 4 && this.status == 200){
      document.getElementById('contentDetails').innerHTML = request.responseText;
    }
  }
request.open("GET", "getDetails.php?record=" + id + "&displayEmpty=" + displayEmpty);
request.send();

}

function highlightRow(row){
  row.style.backgroundColor = '#85ECD8';
}

function unhighlightRow(row){
  if (row.id == rowSelection){
    row.style.backgroundColor = '#359C88';
  } else {
    row.style.backgroundColor = '#65CCB8';
  }
}


function addNew(){
  rowSelection = '';
  var rows = document.getElementsByClassName('listRecord');
    for (var i = 0; i < rows.length; i++){
      unhighlightRow(rows[i]);
  }

  var request = new XMLHttpRequest();
  request.onreadystatechange = function(){
    if (this.readyState == 4 && this.status == 200){
      document.getElementById('contentDetails').innerHTML = request.responseText;
    }
  }
request.open("GET", "getDetails.php?record='0'");
request.send();
}


//updates parent record
function update(element){
  if (!checkRequiredFields(element)){
    return;
  }

  var editableFields = document.getElementsByClassName('editable');
  var requestString = "&" + element.id + "=" + element.value;

  for (var i = 0; i< editableFields.length; i++){
    requestString += "&" + editableFields[i].id + "=" + encodeURIComponent(editableFields[i].value);
  }

  var request = new XMLHttpRequest();
  request.onreadystatechange = function(){
    if (this.readyState == 4 && this.status == 200){
      loadList("same", selectedColumn);
      document.getElementById('contentDetails').innerHTML = request.responseText;
    }
    }
    if (element.value != 0) {
        request.open("POST", "updateItem.php" ,  true);
}   else {
        request.open("POST", "addItem.php" ,  true);
    }
    request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    request.send(requestString);
    }

//Checks required fields 
function checkRequiredFields(element){
  var requiredFields = [];
  var isValid = true;

    switch (element.id) {
        case "pun_pk":
             requiredFields.push("pun_description", "pun_summary");
            break;
        case "cus_pk":
            requiredFields.push("cus_phone1");
            break;
        case "job_pk":
            requiredFields.push("job_number", "job_date", "job_description", "job_balance");
            break;
        case "emp_pk":
            requiredFields.push("emp_fname", "emp_lname", "emp_email", "emp_permissions");
            break;
        case "upd_pk":
            requiredFields.push("upd_description");
            break;
        case "assign_pun_fk":
            console.log('ask_pun_fk');
            requiredFields.push("asi_emp_fk");
            break;
        case "assign_emp_fk":
            console.log('asi_emp_fk');
            requiredFields.push("asi_pun_fk");
            break;
        default:
        isValid = false;
    }

  for (var i = 0; i < requiredFields.length; i++){
    var field = document.getElementById(requiredFields[i]);
    if (field.value == '' || field.value == -1){
      isValid = false;
      field.placeholder = "This is a required field.";
    }
  }
  return isValid;
}

//Updates child records.
function updateChild(element) {
    if (!checkRequiredFields(element)) {
        return;
    }

    var parentkey = 0;
    var keyname = 'parentKey';
    //Get the parentkey
    switch (element.id) {
        case "pun_pk":
            parentkey = document.getElementById('job_pk').value;
            keyname = 'pun_job_fk';
            break;
        case "job_pk":
            parentkey = document.getElementById('cus_pk').value;
            keyname = 'job_cus_fk';
            break;
        case "upd_pk":
            parentkey = document.getElementById('pun_pk').value;
            keyname = 'upd_pun_fk';
            break;
        case "asi_pun_fk":
            parentkey = document.getElementById('emp_pk').value;
            keyname = "asi_emp_fk";
            break;
        case "assign_emp_fk":
            parentkey = document.getElementById('emp_pk').value;
            keyname = "asi_emp_fk";
            break;
        case "assign_pun_fk":
            parentkey = document.getElementById('pun_pk').value;
            keyname = "asi_pun_fk";
            break;
        default:
            return;
    }

    var editableFields = document.getElementsByClassName('editableChildField');
    var requestString = "&" + element.id + "=" + element.value + "&" + keyname + "=" + parentkey + "&isChild=true";

    for (var i = 0; i < editableFields.length; i++) {
        requestString += "&" + editableFields[i].id + "=" + encodeURIComponent(editableFields[i].value);
    }

    var request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            loadList("same", selectedColumn)
            document.getElementById('detailChildBlock').innerHTML = request.responseText;
        }
    }
    if (element.value != 0) {
        request.open("POST", "updateItem.php", true);
    } else {
        request.open("POST", "addItem.php", true);
    }
    request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    request.send(requestString);
}


//This function handles assignment deletions
function deleteAssign(element) {
    var requestString = "&" + element.id + "=" + element.value;


    var request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById('detailChildBlock').innerHTML = request.responseText;
        }
    }

    request.open("POST", "deleteItem.php", true);
    request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    request.send(requestString);
}


//Handle Getting child details
function getDetailsChild(element) {

    var id = element.id.substring(6, 16);
    var request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById('detailChildBlock').innerHTML = request.responseText;
        }
    }
    request.open("GET", "getDetails.php?record=" + id + "&displayEmpty=" + displayEmpty + "&childDetails=true");
    request.send();

}


function addNewChild() {
    rowSelection = '';
    var rows = document.getElementsByClassName('listRecord');
    for (var i = 0; i < rows.length; i++) {
        unhighlightRow(rows[i]);
    }

    var request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById('detailChildBlock').innerHTML = request.responseText;
       }
    }
    request.open("GET", "getDetails.php?record='0'&childDetails=true");
    request.send();
}

//This function refreshes the displayed list
function getChildList(list) {
    var parentKey = 0;

    if (list == 'Jobs') {
        parentKey = document.getElementById('cus_pk').value;
    } else if (list == 'Punchlist') {
        parentKey = document.getElementById('job_pk').value;
    }
    var request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById('detailChildBlock').innerHTML = request.responseText;
        }
    }
    request.open("GET", "getList.php?list=" + list + "&parentKey=" + parentKey, true);
    request.send();
}
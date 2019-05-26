//Global variables just to keep track of current state of the list being displayed.
var sortAscending = "true";
var selectedColumn = 1;
var rowSelection = "";

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
      document.getElementById('contentList').innerHTML = request.responseText;
      document.getElementById('contentDetails').innerHTML = "";
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

function getDetails(record){
  rowSelection = record.id;

  var rows = document.getElementsByClassName('listRecord');
  for (var i = 0; i < rows.length; i++){
    unhighlightRow(rows[i]);
  }
  record.style.backgroundColor = '#359C88';


  var id = record.id.substring(6,16);
  var request = new XMLHttpRequest();
  request.onreadystatechange = function(){
    if (this.readyState == 4 && this.status == 200){
      document.getElementById('contentDetails').innerHTML = request.responseText;
    }
  }
request.open("GET", "getDetails.php?record=" + id);// + "&sortColumn=" + index + "&sortAscending=" + sortAscending, true);
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

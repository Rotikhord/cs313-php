function enlarge(image){
  if (image.style.width!="130%"){
    image.style.width="130%";
    image.style.marginLeft="-15%";
  } else {
    image.style.width="100%";
    image.style.marginLeft="0";
  }
}

function highlight(row){
  if (row.className=="isOdd"){
    if(row.style.backgroundImage=="none"){
      row.style.backgroundImage="linear-gradient(to left, #004a90, #003e79, #003262, #02274c)"
    } else {
      row.style.backgroundImage="none";
      row.style.backgroundColor="#012030";
    }
  } else {
    if(row.style.backgroundImage=="none"){
      row.style.backgroundImage="linear-gradient(to right, #004a90, #003e79, #003262, #02274c)"
    } else {
      row.style.backgroundImage="none";
      row.style.backgroundColor="#012030";
    }
  }
}

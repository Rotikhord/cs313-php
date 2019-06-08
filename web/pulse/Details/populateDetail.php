<?php

if ($isChildDetails == true){
	$class = 'editableChildField';
} else {
	$class = 'editable';
}

for ($i = 0; $i < sizeof($blockFields); $i++){
	if ($result[$blockFields[$i]] != "" || $displayEmpty || $record == 0){
	    echo "<label for='" . $blockFields[$i] . "'>" . $blockLabels[$i] . ":</label><br>";
		//Description fields are displayed in a textArea
		if ($fieldType[$i] == "textArea"){
			echo "<textarea class='";
			if(intval($_SESSION['permissions']) >= $fieldEditable[$i]){
				echo "$class' ";
			} else {
				echo "unEditable' readonly ";
			}
			echo "id='". $blockFields[$i] . "'>" . $result[$blockFields[$i]] . "</textarea><br>";
		} else {
			echo "<input autocomplete='off' class='";
			
			if(intval($_SESSION['permissions']) >= $fieldEditable[$i]){
				echo "$class' ";
			} else {
				echo "unEditable' readonly ";
			}

			echo "id='". $blockFields[$i] . "' type='" . $fieldType[$i] ."' value='";
			if ($fieldType[$i] != 'number'){
				echo $result[$blockFields[$i]] . "'><br>";
			} else {
				echo number_format(floatval($result[$blockFields[$i]]), 2, '.','') . "'><br>";
			}
		}
    }
}
?>

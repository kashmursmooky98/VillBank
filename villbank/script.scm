function getData() {
  fetch("data.php")
    .then(response => response.json())
    .then(data => {
      createTable(data);
    });
}

function createTable(data) {
  // Get table reference
  var table = document.getElementById("myTable");

  // Clear existing table content
  table.innerHTML = "";

  // Create table header row
  var headerRow = table.insertRow();
  for (var key in data[0]) {
    var headerCell = headerRow.insertCell();
    headerCell.textContent = key;
  }

  // Create data rows
  for (var i = 0; i < data.length; i++) {
    var row = table.insertRow();
    for (var key in data[i]) {
      var cell = row.insertCell();
      cell.textContent = data[i][key];
    }
  }
}

function addRow() {
  // Get table reference
  var table = document.getElementById("myTable");

  // Insert a new row at the end
  var newRow = table.insertRow(-1);

  // Add cells with empty content
  for (var i = 0; i < table.rows[0].cells.length; i++) {
    var newCell = newRow.insertCell();
    newCell.textContent = "";
  }
}

function removeRow() {
  // Get table reference
  var table = document.getElementById("myTable");

  // Check if there are rows to remove
  if (table.rows.length > 1) {
    table.deleteRow(-1);
  } else {
    alert("Cannot remove the only row!");
  }
}

// Call getData on page load
getData();

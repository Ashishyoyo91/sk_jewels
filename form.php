<?php
include 'header.php';
// Database connection
$db = new mysqli('localhost', 'root', '', 'jewelry_invoice');
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Jewelry Invoice - Create</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .invoice-card {
      background: white;
      border-radius: 8px;
      box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
      padding: 30px;
      margin-bottom: 30px;
      border-top: 4px solid #f57c00;
    }
    .section-title {
      color: #f57c00;
      border-bottom: 1px solid #eee;
      padding-bottom: 8px;
      margin-bottom: 20px;
      font-weight: 600;
      font-size: 1.2rem;
    }
    .item-row {
      background-color: #f8f9fa;
      border-radius: 5px;
      padding: 15px;
      margin-bottom: 15px;
      border-left: 3px solid #f57c00;
    }
    .form-label {
      font-weight: 500;
      font-size: 0.9rem;
    }
  </style>
</head>
<body>
  <div class="container py-4">
    <h1 class="text-center mb-4">Create Jewelry Invoice</h1>
    
    <div class="invoice-card">
      <form action="save_invoice.php" method="POST">
        <!-- Buyer Information -->
        <div class="mb-4">
          <h3 class="section-title">Buyer Information</h3>
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">Name*</label>
              <input type="text" class="form-control" name="buyer_name" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Company</label>
              <input type="text" class="form-control" name="buyer_company">
            </div>
            <div class="col-12">
              <label class="form-label">Address</label>
              <input type="text" class="form-control" name="buyer_address">
            </div>
            <div class="col-md-6">
              <label class="form-label">City, State ZIP</label>
              <input type="text" class="form-control" name="buyer_city">
            </div>
            <div class="col-md-6">
              <label class="form-label">Phone</label>
              <input type="text" class="form-control" name="buyer_phone">
            </div>
          </div>
        </div>
        
        <!-- Invoice Details -->
        <div class="mb-4">
          <h3 class="section-title">Invoice Details</h3>
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">Date*</label>
              <input type="date" class="form-control" name="invoice_date" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Invoice #*</label>
              <input type="text" class="form-control" name="invoice_number" required>
            </div>
            <div class="col-12">
              <label class="form-label">Supplier's Reference</label>
              <input type="text" class="form-control" name="supplier_ref">
            </div>
          </div>
        </div>
        
        <!-- Items -->
        <div class="mb-4">
          <h3 class="section-title">Items</h3>
          <div id="itemsContainer">
            <div class="item-row">
              <div class="row g-3">
                <div class="col-12">
                  <label class="form-label">Description*</label>
                  <textarea class="form-control" name="item_description[]" rows="2" required></textarea>
                </div>
                <div class="col-md-3">
                  <label class="form-label">Quantity*</label>
                  <input type="number" class="form-control" name="item_quantity[]" step="0.01" required>
                </div>
                <div class="col-md-3">
                  <label class="form-label">Rate*</label>
                  <input type="number" class="form-control" name="item_rate[]" step="0.01" required>
                </div>
                <div class="col-md-3">
                  <label class="form-label">Per</label>
                  <input type="text" class="form-control" name="item_per[]">
                </div>
                <div class="col-md-3">
                  <label class="form-label">Amount</label>
                  <input type="text" class="form-control" name="item_amount[]" readonly>
                </div>
              </div>
            </div>
          </div>
          
          <button type="button" class="btn btn-outline-primary mt-3" id="addItemBtn">
            Add Item
          </button>
        </div>
        
        <!-- Charges -->
        <div class="mb-4">
          <h3 class="section-title">Additional Charges</h3>
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">Tax Rate (%)*</label>
              <input type="number" class="form-control" name="tax_rate" value="1" step="0.01" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Other Charges</label>
              <input type="number" class="form-control" name="other_charges" value="0" step="0.01">
            </div>
          </div>
        </div>
        
        <!-- Totals -->
        <div class="mb-4">
          <div class="row">
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label">Subtotal</label>
                <input type="text" class="form-control" name="subtotal" readonly>
              </div>
              <div class="mb-3">
                <label class="form-label">Tax Amount</label>
                <input type="text" class="form-control" name="tax_amount" readonly>
              </div>
              <div class="mb-3">
                <label class="form-label">Total</label>
                <input type="text" class="form-control" name="total" readonly>
              </div>
            </div>
            <div class="col-md-6">
              <label class="form-label">Amount in Words</label>
              <textarea class="form-control" name="amount_words" rows="4" readonly></textarea>
            </div>
          </div>
        </div>
        
        <div class="text-center pt-3">
          <button type="submit" class="btn btn-primary btn-lg px-5">Save Invoice</button>
        </div>
      </form>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Function to calculate amounts
    function calculateAmounts() {
      let subtotal = 0;
      
      // Calculate item amounts
      document.querySelectorAll('.item-row').forEach((row, index) => {
        const qty = parseFloat(row.querySelector('[name="item_quantity[]"]').value) || 0;
        const rate = parseFloat(row.querySelector('[name="item_rate[]"]').value) || 0;
        const amount = qty * rate;
        
        // Update amount field
        row.querySelector('[name="item_amount[]"]').value = amount.toFixed(2);
        
        subtotal += amount;
      });
      
      // Update subtotal
      document.querySelector('[name="subtotal"]').value = subtotal.toFixed(2);
      
      // Calculate tax
      const taxRate = parseFloat(document.querySelector('[name="tax_rate"]').value) || 0;
      const taxAmount = subtotal * (taxRate / 100);
      document.querySelector('[name="tax_amount"]').value = taxAmount.toFixed(2);
      
      // Get other charges
      const otherCharges = parseFloat(document.querySelector('[name="other_charges"]').value) || 0;
      
      // Calculate total
      const total = subtotal + taxAmount + otherCharges;
      document.querySelector('[name="total"]').value = total.toFixed(2);
      
      // Update amount in words
      document.querySelector('[name="amount_words"]').value = numberToWords(total);
    }
    
    // Number to words function (simplified)
    function numberToWords(num) {
      // This is a simplified version - implement a full version as needed
      return "Amount in words: " + num.toFixed(2) + " USD";
    }
    
    // Add new item row
    document.getElementById('addItemBtn').addEventListener('click', function() {
      const newRow = document.createElement('div');
      newRow.className = 'item-row';
      newRow.innerHTML = `
        <div class="row g-3">
          <div class="col-12">
            <label class="form-label">Description*</label>
            <textarea class="form-control" name="item_description[]" rows="2" required></textarea>
          </div>
          <div class="col-md-3">
            <label class="form-label">Quantity*</label>
            <input type="number" class="form-control" name="item_quantity[]" step="0.01" required>
          </div>
          <div class="col-md-3">
            <label class="form-label">Rate*</label>
            <input type="number" class="form-control" name="item_rate[]" step="0.01" required>
          </div>
          <div class="col-md-3">
            <label class="form-label">Per</label>
            <input type="text" class="form-control" name="item_per[]">
          </div>
          <div class="col-md-3">
            <label class="form-label">Amount</label>
            <input type="text" class="form-control" name="item_amount[]" readonly>
          </div>
        </div>
        <div class="text-end mt-2">
          <button type="button" class="btn btn-sm btn-danger remove-item">Remove</button>
        </div>
      `;
      
      document.getElementById('itemsContainer').appendChild(newRow);
      
      // Add event listeners to new inputs
      newRow.querySelectorAll('input, textarea').forEach(input => {
        input.addEventListener('input', calculateAmounts);
      });
      
      // Add remove event listener
      newRow.querySelector('.remove-item').addEventListener('click', function() {
        this.closest('.item-row').remove();
        calculateAmounts();
      });
    });
    
    // Add event listeners to existing inputs
    document.querySelectorAll('input, textarea, select').forEach(input => {
      if (input.name !== 'item_amount[]' && input.name !== 'subtotal' && 
          input.name !== 'tax_amount' && input.name !== 'total' && 
          input.name !== 'amount_words') {
        input.addEventListener('input', calculateAmounts);
      }
    });
    
    // Initialize calculations
    calculateAmounts();
  </script>
</body>
</html>
<?php include 'footer.php'; ?>
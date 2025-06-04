<?php include 'header.php'; 
// Database connection
$db = new mysqli('localhost', 'root', '', 'jewelry_invoice');
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

// Get invoice ID
$invoice_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch invoice
$invoice_query = "SELECT * FROM invoices WHERE id = $invoice_id";
$invoice_result = $db->query($invoice_query);
$invoice = $invoice_result->fetch_assoc();

if (!$invoice) {
    die("Invoice not found");
}

// Fetch items
$items_query = "SELECT * FROM invoice_items WHERE invoice_id = $invoice_id";
$items_result = $db->query($items_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Invoice <?= htmlspecialchars($invoice['invoice_number']) ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f5f7fa;
    }
    .invoice-container {
      max-width: 800px;
      margin: 0 auto;
      border: 5px solid #f57c00;
      box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
      background: white;
      padding: 30px;
    }
    .invoice-header {
      background: #f57c00;
      color: white;
      padding: 10px;
      font-weight: bold;
      font-size: 20px;
      display: flex;
      justify-content: space-between;
    }
    .invoice-label {
      background: #f57c00;
      color: white;
      text-align: right;
      padding: 5px 10px;
      font-weight: bold;
      font-size: 18px;
      margin-top: 20px;
    }
    .total-table {
      width: 100%;
      margin-top: 20px;
    }
    .total-table td {
      padding: 5px 0;
    }
    .total-table tr:last-child td {
      font-weight: bold;
      border-top: 1px solid #ddd;
    }
    @media print {
      body * {
        visibility: hidden;
      }
      .invoice-container, .invoice-container * {
        visibility: visible;
      }
      .invoice-container {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        box-shadow: none;
        border: none;
      }
    }
  </style>
</head>
<body>
  <div class="container py-5">
    <div class="text-center mb-4">
      <button onclick="window.print()" class="btn btn-primary">Print Invoice</button>
      <a href="input_form.php" class="btn btn-secondary">Create New Invoice</a>
    </div>
    
    <div class="invoice-container">
      <div class="invoice-header">
        <div>JEWELRY ME, MODULE</div>
        <div>INVOICE</div>
      </div>
      
      <div class="mt-3">
        <div>Your Company Slogan</div>
        <div>Street Address</div>
        <div>City, ST ZIP Code</div>
        <div>Phone (503) 555-0190 Fax (503) 555-0191</div>
      </div>
      
      <div class="mt-4">
        <table style="width:100%">
          <tr>
            <td>
              <strong>Buyer:</strong><br>
              <?= htmlspecialchars($invoice['buyer_name']) ?><br>
              <?= htmlspecialchars($invoice['buyer_company']) ?><br>
              <?= htmlspecialchars($invoice['buyer_address']) ?><br>
              <?= htmlspecialchars($invoice['buyer_city']) ?><br>
              Phone <?= htmlspecialchars($invoice['buyer_phone']) ?>
            </td>
            <td style="text-align:right;">
              <strong>Date:</strong> <?= date('F j, Y', strtotime($invoice['invoice_date'])) ?><br>
              <strong>Invoice #:</strong> <?= htmlspecialchars($invoice['invoice_number']) ?><br>
              <strong>Supplier's Reference:</strong> <?= htmlspecialchars($invoice['supplier_ref']) ?>
            </td>
          </tr>
        </table>
      </div>
      
      <div class="invoice-label">INVOICE</div>
      
      <table class="table">
        <thead>
          <tr>
            <th>DESCRIPTION</th>
            <th>QTY</th>
            <th>RATE</th>
            <th>PER</th>
            <th>AMOUNT</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($item = $items_result->fetch_assoc()): ?>
          <tr>
            <td><?= nl2br(htmlspecialchars($item['description'])) ?></td>
            <td><?= number_format($item['quantity'], 2) ?></td>
            <td><?= number_format($item['rate'], 2) ?></td>
            <td><?= htmlspecialchars($item['per']) ?></td>
            <td><?= number_format($item['amount'], 2) ?></td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
      
      <table class="total-table">
        <tr>
          <td style="width: 85%; text-align:right;">SUBTOTAL:</td>
          <td>$ <?= number_format($invoice['subtotal'], 2) ?></td>
        </tr>
        <tr>
          <td style="text-align:right;">TAX RATE:</td>
          <td><?= number_format($invoice['tax_rate'], 2) ?>%</td>
        </tr>
        <tr>
          <td style="text-align:right;">SALES TAX:</td>
          <td>$ <?= number_format($invoice['tax_amount'], 2) ?></td>
        </tr>
        <tr>
          <td style="text-align:right;">OTHER:</td>
          <td>$ <?= number_format($invoice['other_charges'], 2) ?></td>
        </tr>
        <tr>
          <td style="text-align:right;">TOTAL:</td>
          <td>$ <?= number_format($invoice['total'], 2) ?></td>
        </tr>
      </table>
      
      <div class="mt-4">
        Amount Chargeable in Words:<br>
        <strong><?= htmlspecialchars($invoice['amount_words']) ?></strong>
      </div>
      
      <div class="mt-5">
        ___________________________<br>
        Authorized Signature
      </div>
      
      <div class="mt-4">
        Make all checks payable to your Company Name<br>
        Total due in 15 days. Overdue accounts subject to a service charge of 1% per month.<br>
        <strong>THANK YOU FOR YOUR BUSINESS!</strong>
      </div>
    </div>
  </div>
</body>
</html>
<?php include 'footer.php'; ?>
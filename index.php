
<?php
include 'header.php';
// Database connection
$db = new mysqli('localhost', 'root', '', 'jewelry_invoice');
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

// Fetch all invoices
$invoices = $db->query("
    SELECT `id`, `invoice_number`, `invoice_date`, `supplier_ref`, `buyer_name`, 
           `buyer_company`, `buyer_address`, `buyer_city`, `buyer_phone`, 
           `subtotal`, `tax_rate`, `tax_amount`, `other_charges`, `total`, 
           `amount_words`, `created_at` 
    FROM `invoices` WHERE is_delete = 0
    ORDER BY `created_at` DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Jewelry Invoices</title>
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
  <style>
    .invoice-card {
      background: white;
      border-radius: 8px;
      box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
      padding: 20px;
      margin-bottom: 30px;
      border-left: 4px solid #f57c00;
    }
    .table-responsive {
      margin-bottom: 30px;
    }
    .badge-invoice {
      font-size: 0.9em;
      background-color: #f57c00;
    }
    .action-btn {
      width: 30px;
      height: 30px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      margin: 0 3px;
    }
    .total-display {
      font-weight: 600;
      color: #f57c00;
    }
  </style>
</head>
<body>
  <div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <!-- <h1>Jewelry Invoices</h1> -->
      <a href="form.php" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Create New Invoice
      </a>
    </div>

    <?php if ($invoices->num_rows > 0): ?>
      <div class="table-responsive">
        <table class="table table-striped table-hover" id="myTable">
          <thead class="table-light">
            <tr>
              <th>Invoice #</th>
              <th>Date</th>
              <th>Buyer</th>
              <th>Company</th>
              <th>Subtotal</th>
              <th>Tax</th>
              <th>Total</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php while($invoice = $invoices->fetch_assoc()): ?>
              <tr>
                <td>
                  <span class="badge badge-invoice bg-primary"><?= htmlspecialchars($invoice['invoice_number']) ?></span>
                </td>
                <td><?= date('M d, Y', strtotime($invoice['invoice_date'])) ?></td>
                <td><?= htmlspecialchars($invoice['buyer_name']) ?></td>
                <td><?= htmlspecialchars($invoice['buyer_company']) ?></td>
                <td>₹<?= number_format($invoice['subtotal'], 2) ?></td>
                <td>₹<?= number_format($invoice['tax_amount'], 2) ?></td>
                <td class="total-display">₹<?= number_format($invoice['total'], 2) ?></td>
                <td>
                  <a href="view_invoice.php?id=<?= $invoice['id'] ?>" class="btn btn-sm btn-outline-primary action-btn" title="View">
                    <i class="bi bi-eye"></i>
                  </a>
                  <!-- <a href="edit.php?id=</?= $invoice['id'] ?>" class="btn btn-sm btn-outline-secondary action-btn" title="Edit"> -->
                    <!-- <i class="bi bi-pencil"></i> -->
                  </a>
                  <a href="delete.php?id=<?= $invoice['id'] ?>" class="btn btn-sm btn-outline-danger action-btn" title="Delete" onclick="return confirm('Are you sure you want to delete this invoice?')">
                    <i class="bi bi-trash"></i>
                  </a>
                </td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>

      <!-- Display invoice items when one is selected -->
      <?php if (isset($_GET['id'])): 
        $invoice_id = intval($_GET['id']);
        $invoice_items = $db->query("
          SELECT `id`, `description`, `quantity`, `rate`, `per`, `amount` 
          FROM `invoice_items` 
          WHERE `invoice_id` = $invoice_id
        ");
        
        $invoice_details = $db->query("
          SELECT * FROM `invoices` WHERE `id` = $invoice_id
        ")->fetch_assoc();
      ?>
        <div class="invoice-card">
          <div class="d-flex justify-content-between align-items-center mb-4">
            <h3>Invoice Details: <?= htmlspecialchars($invoice_details['invoice_number']) ?></h3>
            <span class="text-muted"><?= date('F j, Y', strtotime($invoice_details['invoice_date'])) ?></span>
          </div>

          <div class="row mb-4">
            <div class="col-md-6">
              <h5>Buyer Information</h5>
              <p>
                <strong><?= htmlspecialchars($invoice_details['buyer_name']) ?></strong><br>
                <?= htmlspecialchars($invoice_details['buyer_company']) ?><br>
                <?= htmlspecialchars($invoice_details['buyer_address']) ?><br>
                <?= htmlspecialchars($invoice_details['buyer_city']) ?><br>
                Phone: <?= htmlspecialchars($invoice_details['buyer_phone']) ?>
              </p>
            </div>
            <div class="col-md-6 text-end">
              <h5>Supplier Reference</h5>
              <p><?= htmlspecialchars($invoice_details['supplier_ref']) ?></p>
            </div>
          </div>

          <div class="table-responsive mb-4">
            <table class="table table-bordered">
              <thead>
                <tr class="table-light">
                  <th>Description</th>
                  <th>Quantity</th>
                  <th>Rate</th>
                  <th>Per</th>
                  <th>Amount</th>
                </tr>
              </thead>
              <tbody>
                <?php while($item = $invoice_items->fetch_assoc()): ?>
                  <tr>
                    <td><?= htmlspecialchars($item['description']) ?></td>
                    <td><?= number_format($item['quantity'], 2) ?></td>
                    <td>$<?= number_format($item['rate'], 2) ?></td>
                    <td><?= htmlspecialchars($item['per']) ?></td>
                    <td>$<?= number_format($item['amount'], 2) ?></td>
                  </tr>
                <?php endwhile; ?>
              </tbody>
              <tfoot>
                <tr>
                  <td colspan="4" class="text-end"><strong>Subtotal</strong></td>
                  <td><strong>$<?= number_format($invoice_details['subtotal'], 2) ?></strong></td>
                </tr>
                <tr>
                  <td colspan="4" class="text-end">Tax (<?= $invoice_details['tax_rate'] ?>%)</td>
                  <td>$<?= number_format($invoice_details['tax_amount'], 2) ?></td>
                </tr>
                <?php if ($invoice_details['other_charges'] > 0): ?>
                <tr>
                  <td colspan="4" class="text-end">Other Charges</td>
                  <td>$<?= number_format($invoice_details['other_charges'], 2) ?></td>
                </tr>
                <?php endif; ?>
                <tr class="table-active">
                  <td colspan="4" class="text-end"><strong>Total</strong></td>
                  <td><strong>$<?= number_format($invoice_details['total'], 2) ?></strong></td>
                </tr>
                <tr>
                  <td colspan="5" class="text-muted">
                    <small><?= htmlspecialchars($invoice_details['amount_words']) ?></small>
                  </td>
                </tr>
              </tfoot>
            </table>
          </div>

          <div class="d-flex justify-content-between">
            <small class="text-muted">Created: <?= date('M j, Y g:i A', strtotime($invoice_details['created_at'])) ?></small>
            <div>
              <a href="print.php?id=<?= $invoice_id ?>" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-printer"></i> Print
              </a>
              <a href="edit.php?id=<?= $invoice_id ?>" class="btn btn-sm btn-primary">
                <i class="bi bi-pencil"></i> Edit
              </a>
            </div>
          </div>
        </div>
      <?php endif; ?>
    <?php else: ?>
      <div class="alert alert-info">
        No invoices found. <a href="form.php" class="alert-link">Create your first invoice</a>.
      </div>
    <?php endif; ?>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Function to toggle invoice details view
    function toggleInvoiceDetails(invoiceId) {
      window.location.href = window.location.pathname + '?id=' + invoiceId;
    }

    $(document).ready(function () {
      $('#myTable').DataTable();
    });
  </script>
</body>
</html>
<?php include 'footer.php'; ?>
<?php
// Database connection
$db = new mysqli('localhost', 'root', '', 'jewelry_invoice');
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

// Process form data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize input
    $invoice_number = $db->real_escape_string($_POST['invoice_number']);
    $invoice_date = $db->real_escape_string($_POST['invoice_date']);
    $supplier_ref = $db->real_escape_string($_POST['supplier_ref'] ?? '');
    $buyer_name = $db->real_escape_string($_POST['buyer_name']);
    $buyer_company = $db->real_escape_string($_POST['buyer_company'] ?? '');
    $buyer_address = $db->real_escape_string($_POST['buyer_address'] ?? '');
    $buyer_city = $db->real_escape_string($_POST['buyer_city'] ?? '');
    $buyer_phone = $db->real_escape_string($_POST['buyer_phone'] ?? '');
    $subtotal = floatval($_POST['subtotal']);
    $tax_rate = floatval($_POST['tax_rate']);
    $tax_amount = floatval($_POST['tax_amount']);
    $other_charges = floatval($_POST['other_charges'] ?? 0);
    $total = floatval($_POST['total']);
    $amount_words = $db->real_escape_string($_POST['amount_words']);
    
    // Insert invoice
    $invoice_query = "INSERT INTO invoices (
        invoice_number, invoice_date, supplier_ref, 
        buyer_name, buyer_company, buyer_address, buyer_city, buyer_phone,
        subtotal, tax_rate, tax_amount, other_charges, total, amount_words
    ) VALUES (
        '$invoice_number', '$invoice_date', '$supplier_ref',
        '$buyer_name', '$buyer_company', '$buyer_address', '$buyer_city', '$buyer_phone',
        $subtotal, $tax_rate, $tax_amount, $other_charges, $total, '$amount_words'
    )";
    
    if ($db->query($invoice_query)) {
        $invoice_id = $db->insert_id;
        
        // Insert items
        if (isset($_POST['item_description'])) {
            foreach ($_POST['item_description'] as $index => $description) {
                $description = $db->real_escape_string($description);
                $quantity = floatval($_POST['item_quantity'][$index]);
                $rate = floatval($_POST['item_rate'][$index]);
                $per = $db->real_escape_string($_POST['item_per'][$index] ?? '');
                $amount = floatval($_POST['item_amount'][$index]);
                
                $item_query = "INSERT INTO invoice_items (
                    invoice_id, description, quantity, rate, per, amount
                ) VALUES (
                    $invoice_id, '$description', $quantity, $rate, '$per', $amount
                )";
                
                $db->query($item_query);
            }
        }
        
        // Redirect to view invoice
        header("Location: view_invoice.php?id=$invoice_id");
        exit();
    } else {
        die("Error saving invoice: " . $db->error);
    }
} else {
    header("Location: input_form.php");
    exit();
}
?>
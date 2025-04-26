<?php
// Include database connection
include("config/connection.php");

if (isset($_GET['item_id'])) {
    // Validate the item_id from the GET parameter
    $item_id = filter_input(INPUT_GET, 'item_id', FILTER_VALIDATE_INT);

    if ($item_id) {
        try {
            // Query to fetch the packing units and their rent details
            $query = "
                SELECT 
                    pum.packing_unit_id,
                    pum.packing_unit_name,
                    COALESCE(ippl.rent_kg_per_month, '0.00') AS rent_kg_per_month,
                    COALESCE(ippl.season_rent_per_kg, '0.00') AS season_rent_per_kg
                FROM tbl_packing_unit_master pum
                LEFT JOIN tbl_item_preservation_price_list_master ippl 
                    ON pum.packing_unit_id = ippl.packing_unit_id 
                    AND ippl.item_id = :item_id
                WHERE pum.packing_unit_id IS NOT NULL
            ";

            // Prepare the query
            $stmt = $_dbh->prepare($query);
            $stmt->bindParam(':item_id', $item_id, PDO::PARAM_INT);
            $stmt->execute();

            // Fetch all results
            $packingUnits = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Return the results as a JSON response
            header('Content-Type: application/json');
            echo json_encode($packingUnits);
        } catch (PDOException $e) {
            // Log the database error to a server-side log file
            error_log("Database Error: " . $e->getMessage());
            http_response_code(500);
            echo json_encode(['error' => 'Error fetching data. Please try again later.']);
        }
    } else {
        // Return error if item_id is invalid
        http_response_code(400);
        echo json_encode(['error' => 'Invalid item ID provided.']);
    }
} else {
    // Return error if item_id is not provided
    http_response_code(400);
    echo json_encode(['error' => 'Item ID not provided.']);
}
?>
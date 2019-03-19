<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

$app = new \Slim\App;

// Get all customers
$app->get('/api/customers' , function(Request $request, Response $response) {
    $sql = "SELECT * FROM customers";

    try {
        // Get DB object
        $db = new db();
        // connect
        $db = $db->connect();

        $stmt = $db->query($sql);

        $customers = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $db = null;

        echo json_encode($customers);
    } catch (PDOException $e) {
        echo '{"error" : {"text": '. $e->getMessage(). '}}';
    }
});

    // Get single customer
    $app->get('/api/customers/{id}' , function(Request $request, Response $response) {
        $id = $request->getAttribute('id');

        $sql = "SELECT * FROM customers WHERE id = $id";

        try {
            // Get DB object
            $db = new db();
            // connect
            $db = $db->connect();

            $stmt = $db->query($sql);

            $customer = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $db = null;

            echo json_encode($customer);
        } catch (PDOException $e) {
            echo '{"error" : {"text": '. $e->getMessage(). '}}';
        }

});

// Add customer
$app->post('/api/customers/add' , function(Request $request, Response $response) {
    $first_name = $request->getParam('first_name');
    $last_name = $request->getParam('last_name');
    $phone = $request->getParam('phone');
    $address = $request->getParam('address');
    $city = $request->getParam('city');
    $state = $request->getParam('state');
    $email = $request->getParam('email');

    $sql = "INSERT INTO customers (first_name, last_name, phone, address, city, state , email) VALUES
    (:first_name, :last_name, :phone, :address, :city, :state , :email)
";

    try {
        // Get DB object
        $db = new db();
        // connect
        $db = $db->connect();

        $stmt = $db->prepare($sql);

        $stmt->bindParam(':first_name' , $first_name);
        $stmt->bindParam(':last_name', $last_name);
        $stmt->bindParam(':phone' , $phone);
        $stmt->bindParam(':address' , $address);
        $stmt->bindParam(':city' , $city);
        $stmt->bindParam(':state' , $state);
        $stmt->bindParam(':email' , $email);

        $stmt->execute();
        echo '{"notice": {"text" : "Customer Added"}}';

    } catch (PDOException $e) {
        echo '{"error" : {"text": '. $e->getMessage(). '}}';
    }

});

// Update customer
$app->put('/api/customers/update/{id}' , function(Request $request, Response $response) {
    $id = $request->getAttribute('id');
    $first_name = $request->getParam('first_name');
    $last_name = $request->getParam('last_name');
    $phone = $request->getParam('phone');
    $address = $request->getParam('address');
    $city = $request->getParam('city');
    $state = $request->getParam('state');
    $email = $request->getParam('email');

    $sql = "UPDATE customers SET
                first_name  = :first_name,
                last_name   = :last_name,
                phone       = :phone,
                email       = :email,
                address     = :address,
                city        = :city,
                state       = :state 
            WHERE id = $id";

    try {
        // Get DB object
        $db = new db();
        // connect
        $db = $db->connect();

        $stmt = $db->prepare($sql);

        $stmt->bindParam(':first_name' , $first_name);
        $stmt->bindParam(':last_name', $last_name);
        $stmt->bindParam(':phone' , $phone);
        $stmt->bindParam(':address' , $address);
        $stmt->bindParam(':city' , $city);
        $stmt->bindParam(':state' , $state);
        $stmt->bindParam(':email' , $email);

        $stmt->execute();
        echo '{"notice": {"text" : "Customer Updated"}}';

    } catch (PDOException $e) {
        echo '{"error" : {"text": '. $e->getMessage(). '}}';
    }

});

// Delete single customer
$app->delete('/api/customers/delete/{id}' , function(Request $request, Response $response) {
    $id = $request->getAttribute('id');

    $sql = "DELETE FROM customers WHERE id = $id";

    try {
        // Get DB object
        $db = new db();
        // connect
        $db = $db->connect();

        $stmt = $db->prepare($sql);

        $stmt->execute();
        $db = null;
        echo '{"notice" : {"text" : "Customer Deleted"}}';
    } catch (PDOException $e) {
        echo '{"error" : {"text": '. $e->getMessage(). '}}';
    }

});

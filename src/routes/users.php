<?php
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

$app = new \Slim\App;


// Get All Users
$app->get('/api/users', function(Request $request, Response $response){
    $sql = "SELECT * FROM slimapp.users ";
    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();
        $stmt = $db->query($sql);
        $users = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo json_encode($users);
    } catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}';
    }
});

// Get Single User
$app->get('/api/users/{login}', function(Request $request, Response $response){
    $login = $request->getAttribute("login");
    $sql = "SELECT * FROM slimapp.users WHERE login = '$login' ";
    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();
        $stmt = $db->query($sql);
        $user = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo json_encode($user);
    } catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}';
    }
});


//Add User
$app->post('/api/users/add', function(Request $request, Response $response){
    $first_name = $request->getParam('first_name');
    $last_name = $request->getParam('last_name');
    $login = $request->getParam('login');
    $password = $request->getParam('password');

    
    $sql = "INSERT INTO slimapp.users (first_name,last_name,login,password) VALUES
    (:first_name,:last_name,:login,:password)";

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':first_name', $first_name);
        $stmt->bindParam(':last_name',  $last_name);
        $stmt->bindParam(':login',      $login);
        $stmt->bindParam(':password',      $password);

        $stmt->execute();

        echo '{"notice": {"text": "Customer Added"}';
    } catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}';
    }
});



// Update User
$app->put('/api/user/update/{login}', function(Request $request, Response $response){
    $login = $request->getAttribute('login');
    
    $first_name = $request->getParam('first_name');
    $last_name = $request->getParam('last_name');
    $login = $request->getParam('login');
    $password = $request->getParam('password');

    $sql = "UPDATE slimapp.users SET
				first_name 	= :first_name,
				last_name 	= :last_name,
                login		= :login,
                password		= :password

			WHERE login = '$login'";
    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();
        $stmt = $db->prepare($sql); 

        $stmt->bindParam(':first_name', $first_name);
        $stmt->bindParam(':last_name',  $last_name);
        $stmt->bindParam(':login',      $login);
        $stmt->bindParam(':password',    $password);

        $stmt->execute();

        echo '{"notice": {"text": "User Updated"}';
    } catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}';
    }
});


<?php
session_start();

class DatabaseConnector {
    private $host = "localhost";
    private $username = "root";
    private $password = "";
    private $database = "blueclipse";
    private $pdo;

    public function __construct($host, $username, $password, $database) {
        $this->host = $host;
        $this->username = $username;
        $this->password = $password;
        $this->database = $database;
    }

    public function connect() {
        try {
            $this->pdo = new PDO("mysql:host=$this->host;dbname=$this->database", $this->username, $this->password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }

    public function closeConnection() {
        $this->pdo = null;
    }

    public function executeQuery($query, $params = []) {
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function executeNonQuery($query, $params = []) {
        $stmt = $this->pdo->prepare($query);
        return $stmt->execute($params);
    }
}

class Login {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function login($email, $password) {
        // Prepare and execute the SQL query
        $stmt = $this-> db ->executeQuery("SELECT id, password, role FROM users WHERE email = ?", [$email]);
        $result = $stmt[0] ?? null;

        // Check if a matching user is found
        if ($result) {
            $stored_password = $result["password"];
            $user_id = $result["id"]; // Retrieve the user ID from the result
            $role = $result["role"]; // Retrieve the user role from the result

            // Verify the password using password_verify
            if (password_verify($password, $stored_password)) {
                // Password is correct

                // Store the user ID and role in the session
                $_SESSION['email'] = $email;
                $_SESSION['id'] = $user_id;
                $_SESSION['role'] = $role;
                header("Location: index.html");

                if ($role === "admin") {
                    // If the user is an admin, redirect to product_management.php
                    header("Location: product_management.php");
                    exit();
                } else {
                    // If the user is not an admin, redirect to the desired page
                    header("Location: index.html");
                    exit();
                }
            } else {
                header("Location: login_signup.html");
                return false;
            }
        } else {
            header("Location: login_signup.html");
            return false;
        }
    }
}

class Signup {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function signup($full_name, $phone_number, $email, $password) {
        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Prepare and execute the SQL query with additional fields
        $query = "INSERT INTO users (full_name, phone_number, email, password) VALUES (?, ?, ?, ?)";
        $success = $this->db->executeNonQuery($query, [$full_name, $phone_number, $email, $hashedPassword]);

        return $success;
    }
}

// Database connection configuration
$host = "localhost";
$username = "root";
$password = "";
$database = "blueclipse";

// Create a new DatabaseConnector instance
$db = new DatabaseConnector($host, $username, $password, $database);
$db->connect();

// Create instances of Login and Signup classes
$login = new Login($db);
$signup = new Signup($db);

// Check the form action
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["action"])) {
    $action = $_POST["action"];

    if ($action == "login") {
        // Handle login
        $email = $_POST["email"];
        $password = $_POST["password"];

        // Perform login
        $success = $login->login($email, $password);

        if ($success) {
            // Redirect to the desired page
            header("Location: Home _ Asyst international_E-commerce.html");
            exit();
        } else {
            // Invalid credentials
            echo "Invalid credentials";
        }
    } 
    elseif ($action == "signup") {
    
        // Retrieve form data from POST
        $full_name = $_POST["full_name"] ?? null;
        $phone_number = $_POST["phone_number"] ?? null;
        $email = $_POST["email"] ?? null;
        $password = $_POST["password"] ?? null;
    
        // Ensure all fields are provided
        if (!empty($full_name) && !empty($phone_number) && !empty($email) && !empty($password)) {
            // Perform signup with all 4 arguments
            $success = $signup->signup($full_name, $phone_number, $email, $password);
    
            if ($success) {
                // Redirect to the login page
                header("Location: login_signup.html");
                exit();
            } else {
                echo "Signup failed.";
            }
        } else {
            echo "Please fill in all required fields.";
        }
    }
    
}

// Close the database connection
$db->closeConnection();
?>

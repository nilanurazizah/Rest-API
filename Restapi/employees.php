<?php
 
    include('konek.php');

    $connection = getConnect(); 
    $request_method = $_SERVER['REQUEST_METHOD']; 

    switch ($request_method) {
       
        case "GET":
            // 
            if (!empty($_GET["id"])) {
                get_employee(intval($_GET["id"])); 
            } else {
                get_employee(); 
            }
            break;

        case 'POST':
            insert_employee();
            break;

        case 'PUT':
            $id = intval($_GET["id"]);
            update_employee($id); 
            break;

        case 'DELETE':
            $id = intval($_GET["id"]);
            delete_employe($id);
            break;

      default:
            header("HTTP/1.0 405 Method Not Allowed");
            break;
    }

    // function for get data employee
    function get_employee($id = 0)
    {
        global $connection; 

        $query = "SELECT * FROM tb_employee"; 

        if ($id != 0) {
            $query .= " WHERE tb_employee.id = $id;";        
        }
        $respons = array(); 
        $resultdata = mysqli_query($connection, $query); 

        while ($row = mysqli_fetch_assoc($resultdata)) { 
            $respons[] = $row;
        }
        header("Content-Type:application/json");
        echo json_encode($respons); 
    }

    function insert_employee()
    {
        global $connection;
        $data = json_decode(file_get_contents("php://input"), true); 
        $employeename = $data['employee_name']; 
        $employeesalary = $data['employee_salary'];
        $employeeage = $data['employee_age'];
        $query = "INSERT INTO `tb_employee` (`id`, `employee_name`, `employee_salary`, `employee_age`) 
        VALUES (NULL, '$employeename', '$employeesalary', '$employeeage')";

        if (mysqli_query($connection, $query)) {
            $respons = array('status' => 1, 
            'status_message' => 'Employee Added Succesfully');
        } else {
            $respons = array('status' => 0,
            'status_message' => 'Employee Added Failed');
        }
        header('Content-Type:application/json');
        echo json_encode($respons);
    }


    function update_employee($id){
        global $connection;

        $post_vars = json_decode(file_get_contents("php://input"), true);
        $employee_name = $post_vars["employee_name"];
        $employee_salary = $post_vars["employee_salary"];
        $employee_age = $post_vars["employee_age"];
        $query = "UPDATE tb_employee SET
                  employee_name='".$employee_name."',
                  employee_salary='".$employee_salary."',
                  employee_age='".$employee_age."' 
                  WHERE id=".$id;
        
        if (mysqli_query($connection, $query)) 
        {
            $response=array(
                'status' =>1,
                'status_message' =>'Employee Updated Successfully.'
            );
        }
        else {
            $response=array(
            'status' => 0,
            'status_message' =>'Employee Updation Failed.'
            );
        }
        header('Content-Type: application/json');
        echo json_encode($response);
    }


    function delete_employe($id) 
    {
        global $connection;
        $query = "DELETE FROM `tb_employee` WHERE `tb_employee`.`id` = $id";
        if (mysqli_query($connection, $query)) {
            $respons = array('status' => 1, 
            'status_message' => 'Employee Delete Succesfully');
        } else {
            $respons = array('status' => 0, 
            'status_message' => 'Employee Delete Failed');
        }
        header("Content-Type:application/json");
        echo json_encode($respons);
    }
    
?>
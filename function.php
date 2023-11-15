<?php session_start();


// FUNCTION TO ESTABLISH THE LINK WITH THE DATABASE
function connect(){
    $servername = 'localhost';
    $username = 'root';
    $password = '';
    $dbname = "conciergerie_immeuble";

    try{
        $pdo = new PDO("mysql:host=$servername; dbname=$dbname", $username, $password);
        return $pdo;
    } catch (PDOException $e){
        die($e -> getMessage());
    }
};

function checkConnexion(){
    connect();
    if(isset($_SESSION['concierge_connected'])){
        echo 'Bonjour '.$_SESSION['firstname'].' '.$_SESSION['lastname'].'';
    } else {
        header("Location: ./php/login.php");
    }
}

// FUNCTION TO FETCH ALL MY DATABASE ELEMENTS
function allTasks(){
    $sql = connect()->prepare("SELECT * FROM intervention ORDER BY date_inter");
    $sql->execute();
    $allTasks = $sql->fetchAll(PDO::FETCH_ASSOC);

    return $allTasks;
};
// FUNCTION TO INSERT A NEW LINE IN MY TABLE
function insertTask(){
    // Securing my input fields
    $type_inter = strip_tags($_POST["type_inter"]);
    $date_inter = strip_tags($_POST["date_inter"]);
    $etage_inter = strip_tags($_POST["etage_inter"]);
    $insertTask = connect()->prepare("INSERT INTO intervention(type_inter, date_inter, etage_inter) VALUES (:type_inter, :date_inter, :etage_inter)");
    $insertTask->bindValue(':type_inter', $type_inter, PDO::PARAM_STR);
    $insertTask->bindValue(':date_inter', $date_inter, PDO::PARAM_STR);
    $insertTask->bindValue(':etage_inter', $etage_inter, PDO::PARAM_STR);
    $insertTask->execute();
    header("Refresh:0; url=index.php");
};
// CHECKING IF THE INPUT IN MY FORM HAS BEEN TRIGGERED SO I CAN USE MY FUNCTION TO INSERT
if(isset($_POST["submit_task"])){
    insertTask();
};

// I USE MY FUNCTION PREVIOUSLY DEFINED TO MAKE THE REQUEST
function displayLines($interventions){
    foreach($interventions as $key => $intervention){

        echo "<tr><td>".($key+1)."</td>" ;
        echo "<td>".$intervention['type_inter']."</td>" ;
        echo "<td>".$intervention['date_inter']."</td>" ;
        echo "<td>".$intervention['etage_inter']."</td>";
        echo '<td>
        <form method="GET" action=\'\'>
            <input type="submit" class="btn btn-danger" value="Supprimer">
            <input type="hidden" name="supprId" value="'.$intervention["id_inter"].'"></form>
        </td> ';
        echo "<td><a href='?action=modifier&id=".$intervention['id_inter']."&date=".$intervention['date_inter']."&etage=".$intervention['etage_inter']."&intervention=".$intervention['type_inter']."' class='btn btn-warning'>Modifier</a>
        </form>
            </tr> ";  
    };
};

function deleteLine(){
    $del = connect()->prepare("DELETE FROM intervention WHERE id_inter = :id");
    $del->bindParam(':id', $_GET['supprId']);
    $del-> execute();
    
};

// If supprId is set I execute the deleteLine function
if(isset($_GET['supprId'])){
    deleteLine();
}



function updateLine(){
    $modifier = connect()->prepare('UPDATE intervention SET date_inter = :date, etage_inter=:etage, type_inter=:intervention WHERE id_inter = :id');
    $modifier->bindParam(':date', $_GET['date']);
    $modifier->bindParam(':etage', $_GET['etage']);
    $modifier->bindParam(':intervention', $_GET['intervention']);
    $modifier->bindParam(':id', $_GET['id']);
    $modifier->execute();
    echo "<script>window.location = './index.php';</script>";
    
 };
 
 function selectLine($type, $date, $etage){
     $select = "SELECT * FROM intervention WHERE 1=1";
     $param = array();
     if(!empty($type)){
        
         $select .= " && type_inter = ?";
         array_push($param, $type);
       
     };
     if(!empty($date)){
        
        $select .= " && date_inter = ?";
        array_push($param, $date);
    
     };
     if(!empty($etage)){
        
        $select .= " && etage_inter = ?";
        array_push($param, $etage);
     };
     $search = connect()->prepare($select);
     $search->execute($param);
     $result = $search->fetchAll(PDO::FETCH_ASSOC);

     return($result);   
 };

?>
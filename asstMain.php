<!-- http://localhost/ThompsonToriCodingAsst/asstmain.php -->
<?php
require_once("asstInclude.php");
require_once("clsDeleteSunglassRecord.php");

function displayMainForm()
    {
        echo"<form action = ? method=post>";
        echo"<div class = \"buttons\">";
        displayButton("f_CreateTable", "Create Table", 
                    "./createtable.png", "Create");
        displayButton("f_AddRecord", "Add Record", "./addrecord.png", "Add");
        echo"</div>";
        echo"<div class = \"buttons\">";
        displayButton("f_DeleteRecord", "Delete Record", 
                    "./deleterecord.png", "Delete");  
        displayButton("f_DisplayData", "Display Data", 
                    "./displaydata.png", "Display");
        echo"</div>";
        echo"</form>";
    }
    function dropTable(&$mysqlObj, $tableName)
    {
        $success = $mysqlObj->prepare("drop table if exists $tableName;");
        return $success;
    }
    function createTable(&$mysqlObj, $sqlCreate, $tableName)
    {
        $success = $mysqlObj->prepare($sqlCreate);
        if($success)
            echo "Table " . $tableName . " created.<br>";
        else
            "Unable to create table" . $tableName;
        return $success;
    }
    function createTableForm(&$mysqlObj, $tableName)
    {
        echo"
        <form action = ? method = post>";
        $stmtObj = dropTable($mysqlObj, $tableName);
        if($stmtObj)
            $stmtObj->execute();
        $sqlCreate = "create table $tableName(BrandName varchar(20) PRIMARY 
                KEY, DateManufactured date, CameraMP int, Colour varchar(8))";
        $stmtObj = createTable($mysqlObj, $sqlCreate, $tableName);
        if($stmtObj)
            $stmtObj->execute();
        $stmtObj->close();
        displayButton("f_Home", "Home", "./home.png", "Home");
        echo"</form>";
    }
    function addRecordForm(&$mysqlObj, $tableName)
    {
        echo"<form action = ? method=post>";
        echo"<div class = \"DataPair\">";
        displayLabel("Brand Name ");
        displayTextbox("text", "f_brandName", 20);
        echo"</div>";
        echo"<div class = \"DataPair\">";
        displayLabel("Date Manufactured ");
        displayTextbox("date","f_dateManufactured", 5, date('y-m-d'));
        echo"</div>";
        echo"<div class = \"DataPair\">";
        displayLabel("Camera: ");
        displayLabel("5MP");
        echo "<input type = radio name = f_camera value = 5 checked>";
        displayLabel("10MP");
        echo "<input type = radio name = f_camera value = 10>";
        echo"</div>";
        echo"<div class = \"DataPair\">";
        displayLabel("Colour: ");
        displayTextbox("color","f_colour", 7 , "#1423F0");
        echo"</div>";
        echo"<div class = \"DataPair\">";
        displayButton("f_Save", "Save", "./saverecord.png", "Save");
        echo"<div class = \"buttons\">";
        displayButton("f_Home", "Home", "./home.png", "Home");
        echo"</div>";
        echo "</form>";
    }
    function saveRecordtoTableForm(&$mysqlObj, $tableName)
    {
        echo"<form action = ? method=post>";
        $brandName = $_POST["f_brandName"];
        $dateManufactured = $_POST["f_dateManufactured"];
        $camera = $_POST["f_camera"];
        $colour = $_POST["f_colour"];
        $stmtObj = $mysqlObj->prepare("insert into $tableName(BrandName, 
                        DateManufactured, CameraMP, Colour) Values(?,?,?,?);");
        $bindSuccess = $stmtObj->bind_param("ssis", $brandName, 
                        $dateManufactured,$camera, $colour);
        if($bindSuccess)
            $success = $stmtObj->execute();
        else
            echo "Bind Failed: " . $stmtObj->error;
        if($success)
            echo "Record successfully added to $tableName";
        else
            echo "Unable to add record to $tableName";
        $stmtObj->close();
        echo "<br>";
        displayButton("f_Home", "Home", "./home.png", "Home");
        echo"</form>";
    }
    function displayDataForm(&$mysqlObj, $tableName)
    {
        echo"<form action = ? method=post>";
        $stmtObj = $mysqlObj->prepare("select BrandName, DateManufactured, 
                                    CameraMP, Colour from $tableName order by BrandName;");
        $stmtObj->execute();
        $stmtObj->bind_result($brandName, $dateManufactured, $cameraMP, 
                            $colour);

        echo"
        <table>
        <tr>
            <th>Brand Name</th>
            <th>Date Manufactured</th>
            <th>Camera MP</th>
            <th>Colour</th>
        </tr>";
        while($stmtObj->fetch()) {
            echo" 
            <tr>
            <td>$brandName</td>
            <td>$dateManufactured</td>
            <td>$cameraMP</td>
            <td><input type = color name = color value = $colour disabled></td>
            </tr>";
        };
        echo"</table>";
        $stmtObj->close();
        displayButton("f_Home", "Home", "./home.png", "Home");
        echo"</form>";
    }
    function deleteRecordForm(&$mysqlObj, $tableName)
    {
        echo"<form action = ? method=post>";
        echo"<div class = \"DataPair\">";
        displayLabel("Choose brand name to delete: ");
        displayTextbox("text", "f_brandName", 20);
        echo"</div>";
        displayLabel("Deletion is final!");
        echo "<br>";
        displayButton("f_IssueDelete", "Delete", "./delete.png", "Delete");
        echo"<div class = \"buttons\">";
        displayButton("f_Home", "Home", "./home.png", "Home");
        echo"</div>";
        echo"</form>";
    }
    function issueDeleteForm(&$mysqlObj, $tableName)
    {
        echo"<form action = ? method=post>";
        $brandName = $_POST["f_brandName"];
        $deleteRecord = new clsDeleteSunglassRecord();
        $success = $deleteRecord->deleteTheRecord($mysqlObj, $tableName,
                                                $brandName);
        if($success > 0)
            echo $success . " records removed.";
        else
            echo $brandName . " record does not exist.";
        echo "<br>";
        displayButton("f_Home", "Home", "./home.png", "Home");
        echo"</form>";
    }

    // main
    date_default_timezone_set ('America/Toronto');
    $mysqlObj = createConnectionObject(); 
    $tableName = "Sunglasses"; 
    writeHeaders("Bluetooth Smart Sunglasses", "Assignment 1");
    if (isset($_POST['f_CreateTable']))
    createTableForm($mysqlObj,$tableName);
    else if (isset($_POST['f_Save'])) 
                        saveRecordtoTableForm($mysqlObj,$tableName);
    else if (isset($_POST['f_AddRecord'])) addRecordForm($mysqlObj,$tableName);
        else if (isset($_POST['f_DeleteRecord'])) deleteRecordForm
                                            ($mysqlObj,$tableName) ;	 
            else if (isset($_POST['f_DisplayData'])) displayDataForm 
                                            ($mysqlObj,$tableName);
            else if (isset($_POST['f_IssueDelete'])) issueDeleteForm 
                                            ($mysqlObj,$tableName);
                else displayMainForm();
    if (isset($mysqlObj)) $mysqlObj->close();
    writeFooters();
?>
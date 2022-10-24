<?php 
function writeHeaders($Heading="Welcome",$TitleBar="MySite")
{
    echo "
        <!doctype html> 
        <html lang = \"en\">
        <head>
            <meta charset = \"UTF-8\">
            <title>$TitleBar</title>\n
            <link rel =\"stylesheet\" type = \"text/css\" 
                                    href=\"asstStyle.css\"/>

        </head>
        <body>\n  
	    <h1>$Heading - Tori Thompson</h1>";
        
}
function writeFooters()
{
    echo "</body>"; 
    echo"<footer>";
    displayContactInfo();
    echo"</footer>";
    echo "</html>";  
}
function displayLabel($label = "Label")
{
    echo"<label>$label</label>";
}
function displayTextbox($type, $name, $size, $value = "")
{
    echo "<input type = $type name = $name size = $size value = $value>";
}
function displayContactInfo()
{
    echo"Questions? Comments? Contact me at 
    <a href=\"mailto:tori.thompson@student.sl.on.ca\">
    tori.thompson@student.sl.on.ca</a>";
}
function displayImage($filename, $alt, $height = 40, $width = 110)
{
    echo"<img src = $filename height = $height width = $width alt= $alt>";
}
function displayButton($name, $text, $filename = "", $alt = "")
{
    if (!$filename == "")
    {
        echo"<button type = Submit name = $name>";
        displayImage($filename, $alt);
        echo" </button>";
    }
    else 
    {
        echo"<button type=Submit name= $name>$text</button>";
    }
}
function createConnectionObject()
{
    $fh = fopen("auth.txt", "r");
    $Host = trim(fgets($fh));
    $UserName = trim(fgets($fh));
    $Password = trim(fgets($fh));
    $Database = trim(fgets($fh));
    $Port = trim(fgets($fh));
    fclose($fh);
    $mysqlObj = new mysqli($Host, $UserName, $Password, $Database, $Port);
    if($mysqlObj->connect_errno != 0)
    {
        echo "<p>Connection failed. Unable to open database $Database. Error: "
        . $mysqlObj->connect_error . "</p>";
        exit;
    }
    return ($mysqlObj);
}

?>
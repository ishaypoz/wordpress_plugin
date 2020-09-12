<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>PHP</title>
</head>
<body>

    <form action="handle_form.php" method="POST" id="example_form">
        <label for="username">Username</label>
        <input type="text" name="username" id="username">
        <label for="favorite_number">Favorite number</label>
        <input type="number" name="favorite_number" id="favorite_number">
        <input type="submit" value="Skicka">
    </form>

    <script src="main.js"></script>
</body>
<script>
const form = document.getElementById('example_form');
form.addEventListener('click', function(event){
    event.preventDefault();
    const formattedFormData = new FormData(form);
    //add more data if we want
    formattedFormData.append('property', 'value');
    fetchData(formattedFormData);
});
async function postData(formattedFormData){
esponse = await fetch('handle_form.php',{
        method: 'POST',
        body: formattedFormData
    });
    const data = await response.text();
    console.log(data);
}
</script>
</html>


<?php
// Inside of `handle_form.php`
echo $_POST["username"];
echo $_POST["favorite_number"];
echo $_GET["name"];
echo $_GET["favorite_color"];
?>
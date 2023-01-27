<?php
    session_start();
    require_once('func.php');
    require_once('db.php');
    require_once('user.php');
    
    if(!findInSession('user')){
        $_SESSION['error'] = 'Вы незалогинены';
        header('location:index.php');
    }

    try {
    $user = Person::loadFromDb(findInSession('user')['username']);
    } catch(\Exception $e) {
        $_SESSION['error'] = 'Пользователь удалён!';
        header('Location: logout.php');
    }

    if(isset($_POST['user'])){
        if(isset($_FILES['user'])){
            ImageService::uploadImage($_FILES['user'], $user);
            $_POST['user']['image'] = $filename;
        }
    $user
        ->edit($_POST['user'])
        ->save()
    ;
        header('Location: profile.php');
    }

function updateFile(array $file) : string
{
    $fileName = date('Y-m-d_H-i-s') . '.' . pathinfo($file['name']['image'], PATHINFO_EXTENSION);
    rename($file['tmp_name']['image'], 'C:\\xampp\\htdocs\\img\\' . $fileName);
    return $fileName;
}


function editUser($user, $formUser)
{
    $id = $user['id'];
    $user = array_diff($formUser, $user);
    if (isset($user['password'])) {
        $user['password'] = crypt($user['password'], 'randomSalt');
    }
    if(!$user){
        return;
    }
    // foreach($user as $column => $value){
    //     if(array_key_exists($column, $formUser)){
    //         if ($formUser["$column"] == ) {
    //             unset($formUser["$column"]);
    //         }
    //     }
    // }
    //     updateUser($formUser, $user['id']);
    // }
    updateUser($user);
}
    

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <title>STEP</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" 
	integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
</head>
<body>
<div class="container">
    <div class="col-sm">

	    <div class="error">
		    <span>
			    <?php echo findAndDelete('error')?>
		    </span>
	    </div>
        <img src="<?= $user->getImage();?>" style="height: 200px; width: 200px;">

        <form method="POST" enctype="multipart/form-data">
            <input class = "form-control" name="user[username]" type="text" placeholder="username" value="<?= $user->getUsername();?>">
            <input class = "form-control" name="user[password]" type="password" placeholder="password">
            <input class = "form-control" name="user[email]" type="text" placeholder="Email" value="<?= $user->email;?>">
            <input class = "form-control" name="user[location]" type="text" placeholder="Location" value="<?= $user->location;?>">
            <input class = "form-control" name="user[link]" type="text" placeholder="Link" value="<?= $user->link;?>">
            <input class = "form-control" name="user[image]" type="file" placeholder="Image" >
            <input class = "btn btn-primary" type="submit" value="Save">
            <input class = "btn btn-primary" formaction="index.php" type="submit" value="Back">
            <a class="btn btn-danger" id="delete_user" href="delete_user.php">Del</a>
        </form>
    </div>
    <div class="col-sm">
        <?php if(findInSession('user')) {?>
        <span><?= 'Username: ' . findInSession('user')['username']; ?></span>
        <a href="logout.php" class="btn btn-danger">logout</a>
        <?php }?>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" 
integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
<script lang="javascript">
    let btn = document.getElementById('delete_user');
    btn.addEventListener('click', function(e){
        let res = confirm('Удалить пользователя?');
        if(!$res){
            window.location = btn.getAttribute('href');
        }
    });
</script>
</body>
</html>
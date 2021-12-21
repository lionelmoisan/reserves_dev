<?php require 'includes/includes_back.php';

require_once 'Classes/Email.php';
require_once 'Classes/class.phpmailer.php';

require_once 'Classes/Notifications.php';

/*---- Tous les champs sont renseignées ----*/
if(!empty($_POST)){
    
    $id_reserve=$_POST['id_reserve'];
    $id_lot=$_POST['lot'];
    $description=Chaines::trt_insert_string($_POST['description']);
    $piece=Chaines::trt_insert_string($_POST['piece']);
    $type=$_POST['type'];
    $image_action=$_POST['image_action'];

    // gestion des images associées à la réserve

    if (!empty($_FILES['image_1']['name'])) {

        $info_upload=Reserve::trtuploadfile($_FILES['image_1'],"img_reserves");

        if (!is_null($info_upload['message'])) {
            $_SESSION['erreur']=$info_upload['message'];
            header('location:index.php');
            exit();
        } else {
            // Pour l'update des images
            if ($image_action=="add") {
                $data_image_1 = array(
                    'id_reserve' => $id_reserve,
                    'description'=> "image 1",
                    'URL'=>$info_upload['nom_image']
                );
                // Pour l'ajout des images
            } else {

                $data_image_1 = array(
                    'id' => $_POST['id_ri_photo_1'],
                    'URL'=>$info_upload['nom_image']
                );
            }
        }

    }

    if (!empty($_FILES['image_2']['name'])) {

        $info_upload=Reserve::trtuploadfile($_FILES['image_2'],"img_reserves");

        if (!is_null($info_upload['message'])) {
            $_SESSION['erreur']=$info_upload['message'];
            header('location:index.php');
            exit();
        } else {

            if ($image_action=="add") {

                $data_image_2 = array(
                    'id_reserve' => $id_reserve,
                    'description'=> "image 2",
                    'URL'=>$info_upload['nom_image']
                );

            } else {

                $data_image_2 = array(
                    'id' => $_POST['id_ri_photo_2'],
                    'URL' => $info_upload['nom_image']
                );

            }
        }
    }
    

    $info_lot=lot::getLotById($DB,$id_lot);

    //print_r($info_lot);
    if (empty($_POST['datesignalement'])) {

        $date_signalement=Db::convertDate($info_lot->l_date_livraison);

    } else {
        $date_signalement=Db::convertDate($_POST['datesignalement']);
    }

    $id_entreprise=$_POST['entreprise_list'];

    $date_jour = date("Y-m-d H:i:s");
    
    $data= array(
        'id_reserve'=>$id_reserve,
        'id_lot'=>$id_lot,
        'description'=>$description,
        'piece'=>$piece,
        'type'=>$type,
        'date_signalement'=>$date_signalement,
        'date_modifier'=>$date_jour,
        'id_entreprise'=>$id_entreprise,
    );

    //var_dump($data);

    $UpdateReserve=Reserve::UppReserve($DB,$data);

    //$info_reserve=Reserve::LastIdreserve($DB);

    if (!is_null($data_image_1)) {
        //var_dump($data_image_1);
        if ($image_action=="add") {
            $add_res_image=Reserve::AddResImage($DB,$data_image_1);

        } else {
            // Mise à jour de l'image
            $upp_res_image=Reserve::UppResImage($DB,$data_image_1);
        }
    }

    if (!is_null($data_image_2)) {
        //var_dump($data_image_2);
        // Mise à jour de l'image
        if ($image_action=="add") {
            $add_res_image=Reserve::AddResImage($DB,$data_image_2);
        } else {
            // Mise à jour de l'image
            $upp_res_image=Reserve::UppResImage($DB,$data_image_2);
        }
    }
    $_SESSION['message']="La réserve a été mise à jour";
    header('location:index.php');
}
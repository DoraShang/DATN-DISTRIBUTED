<?php 
    // if(isset($is_mobile) && $is_mobile == 1)
    //     echo View::element('header_m'); 
    // else
    if($current_category['Category']['id'] == 85 || $current_category['Category']['id'] == 86 || $current_category['Category']['id'] == 87){
        echo View::element('header_landing'); 
    }else{
        echo View::element('header');
    }
?>

<?php echo $content_for_layout; ?>

<?php 
    // if(isset($is_mobile) && $is_mobile == 1)
    //     echo View::element('footer_m'); 
    // else
    if($current_category['Category']['id'] == 85 || $current_category['Category']['id'] == 86 || $current_category['Category']['id'] == 87){
        echo View::element('footer_landing'); 
    }else{
        echo View::element('footer');
    }
?>
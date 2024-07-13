<?php
// if(isset($is_mobile) && $is_mobile == 1)
//     echo View::element('header_m', array('is_cart', 1)); 
// else
echo View::element('header', array('is_cart', 1));
?>

<?php echo $content_for_layout; ?>

<?php
// if(isset($is_mobile) && $is_mobile == 1)
//     echo View::element('footer_m'); 
// else
echo View::element('footer');
?>

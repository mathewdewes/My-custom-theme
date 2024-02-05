<?php
require_once 'gmail-api.php';


add_action( 'wp_enqueue_scripts', 'my_theme_enqueue_styles' );
function my_theme_enqueue_styles() {
    $parenthandle = 'parent-style'; // This is 'twentyfifteen-style' for the Twenty Fifteen theme.
    $theme        = wp_get_theme();
    wp_enqueue_style( $parenthandle,
        get_template_directory_uri() . '/style.css',
        array(),  // If the parent theme code has a dependency, copy it to here.
        $theme->parent()->get( 'Version' )
    );
    wp_enqueue_style( 'child-style',
        get_stylesheet_uri(),
        array( $parenthandle ),
        $theme->get( 'Version' ) // This only works if you have Version defined in the style header.
    );
}

//add_action('init','process_form_submission');
function process_form_submission(){
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Process form data
        $email = sanitize_email($_POST['email']);
        $date = $_POST['date'];
        $service = $_POST['service_type'];
        $name = $_POST["name"];
        $phone = $_POST["phone"];
        
        get_google_client_and_service($email, $date, $service, $name, $phone);   
        
    }
}
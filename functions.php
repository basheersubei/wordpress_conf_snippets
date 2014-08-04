// place these in functions.php


    // redirect all non-admins to home page
    function soi_login_redirect($redirect_to, $request, $user)
    {
        return (is_array($user->roles) && in_array('administrator', $user->roles)) ? admin_url() : site_url();
    }
    add_filter('login_redirect', 'soi_login_redirect', 10, 3);
     
     
    // create new user account (if doesn't already exist) upon event registration completion
    // code taken from https://gist.github.com/sidharrell/7455060#file-espresso_create_wp_user-php	
    // also taken from http://wordpress.stackexchange.com/questions/4725/how-to-change-a-users-role
    add_action('action_hook_espresso_save_attendee_data','espresso_create_wp_user', 10, 1);
    function espresso_create_wp_user($attendee_data) {
        if( email_exists( $attendee_data['email'] ) == false ) {
                    global $org_options;   
                   
                    // Generate the password and create the user
                    $password = wp_generate_password( 12, false );
                    $user_id = wp_create_user( $attendee_data['email'], $password, $attendee_data['email'] );
           
                    // Set the users details
                    //Additional fields can be found here: http://codex.wordpress.org/Function_Reference/wp_update_user
                    wp_update_user(
                            array(
                                    'ID'                    => $user_id,
                                    'nickname'              => $attendee_data['fname'] . ' ' . $attendee_data['lname'],
                                    'display_name'  => $attendee_data['fname'] . ' ' . $attendee_data['lname'],
                                    'first_name'    => $attendee_data['fname'],
                                    'last_name'             => $attendee_data['lname'],
                                    'description'   => __('Registered via event registration form.', 'event_espresso'),
                            )
                    );
                   
                    add_user_meta( $user_id, 'event_espresso_address', $attendee_data['address']);
                    add_user_meta( $user_id, 'event_espresso_address2', $attendee_data['address2']);
                    add_user_meta( $user_id, 'event_espresso_city', $attendee_data['city']);
                    add_user_meta( $user_id, 'event_espresso_state', $attendee_data['state']);
                    add_user_meta( $user_id, 'event_espresso_zip', $attendee_data['zip']);
                    add_user_meta( $user_id, 'event_espresso_country', $attendee_data['country_id']);
                    add_user_meta( $user_id, 'event_espresso_phone', $attendee_data['phone']);
           
                    // Set the role
                    $user = new WP_User( $user_id );
                    $user->set_role( 'abstract' );
           
                    // Email the user
                    wp_mail( $attendee_data['email'], 'Welcome to ' . $org_options['organization'], 'Your Username: ' .$attendee_data['email']. ' Your Password: ' . $password );
           
            } else {
                    $user = get_user_by( 'email', $attendee_data['email'] );
                    $user->remove_role( 'subscriber' );
                    $user->add_role( 'abstract' );
            }// end if
    }



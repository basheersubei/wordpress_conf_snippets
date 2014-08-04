// place these at the end of page.php
   //  hides abstract submission link for non-abstract users and hides event registration link for already registered users
   <?php if ( current_user_can('abstract') ) {
 			    if(is_page( 'Abstract Submission' )) :
                                        echo do_shortcode("[wpabstracts]");
                                endif;
                            echo '<style type="text/css">
                                #menu-item-38 {
                                    display: none;
                                }
                                </style>';
                        } else {
                            echo '<style type="text/css">
                                #menu-item-39 {
                                    display: none;
                                }
                                </style>';
 
                        } ?>

// place these in line 27 of page.php
			// displays abstract submission form if user has "abstract" role
                            <?php if(is_page( 'Abstract Submission' )) :
                                    if ( current_user_can('abstract') ) :
                                            echo do_shortcode("[wpabstracts]");
                                    endif;
                            endif; ?>



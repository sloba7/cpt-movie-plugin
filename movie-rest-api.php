<?php
function movie_rest_api_init() {
    register_rest_route( 'movie/q', '/movies', array(
        'methods' => 'GET',
        'callback' => 'movie_rest_api_get_movies',
    ) );
}
add_action( 'rest_api_init', 'movie_rest_api_init' );

function movie_rest_api_get_movies( $data ) {
    $movies = get_posts( array(
        'post_type' => 'movie',
        'posts_per_page' => -1,
    ) );

    $results = array();

    foreach ( $movies as $movie ) {
        $movie_title = get_post_meta( $movie->ID, 'movie_title', true );
        $results[] = array(
            'id' => $movie->ID,
            'title' => $movie->post_title,
            'content' => $movie->post_content,
            'movie_title' => $movie_title,
        );
    } 

    return $results;
}

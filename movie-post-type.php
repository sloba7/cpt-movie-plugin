<?php
function create_movie_post_type() {
    register_post_type( 'movie',
        array(
            'labels' => array(
                'name' => __( 'Movies' ),
                'singular_name' => __( 'Movie' )
            ),
            'public' => true,
            'has_archive' => true,
            'show_ui' => true,
            'menu_icon' => 'dashicons-video-alt3',
            'menu_position' => 5,
            'capability_type' => 'post',
            'supports' => array( 'title', 'editor', 'thumbnail' ),
            'show_in_rest' => true,
        )
    );
}
add_action( 'init', 'create_movie_post_type' );

function movie_title_meta_box() {
    add_meta_box(
        'movie_title_meta_box',
        __( 'Movie Title', 'textdomain' ),
        'movie_title_meta_box_callback',
        'movie'
    );
}
add_action( 'add_meta_boxes', 'movie_title_meta_box' );

function movie_title_meta_box_callback( $post ) {
    wp_nonce_field( basename( __FILE__ ), 'movie_title_meta_box_nonce' );

    $movie_title = get_post_meta( $post->ID, 'movie_title', true );

    echo '<label for="movie_title">';
    _e( 'Movie Title', 'textdomain' );
    echo '</label> ';
    echo '<input type="text" id="movie_title" name="movie_title" value="' . esc_attr( $movie_title ) . '" size="25" />';
}

function save_movie_title_meta_box_data( $post_id ) {
    if ( ! isset( $_POST['movie_title_meta_box_nonce'] ) ) {
        return;
    }

    if ( ! wp_verify_nonce( $_POST['movie_title_meta_box_nonce'], basename( __FILE__ ) ) ) {
        return;
    }

    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    if ( isset( $_POST['movie_title'] ) ) {
        update_post_meta( $post_id, 'movie_title', sanitize_text_field( $_POST['movie_title'] ) );
    }
}
add_action( 'save_post_movie', 'save_movie_title_meta_box_data' );

function get_movie_title( $post ) {
    return get_post_meta( $post['id'], 'movie_title', true );
}

function update_movie_title( $value, $post, $field ) {
    update_post_meta( $post->ID, 'movie_title', $value );
}

function register_movie_title_field() {
    register_rest_field( 'movie',
        'movie_title',
        array(
            'get_callback'    => 'get_movie_title',
            'update_callback' => 'update_movie_title',
            'schema'          => array(
                'type'        => 'string',
                'description' => 'Movie Title',
                'context'     => array( 'view', 'edit' ),
            ),
        )
    );
}
add_action( 'rest_api_init', 'register_movie_title_field' );



add_action( 'enqueue_block_editor_assets', 'movie_enqueue_block_editor_assets' );

function movie_enqueue_block_editor_assets() {
    wp_enqueue_script(
        'movie-blocks',
        plugins_url( 'movie-blocks.js', __FILE__ ),
        array( 'wp-blocks', 'wp-components' ),
        filemtime( plugin_dir_path( __FILE__ ) . 'movie-blocks.js' )
    );
}
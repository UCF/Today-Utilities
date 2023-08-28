<?php
/**
 * Hooks for modifying the ucf_resource_link post type
 */

/**
 * Adds post_tag to the ucf_resource_link
 * taxonomy array.
 * @author Jim Barnes
 * @since 1.4.0
 * 
 * @param array $taxonomies The array of incoming taxonomies.
 * @return array The modified taxonomies array.
 */
function tu_resource_link_taxonomies( $taxonomies ) {
    if ( ! in_array( 'post_tag', $taxonomies ) ) {
        $taxonomies[] = 'post_tag';
    }

    return $taxonomies;
}

add_filter( 'resource_link_taxonomies', 'tu_resource_link_taxonomies', 10, 1 );
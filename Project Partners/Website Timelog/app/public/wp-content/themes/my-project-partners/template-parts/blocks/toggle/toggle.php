<?php

/**
 * A Toggle Block Template
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'toggle-' . $block['id'];
if (!empty($block['anchor'])) {
    $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$className = 'toggle';
if (!empty($block['className'])) {
    $className .= ' ' . $block['className'];
}
if (!empty($block['align'])) {
    $className .= ' align' . $block['align'];
}

$title = get_field('title');
$content = get_field('content');

?>

<div id="<?php echo esc_attr($id); ?>" class="mt-2 mb-2 card <?php echo esc_attr($className); ?>">
    <div class="toggle-trigger" role="button" aria-pressed="false" aria-expanded="false" onclick="toggleExpand(this)">
        <h4 class="mt-0 mb-0 h5"><?php echo esc_html($title); ?></h4><span class="toggle-icon"></span>
    </div>
    <div class="toggle-content"><?php echo wp_kses($content, 'post'); ?></div>
</div>
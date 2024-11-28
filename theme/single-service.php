<?php get_header(); ?>

<main class="single-service">
  <div class="container">
    <?php if (have_posts()):
      while (have_posts()):
        the_post(); ?>
        <div id="service-<?php the_ID(); ?>" <?php post_class(); ?>>
          <!-- Заголовок -->
          <div class="service-header">
            <h1 class="service-title"><?php the_title(); ?></h1>
            <div class="service-meta">
              <span class="service-date">Опубликовано: <?php echo get_the_date(); ?></span>
              <?php if (has_category()): ?>
                <span class="service-category">Категория: <?php the_category(', '); ?></span>
              <?php endif; ?>
            </div>
          </div>

          <!-- Цена -->
          <div class="service-price">
            <?php
            $price = get_post_meta(get_the_ID(), 'price', true);
            if ($price) {
              echo '<p><strong>Цена услуги:</strong> ' . esc_html($price) . ' руб.</p>';
            }
            ?>
          </div>

          <!-- Метки -->
          <div class="service-tags">
            <?php
            $tags = get_the_tags(get_the_ID());
            if ($tags) {
              echo '<ul class="tags">';
              foreach ($tags as $tag) {
                echo '<li class="tag"><span>' . esc_html($tag->name) . '</span></li>';
              }
              echo '</ul>';
            }
            ?>
          </div>

          <!-- Содержимое -->
          <div class="service-content">
            <?php the_content(); ?>
          </div>
          </d>
        <?php endwhile; else: ?>
        <p>Услуга не найден.</p>
      <?php endif; ?>
    </div>

    <?php
    // Если комментарии разрешены для поста
    if (comments_open() || get_comments_number()):
      comments_template();
    endif;
    ?>

</main>

<?php get_footer(); ?>
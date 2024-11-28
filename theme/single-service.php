<?php get_header(); ?>

<main class="single-service">
  <div class="container">
    <?php if (have_posts()):
      while (have_posts()):
        the_post(); ?>
        <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
          <!-- Заголовок и метки -->
          <div class="service-header">
            <h1 class="service-title"><?php the_title(); ?></h1>
            <div class="service-tags">
              <?php
              $tags = get_the_tags();
              if ($tags) {
                foreach ($tags as $tag) {
                  echo '<span class="service-tag">' . esc_html($tag->name) . '</span>';
                }
              }
              ?>
            </div>
          </div>

          <!-- Превью изображения -->
          <?php if (has_post_thumbnail()): ?>
            <div class="service-thumbnail">
              <?php the_post_thumbnail('large', ['class' => 'service-image']); ?>
            </div>
          <?php endif; ?>

          <!-- Цена -->
          <div class="service-price">
            <?php
            $price = get_post_meta(get_the_ID(), 'price', true);
            if ($price) {
              echo '<span>Цена: от ' . esc_html($price) . ' ₽</span>';
            }
            ?>
          </div>

          <!-- Описание -->
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
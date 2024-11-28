<?php get_header(); ?>

<main>
  <div>
    <h1>Статьи</h1>

    <div class="posts-container">
      <?php
      $articles = new WP_Query([
        'post_type' => 'post', // Тип записи: статьи
        'posts_per_page' => 3, // Показываем последние 3 статьи
      ]);

      if ($articles->have_posts()):
        while ($articles->have_posts()):
          $articles->the_post(); ?>
          <a href="<?php the_permalink() ?>" class="posts-container-item">
            <?php the_post_thumbnail(); ?>

            <div class="posts-container-item-info">
              <div>
                <h2><?php the_title(); ?></h2>
                <?php the_excerpt(); ?>
              </div>
              <p> <?php echo get_the_date(); ?> </p>
            </div>
          </a>
        <?php endwhile; ?>
      <?php else: ?>
        <p>Нет статей для отображения.</p>
      <?php endif; ?>
    </div>
  </div>

  <div>
    <h1>Услуги</h1>
    <div class="services-container">
      <?php
      // Запрос для постов из кастомного пост-типа "Услуги"
      $services = new WP_Query([
        'post_type' => 'service',
      ]);
      if ($services->have_posts()):
        while ($services->have_posts()):
          $services->the_post(); ?>
          <a href="<?php the_permalink(); ?>" class="services-container-item">
            <div class="services-container-item-tags">
              <?php
              $tags = get_the_terms(get_the_ID(), 'service_tag');
              if ($tags):
                foreach ($tags as $tag):
                  echo '<span class="tag">' . esc_html($tag->name) . '</span>';
                endforeach;
              endif;
              ?>
            </div>

            <?php if (has_post_thumbnail()): ?>
              <?php the_post_thumbnail('medium'); ?>
            <?php endif; ?>

            <div class="services-container-item-info">
              <h2><?php the_title(); ?></h2>
              <?php
              $price = get_post_meta(get_the_ID(), 'price', true);
              if ($price) {
                echo '<p>от ' . esc_html($price) . ' ₽</p>';
              }
              ?>
            </div>
          </a>
        <?php endwhile; ?>
        <?php wp_reset_postdata(); ?>
      <?php else: ?>
        <p>Нет услуг для отображения.</p>
      <?php endif; ?>
    </div>
</main>

<?php get_footer(); ?>
<?php get_header(); ?>

<main class="single-post">
  <div class="container">
    <?php if (have_posts()):
      while (have_posts()):
        the_post(); ?>
        <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
          <!-- Заголовок -->
          <div class="post-header">
            <h1 class="post-title"><?php the_title(); ?></h1>
            <div class="post-meta">
              <span class="post-author">Автор: <?php the_author(); ?></span>
              <span class="post-date">Опубликовано: <?php echo get_the_date(); ?></span>
              <?php if (has_category()): ?>
                <span class="post-category">Категория: <?php the_category(', '); ?></span>
              <?php endif; ?>
            </div>
          </div>

          <!-- Превью изображения -->
          <?php if (has_post_thumbnail()): ?>
            <div class="post-thumbnail">
              <?php the_post_thumbnail('large', ['class' => 'featured-image']); ?>
            </div>
          <?php endif; ?>

          <!-- Содержимое статьи -->
          <div class="post-content">
            <?php the_content(); ?>
          </div>

          <!-- Теги -->
          <div class="post-tags">
            <?php
            $tags = get_the_tags();
            if ($tags) {
              echo '<ul class="tags">';
              foreach ($tags as $tag) {
                echo '<li class="tag"><span>' . esc_html($tag->name) . '</span></li>';
              }
              echo '</ul>';
            }
            ?>
          </div>
          </di>

          <!-- Блок комментариев -->
          <?php
          if (comments_open() || get_comments_number()):
            comments_template();
          endif;
          ?>
        <?php endwhile; else: ?>
        <p>Статья не найдена.</p>
      <?php endif; ?>
    </div>
</main>

<?php get_footer(); ?>
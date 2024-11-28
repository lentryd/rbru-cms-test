<?php get_header(); ?>

<main>
  <div>
    <h1>Статьи</h1>

    <div class="posts-container">
      <?php if (have_posts()): ?>
        <?php while (have_posts()):
          the_post(); ?>
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
      <?php endif; ?>
    </div>
  </div>

  <div>
    <h1>Услуги</h1>

  </div>
</main>

<?php get_footer(); ?>
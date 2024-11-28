<?php if (have_comments()): ?>
  <h3 class="comments-title">
    <?php
    printf(
      _n(
        'Один комментарий на "%2$s"',
        '%1$s комментариев на "%2$s"',
        get_comments_number(),
        'textdomain'
      ),
      number_format_i18n(get_comments_number()),
      get_the_title()
    );
    ?>
  </h3>

  <ul class="comment-list">
    <?php
    wp_list_comments(array(
      'style' => 'ul',
      'short_ping' => true,
    ));
    ?>
  </ul>

  <?php if (get_comment_pages_count() > 1 && get_option('page_comments')): ?>
    <nav class="comment-navigation">
      <div class="nav-previous"><?php previous_comments_link('&larr; Старые комментарии'); ?></div>
      <div class="nav-next"><?php next_comments_link('Новые комментарии &rarr;'); ?></div>
    </nav>
  <?php endif; ?>

<?php endif; ?>

<?php
comment_form(array(
  'title_reply' => 'Оставить комментарий',
  'label_submit' => 'Отправить',
));
?>
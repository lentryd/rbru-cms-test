<footer>
  <div class="footer-container">
    <?php if (is_active_sidebar('footer-widget-area')): ?>
      <?php dynamic_sidebar('footer-widget-area'); ?>
    <?php endif; ?>
    <span>&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. Все права защищены.</span>
  </div>
</footer>


<?php wp_footer(); ?>
</body>

</html>
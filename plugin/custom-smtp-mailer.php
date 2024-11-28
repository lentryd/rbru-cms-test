<?php
/**
 * Plugin Name: Feedback Notifier
 * Description: Плагин для отправки писем на ваш email, а также сохранения данных в базе.
 * Version: 1.0
 * Author: lentryd
 */

// Действие для отправки письма
add_action('wp_ajax_feedback', 'feedback_notifier_send_mail');
add_action('wp_ajax_nopriv_feedback', 'feedback_notifier_send_mail');
function feedback_notifier_send_mail()
{
  global $wpdb;

  // Получаем данные из запроса
  $name = sanitize_text_field($_POST['name']);
  $email = sanitize_email($_POST['email']);
  $phone = sanitize_text_field($_POST['phone']);
  $message = sanitize_textarea_field($_POST['message']);

  // Проверяем данные
  if (empty($name) || empty($email) || empty($message)) {
    wp_send_json_error('Все обязательные поля должны быть заполнены.');
  }

  if (!is_email($email)) {
    wp_send_json_error('Некорректный email адрес');
  }

  // Сохраняем данные о сообщении в базе данных
  $table_name = $wpdb->prefix . 'feedback_messages';
  $result = $wpdb->insert(
    $table_name,
    [
      'name' => $name,
      'email' => $email,
      'phone' => $phone,
      'message' => $message,
      'date' => current_time('mysql')
    ]
  );
  if (!$result) {
    wp_send_json_error('Ошибка записи в базу данных: ' . $wpdb->last_error);
  }

  // Отправляем письмо
  $subject = 'Новое сообщение обратной связи';
  $sender = array(
    'name' => get_option('sender_name', ''),
    'email' => get_option('sender_email', ''),
  );
  $recipient = array(
    array(
      'name' => 'Admin',
      'email' => get_option('recipient_email', ''),
    )
  );
  $htmlContent = "
    <!DOCTYPE html>
    <html lang=\"ru\">
    <head>
      <meta charset=\"UTF-8\">
      <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
      <title>Новое сообщение обратной связи</title>
      <style>
        body {
          font-family: Arial, sans-serif;
          line-height: 1.6;
          background-color: #f9f9f9;
          color: #333;
          margin: 0;
          padding: 0;
        }
        .email-container {
          max-width: 600px;
          margin: 20px auto;
          padding: 20px;
          background: #fff;
          border: 1px solid #ddd;
          border-radius: 5px;
        }
        .email-header {
          font-size: 18px;
          margin-bottom: 20px;
          color: #444;
        }
        .email-table {
          width: 100%;
          border-collapse: collapse;
        }
        .email-table th, .email-table td {
          text-align: left;
          padding: 10px;
          border-bottom: 1px solid #ddd;
        }
        .email-table th {
          background-color: #f3f3f3;
        }
        .email-footer {
          margin-top: 20px;
          font-size: 14px;
          color: #777;
        }
      </style>
    </head>
    <body>
      <div class=\"email-container\">
        <div class=\"email-header\">Новое сообщение обратной связи</div>
        <table class=\"email-table\">
          <tr>
            <th>Имя</th>
            <td>$name</td>
          </tr>
          <tr>
            <th>Email</th>
            <td>$email</td>
          </tr>
          <tr>
            <th>Телефон</th>
            <td>$phone</td>
          </tr>
          <tr>
            <th>Сообщение</th>
            <td>$message</td>
          </tr>
        </table>
        <div class=\"email-footer\">
          Это сообщение было отправлено с формы обратной связи на сайте " . get_bloginfo() . ".
        </div>
      </div>
    </body>
    </html>
  ";

  $headers = array(
    'content-type' => 'application/json',
    'api-key' => get_option('brevo_api_key', ''),
  );
  $data = array(
    'to' => $recipient,
    'sender' => $sender,
    'subject' => $subject,
    'htmlContent' => $htmlContent,
  );
  $response = wp_remote_post('https://api.brevo.com/v3/smtp/email', array(
    'headers' => $headers,
    'body' => json_encode($data),
  ));

  if (is_wp_error($response)) {
    wp_send_json_error('Ошибка отправки письма.');
  }

  $response_code = wp_remote_retrieve_response_code($response);
  if ($response_code >= 400) {
    wp_send_json_error('Ошибка отправки письма.');
  }

  wp_send_json_success('Сообщение отправлено.');
}

// Создаем таблицу в базе данных при активации плагина
register_activation_hook(__FILE__, 'create_feedback_table');
function create_feedback_table()
{
  global $wpdb;
  $table_name = $wpdb->prefix . 'feedback_messages';
  $charset_collate = $wpdb->get_charset_collate();

  $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
        name VARCHAR(255) NOT NULL,
        email VARCHAR(255) NOT NULL,
        phone VARCHAR(20),
        message TEXT NOT NULL,
        date DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL,
        PRIMARY KEY (id)
    ) $charset_collate;";

  require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
  dbDelta($sql);
}

// Создаем страницу в админке для отображения сообщений
add_action('admin_menu', 'feedback_notifier_menu');
function feedback_notifier_menu()
{
  // Добавляем страницу в меню
  add_menu_page(
    'Обратная связь', // Название страницы
    'Обратная связь', // Название страницы
    'manage_options', // Права доступа
    'feedback_notifier', // Слаг
    'feedback_notifier_page', // Callback для страницы
    'dashicons-email-alt', // Иконка
    6 // Позиция в меню
  );

  // Добавляем подменю для настроек
  add_submenu_page(
    'feedback_notifier', // Ссылка на родительское меню
    'Настройки Feedback Notifier', // Название страницы
    'Настройки', // Название вкладки
    'manage_options', // Права доступа
    'feedback_notifier_settings', // Слаг
    'feedback_notifier_settings_page' // Callback для страницы
  );
}
// Страница отображения сообщений
function feedback_notifier_page()
{
  global $wpdb;
  $table_name = $wpdb->prefix . 'feedback_messages';

  $messages = $wpdb->get_results("SELECT * FROM $table_name ORDER BY date DESC");

  echo '<div class="wrap"><h1>Сообщения обратной связи</h1>';
  echo '<table class="widefat">';
  echo '<thead><tr><th>Дата</th><th>Телефон</th><th>Email</th><th>Сообщение</th></tr></thead>';
  echo '<tbody>';

  foreach ($messages as $message) {
    echo '<tr>';
    echo '<td>' . esc_html($message->date) . '</td>';
    echo '<td>' . esc_html($message->phone) . '</td>';
    echo '<td>' . esc_html($message->email) . '</td>';
    echo '<td>' . esc_html($message->message) . '</td>';
    echo '</tr>';
  }

  echo '</tbody></table></div>';
}
// Страница настроек
function feedback_notifier_settings_page()
{
  if (!current_user_can('manage_options')) {
    return;
  }

  // Сохраняем данные, если форма отправлена
  if (isset($_POST['submit'])) {
    check_admin_referer('feedback_notifier_settings_save');

    update_option('sender_name', sanitize_text_field($_POST['sender_name']));
    update_option('sender_email', sanitize_text_field($_POST['sender_email']));
    update_option('recipient_email', sanitize_text_field($_POST['recipient_email']));
    update_option('brevo_api_key', sanitize_text_field($_POST['brevo_api_key']));

    echo '<div class="updated"><p>Настройки сохранены.</p></div>';
  }

  // Получаем текущие значения
  $sender_name = get_option('sender_name', get_bloginfo());
  $sender_email = get_option('sender_email', get_option('admin_email'));
  $brevo_api_key = get_option('brevo_api_key', '');
  $recipient_email = get_option('recipient_email', get_option('admin_email'));

  ?>
  <div class="wrap">
    <h1>Настройки Feedback Notifier</h1>
    <form method="post">
      <?php wp_nonce_field('feedback_notifier_settings_save'); ?>
      <table class="form-table">
        <tr>
          <th scope="row">
            <label for="sender_email">Sender email</label>
          </th>
          <td>
            <input name="sender_email" type="email" id="sender_email" value="<?php echo esc_attr($sender_email); ?>"
              class="regular-text">
          </td>
        </tr>
        <tr>
          <th scope="row">
            <label for="sender_name">Sender name</label>
          </th>
          <td>
            <input name="sender_name" type="text" id="sender_name" value="<?php echo esc_attr($sender_name); ?>"
              class="regular-text">
          </td>
        </tr>
        <tr>
          <th scope="row">
            <label for="recipient_email">Recipient email</label>
          </th>
          <td>
            <input name="recipient_email" type="text" id="recipient_email"
              value="<?php echo esc_attr($recipient_email); ?>" class="regular-text">
          </td>
        </tr>
        <tr>
          <th scope="row">
            <label for="brevo_api_key">Brevo API Key</label>
          </th>
          <td>
            <input name="brevo_api_key" type="text" id="brevo_api_key" value="<?php echo esc_attr($brevo_api_key); ?>"
              class="regular-text">
          </td>
        </tr>
      </table>
      <?php submit_button(); ?>
    </form>
  </div>
  <?php
}

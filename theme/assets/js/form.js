document.addEventListener("DOMContentLoaded", () => {
  const form = document.querySelector(".feedback-form");
  const messageBlock = form.querySelector(".form-message"); // Блок для сообщений
  const actionUrlInput = document.querySelector("#form-action-url"); // Получаем input с action URL
  const submitButton = form.querySelector(".btn-submit"); // Кнопка отправки
  const loader = submitButton.querySelector(".loader"); // Лоадер внутри кнопки

  form.addEventListener("submit", async (event) => {
    event.preventDefault(); // Останавливаем стандартную отправку формы

    const formData = new FormData(form); // Собираем данные из формы
    const actionUrl = actionUrlInput.value; // Берем URL из hidden input

    // Показываем лоадер на кнопке
    submitButton.disabled = true; // Отключаем кнопку
    loader.style.display = "inline-block"; // Показываем лоадер

    try {
      const response = await fetch(actionUrl, {
        method: "post",
        body: formData,
      });

      if (!response.ok) {
        throw new Error(`Ошибка: ${response.status}`);
      }

      // Получаем данные из ответа
      const msg = await response.json();
      if (msg.success !== true) {
        // Показать сообщение об ошибке
        messageBlock.textContent = msg.data;
        messageBlock.className = "form-message error"; // Добавляем класс для ошибки
        messageBlock.style.display = "block"; // Показываем блок

        console.error("Ошибка при отправке формы:", msg);
      } else {
        // Показать успешное сообщение
        messageBlock.textContent = "Сообщение успешно отправлено!";
        messageBlock.className = "form-message success"; // Добавляем класс для успешного сообщения
        messageBlock.style.display = "block"; // Показываем блок

        form.reset(); // Очистка формы
      }
    } catch (error) {
      // Показать сообщение об ошибке
      messageBlock.textContent =
        "Не удалось отправить сообщение. Попробуйте снова.";
      messageBlock.className = "form-message error"; // Добавляем класс для ошибки
      messageBlock.style.display = "block"; // Показываем блок

      console.error("Ошибка при отправке формы:", error);
    } finally {
      // После завершения процесса скрываем лоадер и восстанавливаем кнопку
      submitButton.disabled = false;
      loader.style.display = "none"; // Прячем лоадер
    }
  });
});

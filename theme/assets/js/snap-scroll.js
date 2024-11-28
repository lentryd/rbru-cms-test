// Получаем элементы с классом .snap-scroll
var snapScroll = document.querySelectorAll(".snap-scroll");

/**
 * Функция инициализации
 * @param {Element} el - элемент, в котором инициализируется скролл
 */
function init(el) {
  // Получаем количество детей в элементе
  const childrenWidth = el.children[0].clientWidth;
  const childrenCount = el.children[0].children.length;

  // Получаем контейнер, в котором будут отображаться кружочки
  var indicator = el.querySelector(".snap-scroll-indicator");
  if (!indicator) {
    // Если контейнер не найден, создаем его
    indicator = document.createElement("div");
    indicator.classList.add("snap-scroll-indicator");
    el.appendChild(indicator);
  }

  // Создаем кружочки которые будут отображать текущую позицию
  const spanItem = document.createElement("div");
  spanItem.classList.add("snap-scroll-indicator-item");

  // Добавляем кружочки в контейнер
  for (let i = 0; i < childrenCount; i++) {
    const clone = spanItem.cloneNode();
    if (i === 0) clone.classList.add("active");
    indicator.appendChild(clone);
    clone.addEventListener("click", function () {
      el.children[0].scrollTo({
        left: childrenWidth * i,
        behavior: "smooth",
      });
    });
  }
}

/**
 * Функция обработчик скролла
 * @param {Event} e - событие скролла
 */
function onScrollEnd(e) {
  // Получаем элемент, в котором произошло событие
  var target = e.target;
  // Получаем родителя элемента
  var parent = target.parentElement;

  // Получаем ширину элемента
  const childrenWidth = target.children[0].clientWidth;
  // Получаем индекс элемента
  const index = Math.round(target.scrollLeft / childrenWidth);

  // Получаем все кружочки
  const items = parent.querySelectorAll(".snap-scroll-indicator-item");
  // Удаляем класс active у всех кружочков
  items.forEach((el) => el.classList.remove("active"));

  // Добавляем класс active текущему кружочку
  items[index].classList.add("active");
}

/**
 * Функция скрытия кружочков
 */
function hideIndicator() {
  // Скрываем кружочки
  snapScroll.forEach((el) => {
    el.querySelector(".snap-scroll-indicator").style.display = "none";
  });
}
/**
 * Функция показа кружочков
 */
function showIndicator() {
  // Показываем кружочки
  snapScroll.forEach((el) => {
    el.querySelector(".snap-scroll-indicator").style.display = "flex";
  });
}
/**
 * Функция изменения кружочков при изменении размера окна
 */
function changeIndicator() {
  if (window.innerWidth > 768) {
    hideIndicator();
  } else {
    showIndicator();
  }
}

// Перебираем все элементы
snapScroll.forEach((el) => {
  init(el);
  // Добавляем обработчик скролла
  el.children[0].addEventListener("scrollend", onScrollEnd);
});

// Добавляем обработчик изменения размера окна
changeIndicator();
window.addEventListener("resize", changeIndicator);

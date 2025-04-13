console.clear();

document.addEventListener("DOMContentLoaded", function () {
  const burger = document.getElementById('mobile-burger');
  const nav = document.getElementById('navLinks');

  burger.addEventListener('click', function () {
    nav.classList.toggle('active');
    burger.classList.toggle('open');
  });
});
$(".hover").mouseleave(
    function() {
      $(this).removeClass("hover");
    }
  );

function openNav() {
  document.getElementById("myNav").style.width = "100%";
  document.getElementById("mobile-burger").style.zIndex = "1";
  document.getElementById("myNav").style.zIndex = "2";
  
}

function closeNav() {
  document.getElementById("myNav").style.width = "0%";
}
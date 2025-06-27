document.addEventListener("DOMContentLoaded", function () {
  let shopNowButton = document.querySelector(".shop-now");
  if (shopNowButton){
    shopNowButton.addEventListener("click", function() {
      alert("Redirecting to the shop page...");
      window.location.href = "products.php";
    });
  }
});
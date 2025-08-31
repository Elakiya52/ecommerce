const cart = [];

async function loadProducts() {
  try {
    const res = await fetch("http://127.0.0.1/ecom_app/backend/getProduct.php");
    const products = await res.json();
    const container = document.getElementById("products");
    container.innerHTML = "";

    if (products.length === 0) {
      container.innerHTML = "<p>No products found in database ❌</p>";
      return;
    }

    products.forEach(p => {
      const card = document.createElement("div");
      card.className = "card";
      card.innerHTML = `
        <img src="assets/images/${p.image}" alt="${p.name}">
        <h3>${p.name}</h3>
        <p>${p.description}</p>
        <p><b>₹${p.price}</b></p>
        <button onclick='addToCart(${p.id})'>Add to Cart</button>
      `;
      container.appendChild(card);
    });
  } catch (err) {
    console.error("❌ Error fetching products:", err);
  }
}

async function addToCart(productId) {
  try {
    const res = await fetch("http://127.0.0.1/ecom_app/backend/addToCart.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json"
      },
      body: JSON.stringify({ id: productId })
    });

    const data = await res.json();
    if (data.success) {
      alert("Product added to cart!");
      updateCartUI(data.cart);
    } else {
      alert("Error adding product to cart: " + (data.error || "Unknown error"));
    }
  } catch (err) {
    
    console.error(err);
  }
}

function updateCartUI(cartData) {
  const cartElem = document.getElementById("cart");
  if (!cartData || cartData.length === 0) {
    cartElem.innerHTML = "<li>Cart is empty</li>";
    return;
  }
  cartElem.innerHTML = "";
  cartData.forEach(({ product_id, name, price, image, quantity }) => {
    const li = document.createElement("li");
    li.innerHTML = `
      <div class="cart-item">
        <img src="assets/images/${image}" alt="${name}" width="50" height="50" />
        <div>
          <h4>${name}</h4>
          <p>Price: ₹${price}</p>
          <p>Quantity: ${quantity}</p>
        </div>
      </div>
    `;
    cartElem.appendChild(li);
  });
}



async function loadCart() {
  try {
    const res = await fetch("http://127.0.0.1/ecom_app/backend/getCart.php");
    const data = await res.json();
    updateCartUI(data.cart);
  } catch (err) {
    console.error("❌ Error loading cart:", err);
  }
}

window.onload = () => {
  loadProducts();
  loadCart();
};

function updateCartUI(cartData) {
  const cartElem = document.getElementById("cart");
  if (!cartData || cartData.length === 0) {
    cartElem.innerHTML = "<li>Cart is empty</li>";
    return;
  }
  cartElem.innerHTML = "";
  cartData.forEach(({ product_id, name, price, image, quantity }) => {
    const li = document.createElement("li");
    li.innerHTML = `
      <div class="cart-item">
        <img src="assets/images/${image}" alt="${name}" width="50" height="50" />
        <div>
          <h4>${name}</h4>
          <p>Price: ₹${price}</p>
          <p>Quantity: ${quantity}</p>
          <button onclick="removeFromCart(${product_id})">❌ Remove</button>
        </div>
      </div>
    `;
    cartElem.appendChild(li);
  });
}

async function removeFromCart(productId) {
  try {
    const res = await fetch("http://127.0.0.1/ecom_app/backend/removeFromCart.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json"
      },
      body: JSON.stringify({ id: productId })
    });

    const data = await res.json();
    if (data.success) {
      alert("Product removed from cart!");
      updateCartUI(data.cart);
    } else {
      alert("Error removing product: " + (data.error || "Unknown error"));
    }
  } catch (err) {
    alert("Error removing product.");
    console.error(err);
  }
}




